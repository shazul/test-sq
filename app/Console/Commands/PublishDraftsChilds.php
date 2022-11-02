<?php

namespace Pimeo\Console\Commands;

use Illuminate\Console\Command;

use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\ChildProduct;

class PublishDraftsChilds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soprema:publishDraftsChilds {companyId : ID of the company which products belong to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish and assign the website media to all draft child products of a company.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $company_id = $this->argument('companyId');
        $products = ChildProduct::whereCompanyId($company_id)
            ->whereStatus(AttributableModelStatus::DRAFT_STATUS)
            ->get();

        $products->each(function ($product) {
            $product->update([
                'status' => AttributableModelStatus::PUBLISHED_STATUS,
            ]);
            $product->mediaLinks()->updateOrCreate(['media_id' => 1]);
        });
    }
}
