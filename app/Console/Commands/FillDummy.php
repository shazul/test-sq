<?php

namespace Pimeo\Console\Commands;

use Faker\Generator;
use Illuminate\Console\Command;
use Pimeo\Models\Attribute;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\Company;
use Pimeo\Models\Detail;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\LinkAttributeValue;
use Pimeo\Models\LinkMedia;
use Pimeo\Models\Media;
use Pimeo\Models\ParentProduct;
use Pimeo\Models\Specification;
use Pimeo\Models\System;
use Symfony\Component\Console\Helper\ProgressBar;

class FillDummy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:dummy
        {--products= : Number of products to create <comment>(requires children)</comment>}
        {--children= : Number of products\' children to create <comment>(requires products)</comment>}
        {--systems= : Number of system to create}
        {--details= : Number of details to create}
        {--specifications= : Number of specifications to create}
        {--companies= : Number of companies to create}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill database.';

    /**
     * The faker instance.
     *
     * @var Generator
     */
    protected $faker;

    /**
     * Create a new console command instance.
     *
     * @param Generator $faker
     */
    public function __construct(Generator $faker)
    {
        parent::__construct();

        $this->faker = $faker;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Filling database with dummy data');

        $products = (int)$this->option('products');
        $children = (int)$this->option('children');
        $details = (int)$this->option('details');
        $systems = (int)$this->option('systems');
        $specifications = (int)$this->option('specifications');
        $companies = (int)$this->option('companies');

        if ($products > 0 && $children > 0) {
            $this->fillParentProducts($products, $children);
        }

        if ($systems > 0) {
            $this->fillSystems($systems);
        }

        if ($details > 0) {
            $this->fillDetails($details);
        }

        if ($specifications > 0) {
            $this->fillSpecifications($specifications);
        }

        if ($companies > 0) {
            $this->fillCompanies($companies);
        }
    }

    protected function fillCompanies($companies)
    {
        $this->comment('> Filling companies');

        $progress = new ProgressBar($this->getOutput(), $companies);
        $progress->start();

        for ($i = 0; $i < $companies; $i++) {
            factory(Company::class)->create();

            $progress->advance();
        }

        $progress->finish();
        $this->info("\n");
    }

    protected function fillDetails($details)
    {
        $this->comment('> Filling details');

        $progress = new ProgressBar($this->getOutput(), $details);
        $progress->start();

        /** @var Attribute $attribute */
        $attribute = Attribute::where('name', 'detail_name')->first();

        for ($i = 0; $i < $details; $i++) {
            $link = new LinkAttribute;
            $link->attribute_id = $attribute->id;

            /** @var Detail $detail */
            $detail = factory(Detail::class)->create();

            $detail->linkAttributes()->save($link);

            factory(LinkAttributeValue::class)->create([
                'link_attribute_id' => $link->id,
                'values'            => [
                    'fr' => $this->faker->words(3, true),
                    'en' => $this->faker->words(3, true),
                ],
            ]);

            $progress->advance();
        }

        $progress->finish();
        $this->info("\n");
    }

    protected function fillSpecifications($specifications)
    {
        $this->comment('> Filling specifications');

        $progress = new ProgressBar($this->getOutput(), $specifications);
        $progress->start();

        /** @var Attribute $attribute */
        $attribute = Attribute::where('name', 'specification_name')->first();

        for ($i = 0; $i < $specifications; $i++) {
            $link = new LinkAttribute;
            $link->attribute_id = $attribute->id;

            /** @var Specification $specification */
            $specification = factory(Specification::class)->create();

            $specification->linkAttributes()->save($link);

            factory(LinkAttributeValue::class)->create([
                'link_attribute_id' => $link->id,
                'values'            => [
                    'fr' => $this->faker->words(3, true),
                    'en' => $this->faker->words(3, true),
                ],
            ]);

            $progress->advance();
        }

        $progress->finish();
        $this->info("\n");
    }

    protected function fillSystems($systems)
    {
        $this->comment('> Filling systems');

        $progress = new ProgressBar($this->getOutput(), $systems);
        $progress->start();

        /** @var Attribute $attribute */
        $attribute = Attribute::where('name', 'system_name')->first();

        for ($i = 0; $i < $systems; $i++) {
            $link = new LinkAttribute;
            $link->attribute_id = $attribute->id;

            /** @var System $system */
            $system = factory(System::class)->create();

            $system->linkAttributes()->save($link);

            factory(LinkAttributeValue::class)->create(
                [
                    'link_attribute_id' => $link->id,
                    'values'            => [
                        'fr' => $this->faker->words(3, true),
                        'en' => $this->faker->words(3, true),
                    ],
                ]
            );

            $progress->advance();
        }

        $progress->finish();
        $this->info("\n");
    }

    protected function fillParentProducts($products, $children)
    {
        $this->comment('> Filling products and children');

        if ($products > $children) {
            $this->error('Children must be higher than products.');
            exit(1);
        }

        $mod = $children / $products;

        $progress = new ProgressBar($this->getOutput(), $products);
        $progressChildren = new ProgressBar($this->getOutput(), $children);
        $progress->setBarWidth(30);
        $progressChildren->setBarWidth(30 - (strlen($children) - strlen($products)) * 2);

        $progress->start();
        print "\n";
        $progressChildren->start();

        /** @var Media $media */
        $media = Media::first();

        /** @var Attribute $attribute */
        $attribute = Attribute::where('name', 'product_name')->first();

        for ($i = 0; $i < $children; $i++) {
            $this->getOutput()->write("\033[1A");

            if ($i % $mod  == 0) {
                $product = factory(ParentProduct::class)->create();

                $link = new LinkAttribute;
                $link->attribute_id = $attribute->id;

                $product->linkAttributes()->save($link);

                factory(LinkAttributeValue::class)->create(
                    [
                        'link_attribute_id' => $link->id,
                        'values'            => [
                            'fr' => $this->faker->words(3, true),
                            'en' => $this->faker->words(3, true),
                        ],
                    ]
                );

                $progress->advance();
            }

            /** @var ChildProduct $child */
            $child = factory(ChildProduct::class)->create([
                'parent_product_id' => $product->id,
            ]);

            $link = new LinkAttribute;
            $link->attribute_id = $attribute->id;

            $child->linkAttributes()->save($link);

            factory(LinkAttributeValue::class)->create([
                'link_attribute_id' => $link->id,
                'values'            => [
                    'fr' => $this->faker->words(3, true),
                    'en' => $this->faker->words(3, true),
                ],
            ]);

            $linkMedia = new LinkMedia;
            $linkMedia->media_id = $media->id;
            $child->mediaLinks()->save($linkMedia);

            print "\n";
            $progressChildren->advance();
        }

        $progress->finish();
        $progressChildren->finish();
        $this->info("\n");
    }
}
