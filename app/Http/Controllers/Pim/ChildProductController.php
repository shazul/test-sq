<?php

namespace Pimeo\Http\Controllers\Pim;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Pimeo\Forms\ChildProductApproveForm;
use Pimeo\Forms\ChildProductCreateForm;
use Pimeo\Forms\ChildProductEditParentForm;
use Pimeo\Forms\ChildProductForm;
use Pimeo\Forms\ChildProductParentlessForm;
use Pimeo\Forms\ChildProductShowForm;
use Pimeo\Http\Controllers\Controller;
use Pimeo\Http\Requests\Pim\ChildProduct\AttributeUpdateRequest;
use Pimeo\Http\Requests\Pim\ChildProduct\CopyAttributeRequest;
use Pimeo\Http\Requests\Pim\ChildProduct\CreateRequest;
use Pimeo\Http\Requests\Pim\ChildProduct\DeleteRequest;
use Pimeo\Http\Requests\Pim\ChildProduct\EditRequest;
use Pimeo\Http\Requests\Pim\ChildProduct\PublishRequest;
use Pimeo\Http\Requests\Pim\ChildProduct\StoreRequest;
use Pimeo\Http\Requests\Pim\ChildProduct\UpdateParentRequest;
use Pimeo\Http\Requests\Pim\ChildProduct\UpdateRequest;
use Pimeo\Jobs\Pim\ChildProduct\CopyAttributes;
use Pimeo\Jobs\Pim\ChildProduct\DeleteChildProduct;
use Pimeo\Jobs\Pim\ChildProduct\UpdateChildProduct;
use Pimeo\Jobs\Pim\ChildProduct\UpdateChildProductParent;
use Pimeo\Jobs\Pim\ChildProduct\UpdateChildProductParentless;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\Attribute;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\LinkAttribute;
use Pimeo\Repositories\AttributeRepository;
use Pimeo\Repositories\ChildProductRepository;
use Pimeo\Services\InlineAttributeEditionTrait;
use Pimeo\Services\ModelListing;

class ChildProductController extends Controller
{
    use FormBuilderTrait, ModelListing, InlineAttributeEditionTrait;

    /** @var ChildProductRepository */
    private $childProducts;

    /** @var AttributeRepository */
    protected $attributes;

    public function __construct(ChildProductRepository $childProducts, AttributeRepository $attributes)
    {
        $this->attributes = $attributes;
        $this->childProducts = $childProducts;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param string  $status
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $status = AttributableModelStatus::PUBLISHED_STATUS)
    {
        $list = $this->listAll('products', $request, 'child-product.index', ChildProductRepository::class, $status);

        $this->breadcrumb('child-product');

        return view('pim.child-products.index')
            ->with($list)
            ->withActiveStatus($status)
        ;
    }

    protected function getStatusList()
    {
        return [
            AttributableModelStatus::PUBLISHED_STATUS  => [],
            AttributableModelStatus::DRAFT_STATUS      => [],
            AttributableModelStatus::PARENTLESS_STATUS => [],
            AttributableModelStatus::FRESH_STATUS      => [],
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateRequest $request
     * @param FormBuilder   $formBuilder
     * @return \Illuminate\Http\Response
     */
    public function create(CreateRequest $request, FormBuilder $formBuilder)
    {
        $attributes = $this->attributes->allRequired('child_product');

        $form = $formBuilder->create(ChildProductCreateForm::class, [
            'method' => 'POST',
            'url'    => route('child-product.store'),
            'id'     => 'form_create_child_product',
        ], ['attributes' => $attributes]);

        $this->breadcrumb('child-product', trans('breadcrumb.child-products.create'), 'child-product.create');

        return view('pim.child-products.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest $request Child product create request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $attributes = $request->all();
        $attributes['status'] = AttributableModelStatus::PARENTLESS_STATUS;
        $this->childProducts->create($attributes);

        flash()->success(trans('child-product.create.saved'), true);

        return redirect()->route('child-product.index');
    }

    public function show(ChildProduct $child_product, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(ChildProductShowForm::class, [
            'readonly' => true,
        ], [
            'attributes' => $child_product->linkAttributes,
            'medias'     => $child_product->mediaLinks,
            'product'    => $child_product,
        ]);

        $this->breadcrumb('child-product', trans('breadcrumb.child-products.show'), 'child-product.show');

        return view('pim.child-products.show', compact('form', 'child_product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param EditRequest  $request
     * @param ChildProduct $child_product
     * @param  FormBuilder $formBuilder
     * @return \Illuminate\Http\Response
     */
    public function edit(EditRequest $request, ChildProduct $child_product, FormBuilder $formBuilder)
    {
        $required_attributes = $this->attributes->allRequired('child_product');

        $form = $formBuilder->create(ChildProductForm::class, [
            'method' => 'PUT',
            'url'    => route('child-product.update', $child_product->id),
            'id'     => 'form_edit_child_product',
        ], [
            'required_attributes' => $required_attributes,
            'attributes'          => $child_product->linkAttributes,
            'medias'              => $child_product->mediaLinks,
            'product'             => $child_product,
        ]);

        $other_children = $this->getChildrenExcept($child_product);

        $nature = $this->childProducts->natureID($child_product);
        $attributes = $this->attributes->allNotLinkedToProduct(
            $child_product,
            $nature,
            Attribute::MODEL_TYPE_CHILD_PRODUCT
        );

        $this->breadcrumb('child-product', trans('breadcrumb.child-products.edit'), 'child-product.edit');

        return view('pim.child-products.edit', compact('form', 'child_product', 'other_children', 'attributes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param EditRequest  $request
     * @param ChildProduct $child_product
     * @param  FormBuilder $formBuilder
     * @return \Illuminate\Http\Response
     */
    public function approve(EditRequest $request, ChildProduct $child_product, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(ChildProductApproveForm::class, [
            'method' => 'POST',
            'url'    => route('child-product.approve-edit', $child_product->id),
        ], [
            'attributes' => $child_product->linkAttributes,
            'product'    => $child_product,
        ]);

        $this->breadcrumb('child-product', trans('breadcrumb.child-products.approve'), 'child-product.approve');

        return view('pim.child-products.approve', compact('form', 'child_product'));
    }

    public function approveEdit(EditRequest $request, ChildProduct $child_product)
    {
        if ($request->has('approve')) {
            $child_product->status = AttributableModelStatus::PARENTLESS_STATUS;
            $child_product->save();
            flash()->success(trans('child-product.approve.approved'), true);
        } elseif ($request->has('delete')) {
            $this->dispatch(new DeleteChildProduct($child_product));
            flash()->success(trans('child-product.approve.deleted'), true);
        }

        return redirect()->route('child-product.index', AttributableModelStatus::PARENTLESS_STATUS);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param ChildProduct $child_product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, ChildProduct $child_product)
    {
        $old_product = $child_product;

        $this->dispatch(new UpdateChildProduct(
            $child_product,
            $request->all(),
            $request->has('publish')
        ));

        flash()->success(trans('child-product.edit.saved'), true);

        if ($old_product->status == AttributableModelStatus::DRAFT_STATUS) {
            return redirect()->route('child-product.edit', $child_product->id);
        }

        return redirect()->route('child-product.index');
    }

    /**
     * Show the form for editing the specified attribute.
     *
     * @param EditRequest  $request
     * @param ChildProduct $child_product
     * @return \Illuminate\Http\Response
     */
    public function editAttribute(EditRequest $request, ChildProduct $child_product)
    {
        $productNature = $this->childProducts->nature($child_product);

        $attributesList = $this->attributes->allWhereNature($productNature, Attribute::MODEL_TYPE_CHILD_PRODUCT);

        $attributes['choices'] = [];

        $language_code = Auth::user()->getLanguageCode();

        $productAttributesIDs = $child_product->linkAttributes->pluck('attribute_id')->toArray();
        foreach ($attributesList as $attribute) {
            // Ne pas afficher les attributs déjà associés au produit
            $alreadyInProduct = (in_array($attribute->id, $productAttributesIDs));
            if (!$alreadyInProduct) {
                $attributes['choices'][] = [
                    'id'   => $attribute->id,
                    'text' => $attribute->label->values[$language_code],
                ];
            }
        }

        $this->breadcrumb(
            'child-product',
            trans('breadcrumb.child-products.edit-attribute'),
            'child-product.edit-attribute'
        );

        return view('pim.child-products.edit-attribute')->withProduct($child_product)->withAttributes($attributes);
    }

    /**
     * Update the specified attribute in storage.
     *
     * @param  AttributeUpdateRequest $request Child product attribute update request
     * @param  ChildProduct $child_product
     * @return \Illuminate\Http\Response
     */
    public function updateAttribute(AttributeUpdateRequest $request, ChildProduct $child_product)
    {
        if ($request->input('attributesToDelete') != null) {
            $attributesToDelete = explode(',', $request->input('attributesToDelete'));
            LinkAttribute::destroy($attributesToDelete);
        }

        // Save new empty LinkAttributes
        $uniqueNewAttributes = array_unique((array)$request->input('link_attribute'));
        foreach ($uniqueNewAttributes as $newAttributeID) {
            if (!$child_product->hasLinkAttribute($newAttributeID)) {
                $linkAttribute = new LinkAttribute(['attribute_id' => $newAttributeID]);
                $child_product->linkAttributes()->save($linkAttribute);
            }
        }

        flash()->success(trans('child-product.edit-attribute.saved'), true);

        return redirect()->route('child-product.edit', $child_product->id);
    }

    public function childAddInlineAttribute(Request $request, FormBuilder $formBuilder, ChildProduct $child_product)
    {
        return $this->addInlineAttribute(
            $request,
            $formBuilder,
            ChildProductRepository::class,
            $child_product,
            Attribute::MODEL_TYPE_CHILD_PRODUCT
        );
    }

    public function childDeleteInlineAttribute(Request $request, ChildProduct $child_product)
    {
        return $this->deleteInlineAttribute(
            $request,
            ChildProductRepository::class,
            $child_product,
            Attribute::MODEL_TYPE_CHILD_PRODUCT
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DeleteRequest $request Child product delete request
     * @param  ChildProduct $child_product
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteRequest $request, ChildProduct $child_product)
    {
        $this->dispatch(new DeleteChildProduct($child_product));

        flash()->success(trans('child-product.deleted'), true);

        return redirect()->route('child-product.index');
    }

    public function copyAttributes(CopyAttributeRequest $request, ChildProduct $child_product)
    {
        $this->dispatch(app(CopyAttributes::class, [$child_product, $request->get('child_product_id')]));

        flash()->success(trans('child-product.edit.attributes-copied'), true);

        return back();
    }

    protected function getChildrenExcept(ChildProduct $childProduct)
    {
        return $this->childProducts->parentChildrenExcept($childProduct);
    }

    /**
     * Formulaire pour associer l'enfant à un parent.
     *
     * @param EditRequest  $request
     * @param ChildProduct $child_product
     * @param  FormBuilder $formBuilder
     * @return \Illuminate\Http\Response
     */
    public function editParentless(EditRequest $request, ChildProduct $child_product, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(ChildProductParentlessForm::class, [
            'method' => 'POST',
            'url'    => route('child-product.update-parentless', $child_product->id),
        ], [
            'product' => $child_product,
        ]);

        // $this->breadcrumb('child-product', trans('breadcrumb.child-products.edit'), 'child-product.edit');

        return view('pim.child-products.edit-parentless', compact('form', 'child_product'));
    }

    public function updateParentless(UpdateParentRequest $request, ChildProduct $child_product)
    {
        $this->dispatch(new UpdateChildProductParentless($child_product, $request->all()));

        flash()->success(trans('child-product.edit-parent.saved'), true);

        return redirect()->route('child-product.index', AttributableModelStatus::DRAFT_STATUS);
    }

    /**
     * Formulaire pour modifier le parent d'un produit enfant.
     *
     * @param EditRequest  $request
     * @param ChildProduct $child_product
     * @param  FormBuilder $formBuilder
     * @return \Illuminate\Http\Response
     */
    public function editParent(EditRequest $request, ChildProduct $child_product, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(ChildProductEditParentForm::class, [
            'method' => 'POST',
            'url'    => route('child-product.update-parent', $child_product->id),
        ], [
            'product' => $child_product,
        ]);

        // $this->breadcrumb('child-product', trans('breadcrumb.child-products.edit'), 'child-product.edit');

        return view('pim.child-products.edit-parent', compact('form', 'child_product'));
    }

    public function updateParent(UpdateParentRequest $request, ChildProduct $child_product)
    {
        $this->dispatch(new UpdateChildProductParent($child_product, $request->all()));

        flash()->success(trans('child-product.edit-parent.saved'), true);

        return redirect()->route('child-product.index', AttributableModelStatus::DRAFT_STATUS);
    }

    public function validateRequiredAttributes(PublishRequest $request)
    {
        return [];
    }
}
