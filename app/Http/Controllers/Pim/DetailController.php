<?php

namespace Pimeo\Http\Controllers\Pim;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Pimeo\Forms\DetailCreateForm;
use Pimeo\Forms\DetailEditForm;
use Pimeo\Forms\DetailShowForm;
use Pimeo\Http\Controllers\Controller;
use Pimeo\Http\Requests\Pim\Detail\AttributeUpdateRequest;
use Pimeo\Http\Requests\Pim\Detail\CreateRequest;
use Pimeo\Http\Requests\Pim\Detail\DeleteRequest;
use Pimeo\Http\Requests\Pim\Detail\EditRequest;
use Pimeo\Http\Requests\Pim\Detail\StoreRequest;
use Pimeo\Http\Requests\Pim\Detail\UpdateRequest;
use Pimeo\Jobs\Pim\Detail\CreateDetail;
use Pimeo\Jobs\Pim\Detail\DeleteDetail;
use Pimeo\Jobs\Pim\Detail\UpdateDetail;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\Attribute;
use Pimeo\Models\Detail;
use Pimeo\Models\LinkAttribute;
use Pimeo\Repositories\AttributeRepository;
use Pimeo\Repositories\DetailRepository;
use Pimeo\Services\InlineAttributeEditionTrait;
use Pimeo\Services\ModelListing;

class DetailController extends Controller
{
    use FormBuilderTrait, ModelListing, InlineAttributeEditionTrait;

    /**
     * @var AttributeRepository
     */
    protected $attributes;

    /**
     * @var DetailRepository
     */
    protected $details;

    /**
     * @param AttributeRepository     $attributes
     * @param DetailRepository        $details
     */
    public function __construct(AttributeRepository $attributes, DetailRepository $details)
    {
        $this->attributes = $attributes;
        $this->details = $details;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request $request
     * @param  string  $status
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $status = AttributableModelStatus::COMPLETE_STATUS)
    {
        $list = $this->listAll('details', $request, 'detail.index', DetailRepository::class, $status);

        $this->breadcrumb('detail');

        return view('pim.details.index')
            ->with($list)
            ->withActiveStatus($status);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  CreateRequest $request
     * @param  FormBuilder   $formBuilder
     * @return \Illuminate\Http\Response
     */
    public function create(CreateRequest $request, FormBuilder $formBuilder)
    {
        $attributes = $this->attributes->allRequired('detail');

        $form = $formBuilder->create(DetailCreateForm::class, [
            'method' => 'POST',
            'url'    => route('detail.store'),
        ], ['attributes' => $attributes]);

        $this->breadcrumb('detail', trans('breadcrumb.details.create'), 'detail.create');

        return view('pim.details.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest $request Detail create request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $this->dispatch(new CreateDetail($request->all()));

        flash()->success(trans('detail.create.saved'), true);

        return redirect()->route('detail.index');
    }

    public function show(Detail $detail, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(DetailShowForm::class, [
            'readonly' => true,
        ], [
            'attributes' => $detail->linkAttributes,
            'medias'     => $detail->mediaLinks,
            'detail'     => $detail,
        ]);

        $this->breadcrumb('detail', trans('breadcrumb.details.show'), ['detail.show', $detail->id]);

        return view('pim.details.show', compact('form', 'detail'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  EditRequest $request
     * @param  Detail      $detail
     * @param  FormBuilder $formBuilder
     * @return \Illuminate\Http\Response
     */
    public function edit(EditRequest $request, Detail $detail, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(DetailEditForm::class, [
            'method' => 'PUT',
            'url'    => route('detail.update', $detail->id),
        ], [
            'required_attributes' => $this->attributes->allRequired('detail'),
            'attributes'          => $detail->linkAttributes,
            'medias'              => $detail->mediaLinks,
            'detail'              => $detail,
        ]);

        $attributes = $this->attributes->allNotLinkedToProduct(
            $detail,
            null,
            Attribute::MODEL_TYPE_DETAIL
        );

        $this->breadcrumb('detail', trans('breadcrumb.details.edit'), 'detail.edit');

        return view('pim.details.edit', compact('form', 'detail', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request Detail update request
     * @param  Detail        $detail
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Detail $detail)
    {
        $this->dispatch(new UpdateDetail($detail, $request->all()));

        flash()->success(trans('detail.edit.saved'), true);

        return redirect()->route('detail.index');
    }

    /**
     * Show the form for editing the specified attribute.
     *
     * @param  EditRequest $request
     * @param  Detail      $detail
     * @return \Illuminate\Http\Response
     */
    public function editAttribute(EditRequest $request, Detail $detail)
    {
        $attributesList = $this->attributes->all(Auth::user()->getCompany()->id, 'detail');

        $attributes = [];

        $language_code = Auth::user()->getLanguageCode();

        $detailAttributesIDs = $detail->linkAttributes->pluck('attribute_id')->toArray();
        foreach ($attributesList as $attribute) {
            // Ne pas afficher les attributs déjà associés au produit
            $alreadyInDetail = (in_array($attribute->id, $detailAttributesIDs));
            if (!$alreadyInDetail) {
                $attributes['choices'][] = [
                    'id'   => $attribute->id,
                    'text' => $attribute->label->values[$language_code],
                ];
            }
        }

        $this->breadcrumb('detail', trans('breadcrumb.details.edit-attribute'), 'detail.edit-attribute');

        return view('pim.details.edit-attribute')->withDetail($detail)->withAttributes($attributes);
    }

    /**
     * Update the specified attribute in storage.
     *
     * @param  AttributeUpdateRequest $request Detail attribute update request
     * @param  Detail                 $detail
     * @return \Illuminate\Http\Response
     */
    public function updateAttribute(AttributeUpdateRequest $request, Detail $detail)
    {
        if ($request->input('attributesToDelete') != null) {
            $attributesToDelete = explode(',', $request->input('attributesToDelete'));
            LinkAttribute::destroy($attributesToDelete);
        }

        // Save new empty LinkAttributes
        foreach ((array)$request->input('link_attribute') as $newAttributeID) {
            if (!$detail->hasLinkAttribute($newAttributeID)) {
                $linkAttribute = new LinkAttribute(['attribute_id' => $newAttributeID]);
                $detail->linkAttributes()->save($linkAttribute);
            }
        }

        $detail->status = AttributableModelStatus::INCOMPLETE_STATUS;

        flash()->success(trans('detail.edit-attribute.saved'), true);

        return redirect()->route('detail.edit', $detail->id);
    }

    public function specAddInlineAttribute(Request $request, FormBuilder $formBuilder, Detail $detail)
    {
        return $this->addInlineAttribute(
            $request,
            $formBuilder,
            DetailRepository::class,
            $detail,
            Attribute::MODEL_TYPE_DETAIL
        );
    }

    public function specDeleteInlineAttribute(Request $request, Detail $detail)
    {
        return $this->deleteInlineAttribute(
            $request,
            DetailRepository::class,
            $detail,
            Attribute::MODEL_TYPE_DETAIL
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DeleteRequest $request Detail delete request
     * @param  Detail        $detail
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteRequest $request, Detail $detail)
    {
        $this->dispatch(new DeleteDetail($detail));

        flash()->success(trans('detail.deleted'), true);

        return redirect()->route('detail.index');
    }
}
