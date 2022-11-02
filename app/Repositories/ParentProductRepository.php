<?php

namespace Pimeo\Repositories;

use Pimeo\Models\LinkAttribute;
use Pimeo\Models\Nature;
use Pimeo\Models\ParentProduct;
use Pimeo\Repositories\Traits\Decodable;
use Pimeo\Repositories\Traits\QueryableFields;
use Pimeo\Repositories\Traits\Sortable;

class ParentProductRepository
{
    use Decodable, QueryableFields, Sortable;

    /**
     * Get all instance of ParentProduct.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\ParentProduct[]
     */
    public function all()
    {
        return ParentProduct::whereCompanyId(current_company()->id)->all();
    }

    /**
     * Retourne tous les childProducts avec leurs attributs et ceux de leur parent
     * pour le listing dans la page child-products
     *
     * @param string $status
     * @param string|null $sorting
     * @param string|null $langCode
     * @return array : ['1' => ['child_product_name' => 'Produit', 'parent_id' => 1]]
     */
    public function allForListing($status = '*', $sorting = null, $langCode = null)
    {
        $productsList = $this->getQueryForFields(
            'parent_products',
            ParentProduct::class,
            ['name', 'building_component'],
            ['nature_id']
        );

        if ($status != '*') {
            $productsList->where('parent_products.status', $status);
        }

        $productsList->orderBy('parent_products.updated_at', 'desc');

        $productsList = $productsList->where('parent_products.company_id', current_company()->id)->get();

        $products = [];
        foreach ($productsList as $product) {
            $products[$product->attributable_id][$product->name] = $this->decodeValues($product);
            $products[$product->attributable_id]['media_name'][$product->media_name] = $product->media_name;
            $products[$product->attributable_id]['product_nature'] = $this->natureAttribute($product);
        }

        if (!is_null($sorting)) {
            $products = $this->sortResults($sorting, $products, $langCode);
        }

        return $products;
    }

    /**
     * Retourne tous les childProducts avec leurs attributs et ceux de leur parent
     * pour le listing dans la page child-products
     *
     * @return array : ['1' => ['child_product_name' => 'Produit', 'parent_id' => 1]]
     */
    public function allForImport()
    {
        $productsList = $this->getQueryForFields(
            'parent_products',
            ParentProduct::class,
            ['product_nature', 'name', 'product_role']
        )->where('parent_products.company_id', current_company()->id)->get();

        $products = [];
        foreach ($productsList as $product) {
            $products[$product->attributable_id][$product->name] = $this->decodeValues($product);
        }

        return $products;
    }

    /**
     * Find an instance of ParentProduct with the given ID.
     *
     * @param  int  $id
     * @return \Pimeo\Models\ParentProduct
     */
    public function find($id)
    {
        return ParentProduct::whereCompanyId(current_company()->id)->find($id);
    }

    public function findWithFields($id, $fields)
    {
        $formatedProduct = [];

        $productsList = $this->getQueryForFields('parent_products', ParentProduct::class, $fields)
            ->where('parent_products.id', $id)
            ->where('parent_products.company_id', current_company()->id)
            ->get();

        foreach ($productsList as $product) {
            $formatedProduct[$product->name] = $this->decodeValues($product);
        }

        return $formatedProduct;
    }

    /**
     * Create a new instance of ParentProduct.
     *
     * @param  array  $attributes
     * @return \Pimeo\Models\ParentProduct
     */
    public function create(array $attributes = [])
    {
        return ParentProduct::create($attributes);
    }

    /**
     * Update the ParentProduct with the given attributes.
     *
     * @param  int    $id
     * @param  array  $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return ParentProduct::find($id)->update($attributes);
    }

    /**
     * Delete an entry with the given ID.
     *
     * @param  int  $id
     * @return bool|null
     * @throws \Exception
     */
    public function delete($id)
    {
        return ParentProduct::find($id)->delete();
    }

    /**
     * Find all Parent Products for a given company
     *
     * @param int $company_id
     * @return \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\ParentProduct[]
     */
    public function allInCompany($company_id)
    {
        return ParentProduct::whereCompanyId($company_id)
            ->with(['linkAttributes' => function ($query) {
                $query->whereHas('attribute', function ($query) {
                    $query->where('name', 'name');
                });
            }])
            ->get();
    }

    /**
     * @param $parent_product
     * @return mixed
     */
    public function natureAttribute($parent_product)
    {
        static $natures = null;
        // Liste des natures provenant de la table d'attributs
        if ($natures === null) {
            $natures = Nature::all()->keyBy('id');
        }

        $nature = [];
        foreach (get_current_company_languages() as $language) {
            $nature[$language->code] = trans(
                "attribute.natures.{$natures[$parent_product->nature_id]->code}",
                [],
                'messages',
                $language->code
            );
        }

        return $nature;
    }

    /**
     * Verify if the product has values for all the min_required attributes
     * @param  int  $id product id
     * @return boolean
     */
    public function hasRequiredAttributes($id)
    {
        $attributesRepo = app(AttributeRepository::class);
        $requiredAttributes = $attributesRepo->allRequired('parent_product');

        foreach ($requiredAttributes as $reqAttribute) {
            $linkAttribute = LinkAttribute::whereAttributeId($reqAttribute->id)
                ->whereAttributableId($id)
                ->first();
            if ($linkAttribute == null || !isset($linkAttribute->values)) {
                return false;
            }
        }

        return true;
    }
}
