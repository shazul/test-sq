<?php

namespace Pimeo\Repositories;

use Illuminate\Support\Facades\Auth;
use Pimeo\Events\Pim\ChildProductWasCreated;
use Pimeo\Jobs\Pim\Product\Create;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\Company;
use Pimeo\Models\ParentProduct;
use Pimeo\Repositories\Traits\Decodable;
use Pimeo\Repositories\Traits\QueryableFields;
use Pimeo\Repositories\Traits\Sortable;

class ChildProductRepository
{
    use Decodable, QueryableFields, Sortable;

    private $natureRepository;

    private $languages = [];

    public function __construct(NatureRepository $natureRepository)
    {
        $this->natureRepository = $natureRepository;
        if (Auth::user()) {
            $this->languages = Auth::user()->getCompany()->languages()->get();
        }
    }

    /**
     * Get all instance of ChildProduct.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\ChildProduct[]
     */
    public function all()
    {
        return ChildProduct::whereCompanyId(current_company()->id)->all();
    }

    public function parentChildrenExcept(ChildProduct $childProduct)
    {
        if (!$childProduct->parentProduct) {
            return [];
        }

        /** @var ChildProduct[] $children */
        $children = $childProduct
            ->parentProduct
            ->childProducts()
            ->where('id', '!=', $childProduct->id)
            ->where('company_id', '=', current_company()->id)
            ->get();

        $products = [];
        foreach ($children as $child) {
            $attribute = $child
                ->linkAttributes()
                ->whereHas('attribute', function ($query) {
                    $query->where('name', 'child_product_name');
                })->first();

            $code = $child
                ->linkAttributes()
                ->whereHas('attribute', function ($query) {
                    $query->where('name', 'child_product_code');
                })->first();

            $data = [
                'id'   => $child->id,
                'text' => $attribute->values,
                'code' => '',
            ];

            if (isset($code->values)) {
                $data['code'] = $code->values;
            }

            $products[] = $data;
        }

        return $products;
    }

    /**
     * Retourne tous les childProducts avec leurs attributs et ceux de leur parent
     * pour le listing dans la page child-products
     *
     * @param string $status
     * @param string|null $sorting
     * @param string|null $langCode
     *
     * @return array : ['1' => ['child_product_name' => 'Produit', 'parent_id' => 1]]
     */
    public function allForListing($status = '*', $sorting = null, $langCode = null)
    {
        $productsList = $this->getQueryForFields(
            'child_products',
            ChildProduct::class,
            ['child_product_name', 'child_product_code'],
            ['child_products.status', 'parent_product_id'],
            false
        );

        if ($status != '*') {
            $productsList->where('child_products.status', $status);
        }

        $productsList = $productsList->where('child_products.company_id', current_company()->id)->get();

        $products = [];
        foreach ($productsList as $product) {
            $products[$product->attributable_id]['parent_id'] = $product->parent_product_id;
            $products[$product->attributable_id][$product->name] = json_decode($product->values, true);
            $products[$product->attributable_id]['media_name'][$product->media_name] = $product->media_name;
        }

        $parentProductsList = $this->getQueryForFields(
            'parent_products',
            ParentProduct::class,
            ['name'],
            ['nature_id']
        )->get();

        $parentProductsList = collect($parentProductsList)->groupBy('attributable_id');

        // Ajouter les attributs parents au gros array de products
        foreach ($products as $product_id => $product) {
            if (!empty($product['parent_id'])) {
                $parentAttributes = $parentProductsList[$product['parent_id']];
                foreach ($parentAttributes as $attribute) {
                    $products[$product_id][$attribute->name] = $this->decodeValues($attribute);
                }
                $products[$product_id]['product_nature'] = $this->natureRepository
                                                                ->getTranslatedByID($parentAttributes[0]->nature_id);
            }
        }

        if (!is_null($sorting)) {
            $products = $this->sortResults($sorting, $products, $langCode);
        }

        return $products;
    }


    /**
     * Find an instance of ChildProduct with the given ID.
     *
     * @param  int $id
     * @return \Pimeo\Models\ChildProduct
     */
    public function find($id)
    {
        return ChildProduct::whereCompanyId(current_company()->id)->find($id);
    }

    /**
     * Create a new instance of ChildProduct.
     *
     * @param  array $attributes
     * @return \Pimeo\Models\ChildProduct
     */
    public function create(array $attributes = [])
    {
        /** @var Company $company */
        $company = current_company();
        $defaultAttributes = [
            'company_id' => $company->id,
            'company_catalog_id' => $company->companyCatalogs->first()->id,
        ];
        $attributes = array_merge($defaultAttributes, $attributes);

        $createHelper = new Create();

        $childProduct = ChildProduct::create($attributes);
        $createHelper->product = $childProduct;
        $createHelper->addAttributes($attributes['attributes']);

        if (isset($attributes['media'])) {
            $createHelper->addMedia($attributes['media']);
        }
        $createHelper->product->save();

        event(new ChildProductWasCreated($childProduct->fresh()));

        return $childProduct;
    }

    /**
     * Update the ChildProduct with the given attributes.
     *
     * @param  int $id
     * @param  array $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return ChildProduct::find($id)->update($attributes);
    }

    /**
     * Delete an entry with the given ID.
     *
     * @param  int $id
     * @return bool|null
     * @throws \Exception
     */
    public function delete($id)
    {
        return ChildProduct::find($id)->delete();
    }

    public function natureID($child_product)
    {
        return $child_product->parentProduct->nature_id;
    }
}
