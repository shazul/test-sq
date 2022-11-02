<?php

use Illuminate\Database\Seeder;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\Company;
use Pimeo\Models\Detail;
use Pimeo\Models\Media;
use Pimeo\Models\ParentProduct;
use Pimeo\Models\Specification;
use Pimeo\Models\System;
use Pimeo\Models\TechnicalBulletin;

class MediaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $media = factory(Media::class)->create([
            'name' => 'website',
            'code' => 'website',
        ]);

        /** @var Company $company */
        $company = Company::first();

        $company->medias()->attach($media);


        $this->linkToAllProducts($media);
        $this->linkToAllDetails($media);
        $this->linkToAllSpecification($media);
        $this->linkToAllSystems($media);
        $this->linkToAllTechnicalBulletin($media);

    }


    /**
     * Link a media to all products
     *
     * @param Media $media
     */
    protected function linkToAllProducts(Media $media)
    {
        /** @var ParentProduct[] $parent_products */
        $parent_products = ParentProduct::all();
        /** @var ChildProduct[] $parent_products */
        $child_products = ChildProduct::all();

        foreach ($parent_products as $parent_product) {
            $medias = $parent_product->mediaLinks;
            if (count($medias) == 0) {
                $parent_product->mediaLinks()->create(['media_id' => $media->id]);
            }
        }

        foreach ($child_products as $child_product) {
            $medias = $child_product->mediaLinks;
            if (count($medias) == 0) {
                $child_product->mediaLinks()->create(['media_id' => $media->id]);
            }
        }
    }

    /**
     * Link a media to all systems
     *
     * @param Media $media
     */
    protected function linkToAllSystems(Media $media)
    {
        /** @var System[] $systems */
        $systems = System::all();

        foreach ($systems as $system) {
            $medias = $system->mediaLinks;
            if (count($medias) == 0) {
                $system->mediaLinks()->create(['media_id' => $media->id]);
            }
        }
    }

    /**
     * Link media to all specifications
     *
     * @param Media $media
     */
    protected function linkToAllSpecification(Media $media)
    {
        /** @var Specification[] $specifications */
        $specifications = Specification::all();

        foreach ($specifications as $specification) {
            $medias = $specification->mediaLinks;
            if (count($medias) == 0) {
                $specification->mediaLinks()->create(['media_id' => $media->id]);
            }
        }
    }

    /**
     * Link media to all details
     *
     * @param Media $media
     */
    protected function linkToAllDetails(Media $media)
    {
        /** @var Detail[] $details */
        $details = Detail::all();

        foreach ($details as $detail) {
            $medias = $detail->mediaLinks;
            if (count($medias) == 0) {
                $detail->mediaLinks()->create(['media_id' => $media->id]);
            }
        }
    }

    /**
     * Link media to all technical bulletin
     *
     * @param Media $media
     */
    protected function linkToAllTechnicalBulletin(Media $media)
    {
        /** @var TechnicalBulletin[] $technical_bulletins */
        $technical_bulletins = TechnicalBulletin::all();

        foreach ($technical_bulletins as $technical_bulletin) {
            $medias = $technical_bulletin->mediaLinks;
            if (count($medias) == 0) {
                $technical_bulletin->mediaLinks()->create(['media_id' => $media->id]);
            }
        }
    }
}
