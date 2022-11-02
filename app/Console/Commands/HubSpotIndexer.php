<?php

namespace Pimeo\Console\Commands;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Pimeo\Models\CompanyBlog;
use SevenShores\Hubspot\Factory;
use Symfony\Component\Console\Helper\ProgressBar;

class HubSpotIndexer extends Command
{
    /**
     * @const string
     */
    const TYPE = 'blog';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soprema:index:hubspot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index blog posts from HubSpot';

    /**
     * @var Factory
     */
    protected $hubSpot;

    /**
     * @var Collection
     */
    protected $posts;

    /**
     * @var Collection
     */
    protected $topics;

    /**
     * @var ProgressBar
     */
    protected $progress;

    /**
     * @var string
     */
    protected $lastLine;

    /**
     * @var Collection
     */
    protected $cache;

    /**
     * @var Client
     */
    protected $client;

    /**
     * Create a new command instance.
     *
     * @param Factory $hubSpot
     */
    public function __construct(Factory $hubSpot)
    {
        parent::__construct();

        $this->hubSpot = $hubSpot;
        $this->cache = collect();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Index HubSpot blog posts:');

        $time = microtime(true);

        $this->createElasticSearchClient();
        $this->fetchBlogTopics();
        $this->prepareBlogTopics();
        $this->fetchBlogPosts();
        $this->prepareBlogPosts();
        $this->mergePostsTopics();
        $this->defineIndicesForPosts();
        $this->setMapping();
        $this->indexPosts();

        $seconds = round(microtime(true) - $time, 4);

        $this->output->writeln("> <info>HubSpot has been indexed successfully in {$seconds} seconds</info>");
    }

    private function createElasticSearchClient()
    {
        $this->client = ClientBuilder::create()
            ->setHosts([config('elasticsearch.client_ip') . ':' . config('elasticsearch.client_port')])
            ->setRetries(0)
            ->build();
    }

    private function fetchBlogTopics()
    {
        $this->write('Fetching blog topics');

        $params = [
            'limit' => 10000,
        ];

        $this->topics = collect(array_get($this->hubSpot->blogTopics()->all($params)->toArray(), 'objects'));

        $this->done($this->topics->count() . ' fetched');
    }

    private function prepareBlogTopics()
    {
        $this->write('Preparing blog topics');
        $this->createProgress($this->topics->count());

        $this->topics->transform(function ($topic) {
            $this->progress->advance();

            return array_only($topic, [
                'id',
                'name',
                'slug',
            ]);
        });

        $this->finish();
    }

    private function fetchBlogPosts()
    {
        $this->write('Fetching blog posts');

        $params = [
            'limit' => 10000,
            'state' => 'PUBLISHED',
        ];

        $this->posts = collect(array_get($this->hubSpot->blogPosts()->all($params)->toArray(), 'objects'));

        $this->done($this->posts->count() . ' fetched');
    }

    private function prepareBlogPosts()
    {
        $this->write('Preparing blog posts');
        $this->createProgress($this->posts->count());

        $this->posts->transform(function ($post) {
            $this->progress->advance();

            return array_only($post, [
                'title',
                'topics',
                'id',
                'slug',
                'url',
                'post_body',
                'post_summary',
                'blog_post_author',
                'parent_blog',
                'publish_date',
            ]);
        });

        $this->finish();
    }

    private function mergePostsTopics()
    {
        $this->write('Merging posts and topics');
        $this->createProgress($this->posts->count());

        $this->posts->transform(function ($post) {
            $this->progress->advance();

            $post['topics'] = collect($post['topics'])->transform(function ($topicId) {
                return $this->topics->first(function ($index, $topic) use ($topicId) {
                    return $topic['id'] === $topicId;
                });
            })->toArray();

            return $post;
        });

        $this->finish();
    }

    private function defineIndicesForPosts()
    {
        $this->write('Defining indices for posts');
        $this->createProgress($this->posts->count());

        $this->posts->transform(function ($post) {
            $this->progress->advance();

            return [
                'index' => $this->findIndexForBlog(array_get($post, 'parent_blog.id')),
                'type'  => self::TYPE,
                'id'    => str_slug($post['slug'] . ' ' . $post['id']),
                'body'  => $post,
            ];
        });

        $this->finish();
    }

    private function findIndexForBlog($blogId)
    {
        if ($this->cache->has($blogId)) {
            return $this->cache->get($blogId);
        }

        $blog = CompanyBlog::whereBlogId($blogId)->first();

        $index = 'company-' . $blog->company_id . '-' . $blog->language->code;

        $this->cache->put($blogId, $index);

        return $index;
    }

    private function indexPosts()
    {
        $this->write('Sending posts to Elastic Search');
        $this->createProgress($this->posts->count());

        $this->posts->each(function ($post) {
            $this->progress->advance();

            $this->client->index($post);
        });

        $this->finish();
    }

    private function setMapping()
    {
        $this->write('Defining mapping');

        $blog = CompanyBlog::all();

        $this->createProgress($blog->count());

        $blog->each(function ($blog) {
            $this->progress->advance();

            $mapping = $this->client->indices()->getMapping([
                'index' => $this->findIndexForBlog($blog->blog_id),
                'type'  => self::TYPE,
            ]);

            if (!empty($mapping)) {
                return;
            }

            $this->client->indices()->putMapping([
                'index' => $this->findIndexForBlog($blog->blog_id),
                'type'  => self::TYPE,
                'body'  => [
                    'properties' => [
                        'title'            => [
                            'type'     => 'string',
                            'analyzer' => 'ascii_lower',
                            'fields'   => [
                                'raw' => [
                                    'type'  => 'string',
                                    'index' => 'not_analyzed',
                                ],
                            ],
                        ],
                        'topics'           => [
                            'type'       => 'nested',
                            'properties' => [
                                'name' => [
                                    'type'  => 'string',
                                    'index' => 'not_analyzed',
                                ],
                                'slug' => [
                                    'type'  => 'string',
                                    'index' => 'not_analyzed',
                                ],
                            ],
                        ],
                        'id'               => [
                            'type'  => 'long',
                            'index' => 'not_analyzed',
                        ],
                        'slug'             => [
                            'type'  => 'string',
                            'index' => 'not_analyzed',
                        ],
                        'url'              => [
                            'type'  => 'string',
                            'index' => 'not_analyzed',
                        ],
                        'post_body'        => [
                            'type'     => 'string',
                            'analyzer' => 'ascii_lower',
                        ],
                        'post_summary'     => [
                            'type'  => 'string',
                            'index' => 'not_analyzed',
                        ],
                        'publish_date'     => [
                            'type'  => 'long',
                            'index' => 'not_analyzed',
                        ],
                        'blog_post_author' => [
                            'type'       => 'nested',
                            'properties' => [
                                'display_name'     => [
                                    'type'  => 'string',
                                    'index' => 'not_analyzed',
                                ],
                                'email'            => [
                                    'type'  => 'string',
                                    'index' => 'not_analyzed',
                                ],
                                'facebook'         => [
                                    'type'  => 'string',
                                    'index' => 'not_analyzed',
                                ],
                                'twitter'          => [
                                    'type'  => 'string',
                                    'index' => 'not_analyzed',
                                ],
                                'twitter_username' => [
                                    'type'  => 'string',
                                    'index' => 'not_analyzed',
                                ],
                                'website'          => [
                                    'type'  => 'string',
                                    'index' => 'not_analyzed',
                                ],
                            ],
                        ],
                    ],
                ],
            ]);
        });

        $this->finish();
    }

    /**
     * @return $this
     */
    private function finish()
    {
        $this->progress->clear();
        $this->clearLineUp();

        $this->output->writeln('');
        $this->lineUp();

        $count = $this->progress->getMaxSteps();

        $this->done("{$count} processed");

        return $this;
    }

    /**
     * @param $count
     */
    private function createProgress($count)
    {
        $this->progress = new ProgressBar($this->output, $count);
        $this->progress->start();
    }

    /**
     * @param $line
     */
    private function write($line)
    {
        $this->lastLine = '> ' . $line;

        $this->output->writeln($this->lastLine . ' ...');
    }

    /**
     * @param string $message
     */
    private function done($message = 'done')
    {
        $this->lineUp();

        $line = $this->lastLine . ': <comment>' . $message . '</comment>';

        $this->output->writeln($line);
    }

    private function lineUp()
    {
        $this->output->write("\033[1A");
    }

    private function clearLineUp()
    {
        $this->lineUp();
        $this->output->writeln('');
    }
}
