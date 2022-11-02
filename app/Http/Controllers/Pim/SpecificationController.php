<?php

namespace Pimeo\Http\Controllers\Pim;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Pimeo\Forms\SpecificationCreateForm;
use Pimeo\Forms\SpecificationEditForm;
use Pimeo\Forms\SpecificationShowForm;
use Pimeo\Http\Controllers\Controller;
use Pimeo\Http\Requests\Pim\Specification\AttributeUpdateRequest;
use Pimeo\Http\Requests\Pim\Specification\CreateRequest;
use Pimeo\Http\Requests\Pim\Specification\DeleteRequest;
use Pimeo\Http\Requests\Pim\Specification\EditRequest;
use Pimeo\Http\Requests\Pim\Specification\StoreRequest;
use Pimeo\Http\Requests\Pim\Specification\UpdateRequest;
use Pimeo\Jobs\Pim\Specification\CreateSpecification;
use Pimeo\Jobs\Pim\Specification\DeleteSpecification;
use Pimeo\Jobs\Pim\Specification\UpdateSpecification;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\Attribute;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\Specification;
use Pimeo\Repositories\AttributeRepository;
use Pimeo\Repositories\SpecificationRepository;
use Pimeo\Services\InlineAttributeEditionTrait;
use Pimeo\Services\ModelListing;

class SpecificationController extends Controller
{
    use FormBuilderTrait, ModelListing, InlineAttributeEditionTrait;

    /**
     * @var AttributeRepository
     */
    protected $attributes;

    /**
     * @var SpecificationRepository
     */
    protected $specs;

    /**
     * @param AttributeRepository $attributes
     * @param SpecificationRepository $specs
     */
    public function __construct(AttributeRepository $attributes, SpecificationRepository $specs)
    {
        $this->attributes = $attributes;
        $this->specs = $specs;
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
        $list = $this->listAll('specs', $request, 'specification.index', SpecificationRepository::class, $status);

        $this->breadcrumb('specification');

        return view('pim.specifications.index')
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
        $attributes = $this->attributes->allRequired('specification');

        $form = $formBuilder->create(SpecificationCreateForm::class, [
            'method' => 'POST',
            'url'    => route('specification.store'),
        ], ['attributes' => $attributes]);

        $this->breadcrumb('specification', trans('breadcrumb.specifications.create'), 'specification.create');

        return view('pim.specifications.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest $request Specification create request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $this->dispatch(new CreateSpecification($request->all()));

        flash()->success(trans('specification.create.saved'), true);

        return redirect()->route('specification.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  EditRequest    $request
     * @param  Specification $specification
     * @param  FormBuilder    $formBuilder
     * @return \Illuminate\Http\Response
     */
    public function edit(EditRequest $request, Specification $specification, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(SpecificationEditForm::class, [
            'method' => 'PUT',
            'url'    => route('specification.update', $specification->id),
        ], [
            'required_attributes' => $this->attributes->allRequired('specification'),
            'attributes'          => $specification->linkAttributes,
            'medias'              => $specification->mediaLinks,
            'specification'       => $specification,
        ]);

        $attributes = $this->attributes->allNotLinkedToProduct(
            $specification,
            null,
            Attribute::MODEL_TYPE_SPECIFICATION
        );

        $this->breadcrumb('specification', trans('breadcrumb.specifications.edit'), 'specification.edit');

        return view('pim.specifications.edit', compact('form', 'specification', 'attributes'));
    }

    public function show(Specification $specification, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(SpecificationShowForm::class, [
            'readonly' => true,
        ], [
            'attributes'    => $specification->linkAttributes,
            'medias'        => $specification->mediaLinks,
            'specification' => $specification,
        ]);

        $this->breadcrumb('specification', trans('breadcrumb.specifications.show'), 'specification.show');

        return view('pim.specifications.show', compact('form', 'specification'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request Specification update request
     * @param  Specification $specification
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Specification $specification)
    {
        $this->dispatch(new UpdateSpecification($specification, $request->all()));

        flash()->success(trans('specification.edit.saved'), true);

        return redirect()->route('specification.index');
    }

    /**
     * Show the form for editing the specified attribute.
     *
     * @param  EditRequest   $request
     * @param  Specification $specification
     * @return \Illuminate\Http\Response
     */
    public function editAttribute(EditRequest $request, Specification $specification)
    {
        $attributesList = $this->attributes->all(Auth::user()->getCompany()->id, 'specification');
        $attributes = [];
        $specificationAttributesIDs = $specification->linkAttributes->pluck('attribute_id')->toArray();
        $language_code = Auth::user()->getLanguageCode();

        foreach ($attributesList as $attribute) {
            // Ne pas afficher les attributs déjà associés au produit
            $alreadyInSpecification = (in_array($attribute->id, $specificationAttributesIDs));
            if (!$alreadyInSpecification) {
                $attributes['choices'][] = [
                    'id'   => $attribute->id,
                    'text' => $attribute->label->values[$language_code],
                ];
            }
        }

        $this->breadcrumb(
            'specification',
            trans('breadcrumb.specifications.edit-attribute'),
            'specification.edit-attribute'
        );

        return view('pim.specifications.edit-attribute')
            ->withSpecification($specification)
            ->withAttributes($attributes);
    }

    /**
     * Update the specified attribute in storage.
     *
     * @param  AttributeUpdateRequest $request Specification attribute update request
     * @param  Specification          $specification
     * @return \Illuminate\Http\Response
     */
    public function updateAttribute(AttributeUpdateRequest $request, Specification $specification)
    {
        if ($request->input('attributesToDelete') != null) {
            $attributesToDelete = explode(',', $request->input('attributesToDelete'));
            LinkAttribute::destroy($attributesToDelete);
        }

        // Save new empty LinkAttributes
        $uniqueNewAttributes = array_unique((array)$request->input('link_attribute'));
        foreach ($uniqueNewAttributes as $newAttributeID) {
            if (!$specification->hasLinkAttribute($newAttributeID)) {
                $linkAttribute = new LinkAttribute(['attribute_id' => $newAttributeID]);
                $specification->linkAttributes()->save($linkAttribute);
            }
        }

        $specification->status = AttributableModelStatus::INCOMPLETE_STATUS;

        flash()->success(trans('specification.edit-attribute.saved'), true);

        return redirect()->route('specification.edit', $specification->id);
    }

    public function specAddInlineAttribute(Request $request, FormBuilder $formBuilder, Specification $specification)
    {
        return $this->addInlineAttribute(
            $request,
            $formBuilder,
            SpecificationRepository::class,
            $specification,
            Attribute::MODEL_TYPE_SPECIFICATION
        );
    }

    public function specDeleteInlineAttribute(Request $request, Specification $specification)
    {
        return $this->deleteInlineAttribute(
            $request,
            SpecificationRepository::class,
            $specification,
            Attribute::MODEL_TYPE_SPECIFICATION
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DeleteRequest $request Specification delete request
     * @param  Specification $specification
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteRequest $request, Specification $specification)
    {
        $this->dispatch(new DeleteSpecification($specification));

        flash()->success(trans('specification.deleted'), true);

        return redirect()->route('specification.index');
    }
}
