<?php

namespace Pimeo\Http\Controllers\Pim;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Pimeo\Forms\ParentProductCreateForm;
use Pimeo\Forms\ParentProductEditForm;
use Pimeo\Forms\ParentProductEditNatureForm;
use Pimeo\Forms\ParentProductShowForm;
use Pimeo\Http\Controllers\Controller;
use Pimeo\Http\Requests\Pim\ParentProduct\AttributeUpdateRequest;
use Pimeo\Http\Requests\Pim\ParentProduct\CreateRequest;
use Pimeo\Http\Requests\Pim\ParentProduct\DeleteRequest;
use Pimeo\Http\Requests\Pim\ParentProduct\EditRequest;
use Pimeo\Http\Requests\Pim\ParentProduct\PublishRequest;
use Pimeo\Http\Requests\Pim\ParentProduct\StoreRequest;
use Pimeo\Http\Requests\Pim\ParentProduct\UpdateRequest;
use Pimeo\Jobs\Pim\ParentProduct\CreateParentProduct;
use Pimeo\Jobs\Pim\ParentProduct\DeleteParentProduct;
use Pimeo\Jobs\Pim\ParentProduct\UpdateParentProduct;
use Pimeo\Jobs\Pim\ParentProduct\UpdateParentProductNature;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\Attribute;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\ParentProduct;
use Pimeo\Repositories\AttributeRepository;
use Pimeo\Repositories\ParentProductRepository;
use Pimeo\Services\InlineAttributeEditionTrait;
use Pimeo\Services\ModelListing;

class ParentProductController extends Controller
{
    use FormBuilderTrait, ModelListing, InlineAttributeEditionTrait;

    /**
     * @var AttributeRepository
     */
    protected $attributes;

    /**
     * @var ParentProductRepository
     */
    protected $parents;

    /**
     * @param AttributeRepository     $attributes
     * @param ParentProductRepository $parents
     */
    public function __construct(AttributeRepository $attributes, ParentProductRepository $parents)
    {
        $this->attributes = $attributes;
        $this->parents = $parents;
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
        $list = $this->listAll('products', $request, 'parent-product.index', ParentProductRepository::class, $status);

        $this->breadcrumb('parent-product');

        return view('pim.parent-products.index')
            ->with($list)
            ->withLangCode(Auth::user()->getLanguageCode())
            ->withActiveStatus($status)
        ;
    }

    private function getStatusList()
    {
        return [
            AttributableModelStatus::PUBLISHED_STATUS => [],
            AttributableModelStatus::DRAFT_STATUS     => [],
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
        $attributes = $this->attributes->allRequired('parent_product');

        $form = $formBuilder->create(ParentProductCreateForm::class, [
            'method' => 'POST',
            'url'    => route('parent-product.store'),
            'id'     => 'form_create_parent_product',
        ], ['attributes' => $attributes]);

        $this->breadcrumb('parent-product', trans('breadcrumb.parent-products.create'), 'parent-product.create');

        return view('pim.parent-products.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest $request Parent product create request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $this->dispatch(new CreateParentProduct($request->all(), $request->has('publish')));

        flash()->success(trans('parent-product.create.saved'), true);

        return redirect()->route('parent-product.index');
    }

    public function show(ParentProduct $parent_product, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(ParentProductShowForm::class, [
            'readonly' => true,
        ], [
            'attributes' => $parent_product->linkAttributes,
            'medias'     => $parent_product->mediaLinks,
            'product'    => $parent_product,
        ]);

        $this->breadcrumb('parent-product', trans('breadcrumb.parent-products.show'), 'parent-product.show');

        return view('pim.parent-products.show', compact('form', 'parent_product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param EditRequest    $request
     * @param  ParentProduct $parent_product
     * @param  FormBuilder   $formBuilder
     * @return \Illuminate\Http\Response
     */

    public function edit(EditRequest $request, ParentProduct $parent_product, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(ParentProductEditForm::class, [
            'method' => 'PUT',
            'url'    => route('parent-product.update', $parent_product->id),
            'id'     => 'form_edit_parent_product',
        ], [
            'required_attributes' => $this->attributes->allRequired('parent_product'),
            'attributes'          => $parent_product->linkAttributes,
            'medias'              => $parent_product->mediaLinks,
            'product'             => $parent_product,
        ]);

        $nature = $parent_product->nature_id;
        $attributes = $this->attributes->allNotLinkedToProduct(
            $parent_product,
            $nature,
            Attribute::MODEL_TYPE_PARENT_PRODUCT
        );

        $this->breadcrumb('parent-product', trans('breadcrumb.parent-products.edit'), 'parent-product.edit');

        return view('pim.parent-products.edit', compact('form', 'parent_product', 'nature', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request Parent product update request
     * @param  ParentProduct $parent_product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, ParentProduct $parent_product)
    {
        $old_product = $parent_product;

        $this->dispatch(new UpdateParentProduct(
            $parent_product,
            $request->all(),
            $request->has('publish')
        ));

        flash()->success(trans('parent-product.edit.saved'), true);

        if ($old_product->status == AttributableModelStatus::DRAFT_STATUS) {
            return redirect()->route('parent-product.edit', $parent_product->id);
        }

        return redirect()->route('parent-product.index');
    }

    /**
     * Show the form for editing the specified attribute.
     *
     * @param  ParentProduct $parent_product
     * @return \Illuminate\Http\Response
     */
    public function editAttribute(ParentProduct $parent_product)
    {
        $productNature = $this->parents->nature($parent_product);

        $attributes = $this->attributes->allNotLinkedToProduct(
            $parent_product,
            $productNature,
            Attribute::MODEL_TYPE_PARENT_PRODUCT
        );

        $attributeChoices['choices'] = $attributes;

        $this->breadcrumb(
            'parent-product',
            trans('breadcrumb.parent-products.edit-attribute'),
            'parent-product.edit-attribute'
        );

        return view('pim.parent-products.edit-attribute')
            ->withProduct($parent_product)
            ->withAttributes($attributeChoices);
    }

    /**
     * Update the specified attribute in storage.
     *
     * @param  AttributeUpdateRequest $request Parent product attribute update request
     * @param  ParentProduct $parent_product
     * @return \Illuminate\Http\Response
     */
    public function updateAttribute(AttributeUpdateRequest $request, ParentProduct $parent_product)
    {
        if ($request->input('attributesToDelete') != null) {
            $attributesToDelete = explode(',', $request->input('attributesToDelete'));
            LinkAttribute::destroy($attributesToDelete);
        }

        // Save new empty LinkAttributes
        $uniqueNewAttributes = array_unique((array)$request->input('link_attribute'));
        foreach ($uniqueNewAttributes as $newAttributeID) {
            if (!$parent_product->hasLinkAttribute($newAttributeID)) {
                $linkAttribute = new LinkAttribute(['attribute_id' => $newAttributeID]);
                $parent_product->linkAttributes()->save($linkAttribute);
            }
        }

        flash()->success(trans('parent-product.edit-attribute.saved'), true);

        return redirect()->route('parent-product.edit', $parent_product->id);
    }

    public function parentAddInlineAttribute(Request $request, FormBuilder $formBuilder, ParentProduct $parent_product)
    {
        return $this->addInlineAttribute(
            $request,
            $formBuilder,
            ParentProductRepository::class,
            $parent_product,
            Attribute::MODEL_TYPE_PARENT_PRODUCT
        );
    }

    public function parentDeleteInlineAttribute(Request $request, ParentProduct $parent_product)
    {
        return $this->deleteInlineAttribute(
            $request,
            ParentProductRepository::class,
            $parent_product,
            Attribute::MODEL_TYPE_PARENT_PRODUCT
        );
    }

    /**
     * Show the form for editing the specified attribute.
     *
     * @param  ParentProduct $parent_product
     * @return \Illuminate\Http\Response
     */
    public function editNature(ParentProduct $parent_product, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(ParentProductEditNatureForm::class, [
            'method' => 'PUT',
            'url'    => route('parent-product.update-nature', $parent_product->id),
        ], [
            'product'          => $parent_product,
        ]);

        $this->breadcrumb(
            'parent-product',
            trans('breadcrumb.parent-products.edit-nature'),
            'parent-product.edit-nature'
        );

        return view('pim.parent-products.edit-nature')
            ->withProduct($parent_product)
            ->withForm($form)
        ;
    }

    /**
     * @param  Request $request
     * @param  ParentProduct $parent_product
     * @return \Illuminate\Http\Response
     */
    public function updateNature(Request $request, ParentProduct $parent_product)
    {
        $this->dispatch(new UpdateParentProductNature($parent_product, $request->input('nature_id')));

        flash()->success(trans('parent-product.nature.saved'), true);

        return redirect()->route('parent-product.edit', $parent_product->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DeleteRequest $request Parent product delete request
     * @param  ParentProduct $parent_product
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteRequest $request, ParentProduct $parent_product)
    {
        $this->dispatch(new DeleteParentProduct($parent_product));

        flash()->success(trans('parent-product.deleted'), true);

        return redirect()->route('parent-product.index');
    }

    /**
     * Upload an image ...
     *
     * @param Request $request
     * @return array
     */
    public function uploadImage(Request $request)
    {
        $file = $request->file('file');

        $dateCreation = date('Y-m-d');

        $originalName = $file->getClientOriginalName();

        $newFileName = $originalName . uniqid() . md5($originalName);
        $fileExtension = $file->getClientOriginalExtension();
        $newFilePath = $dateCreation . '/' . $newFileName . '.' . $fileExtension;

        Storage::put(
            $newFilePath,
            file_get_contents($file->getRealPath())
        );

        return [
            'full_name' => $dateCreation . '/' . $newFileName . '.' . $fileExtension,
        ];
    }

    public function validateRequiredAttributes(PublishRequest $request)
    {
        return [];
    }
}
