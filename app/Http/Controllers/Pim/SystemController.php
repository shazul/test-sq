<?php

namespace Pimeo\Http\Controllers\Pim;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kris\LaravelFormBuilder\FormBuilder;
use Pimeo\Forms\SystemCreateForm;
use Pimeo\Forms\SystemEditForm;
use Pimeo\Forms\SystemShowForm;
use Pimeo\Http\Controllers\Controller;
use Pimeo\Http\Requests\Pim\System\AttributeUpdateRequest;
use Pimeo\Http\Requests\Pim\System\CreateRequest;
use Pimeo\Http\Requests\Pim\System\DeleteRequest;
use Pimeo\Http\Requests\Pim\System\EditRequest;
use Pimeo\Http\Requests\Pim\System\StoreRequest;
use Pimeo\Http\Requests\Pim\System\UpdateRequest;
use Pimeo\Jobs\Pim\System\CreateSystem;
use Pimeo\Jobs\Pim\System\DeleteSystem;
use Pimeo\Jobs\Pim\System\UpdateSystem;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\Attribute;
use Pimeo\Models\LayerGroup;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\System;
use Pimeo\Repositories\AttributeRepository;
use Pimeo\Repositories\SystemRepository;
use Pimeo\Services\InlineAttributeEditionTrait;
use Pimeo\Services\ModelListing;

class SystemController extends Controller
{
    use ModelListing, InlineAttributeEditionTrait;

    /**
     * @var AttributeRepository
     */
    protected $attributes;

    /**
     * @var SystemRepository
     */
    protected $systems;

    /**
     * @param AttributeRepository $attributes
     * @param SystemRepository    $systems
     */
    public function __construct(AttributeRepository $attributes, SystemRepository $systems)
    {
        $this->attributes = $attributes;
        $this->systems = $systems;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request    $request
     * @param string     $status
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $status = AttributableModelStatus::COMPLETE_STATUS)
    {
        $list = $this->listAll(
            'systems',
            $request,
            'system.index',
            SystemRepository::class,
            $status
        );

        $this->breadcrumb(
            'system',
            trans('breadcrumb.systems.listing'),
            ['system.index']
        );

        return view('pim.systems.index')
            ->with($list)
            ->withActiveStatus($status);
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
        $attributes = $this->attributes->allRequired(Attribute::MODEL_TYPE_SYSTEM);

        $form = $formBuilder->create(SystemCreateForm::class, [
            'method' => 'POST',
            'url'    => route('system.store'),
        ], ['attributes' => $attributes]);

        $this->breadcrumb(
            'system',
            trans('breadcrumb.systems.create'),
            ['system.create']
        );

        return view('pim.systems.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest $request System create request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $this->dispatch(new CreateSystem($request->all()));

        flash()->success(trans('system.create.saved'), true);

        return redirect()->route('system.index');
    }

    public function show(System $system, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(SystemShowForm::class, [
            'readonly' => true,
        ], [
            'attributes'             => $system->linkAttributes,
            'medias'                 => $system->mediaLinks,
            'system'                 => $system,
            'building_component_ids' => $system->building_component_ids
        ]);

        $layerGroups = LayerGroup::whereSystemId($system->id)->with([
            'layers' => function ($query) {
                return $query->orderBy('position');
            },
        ])->orderBy('position')->get();

        $this->breadcrumb(
            'system',
            trans('breadcrumb.systems.show'),
            ['system.show', [$system->id]]
        );

        return view('pim.systems.show', compact('form', 'system', 'layerGroups'));
    }

    /**
     * Show the form for editing the specified system.
     *
     * @param  EditRequest $request
     * @param  System      $system
     * @param  FormBuilder $formBuilder
     * @return \Illuminate\Http\Response
     */
    public function edit(EditRequest $request, System $system, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(SystemEditForm::class, [
            'method' => 'PUT',
            'url'    => route('system.update', $system->id),
        ], [
            'required_attributes'    => $this->attributes->allRequired(Attribute::MODEL_TYPE_SYSTEM),
            'attributes'             => $system->linkAttributes,
            'medias'                 => $system->mediaLinks,
            'system'                 => $system,
            'building_component_ids' => $system->building_component_ids
        ]);

        $layerGroups = LayerGroup::whereSystemId($system->id)->with([
            'layers' => function ($query) {
                return $query->orderBy('position');
            },
        ])->orderBy('position')->get();

        $attributes = $this->attributes->allNotLinkedToSystem($system);

        $this->breadcrumb(
            'system',
            trans('breadcrumb.systems.edit'),
            ['system.edit', [$system->id]]
        );

        return view('pim.systems.edit', compact('form', 'system', 'layerGroups', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request System update request
     * @param  System        $system
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, System $system)
    {
        $this->dispatch(new UpdateSystem($system, $request->all()));

        flash()->success(trans('system.edit.saved'), true);

        return redirect()->route('system.index');
    }

    /**
     * Update the specified attribute in storage.
     *
     * @param  AttributeUpdateRequest $request System attribute update request
     * @param  System                 $system
     * @return \Illuminate\Http\Response
     */
    public function updateAttribute(AttributeUpdateRequest $request, System $system)
    {
        if ($request->input('attributesToDelete') != null) {
            $attributesToDelete = explode(',', $request->input('attributesToDelete'));
            LinkAttribute::destroy($attributesToDelete);
        }

        // Save new empty LinkAttributes
        $uniqueNewAttributes = array_unique((array)$request->input('link_attribute'));
        foreach ($uniqueNewAttributes as $newAttributeID) {
            if (!$system->hasLinkAttribute($newAttributeID)) {
                $linkAttribute = new LinkAttribute(['attribute_id' => $newAttributeID]);
                $system->linkAttributes()->save($linkAttribute);
            }
        }

        $system->status = AttributableModelStatus::INCOMPLETE_STATUS;

        flash()->success(trans('system.edit-attribute.saved'), true);

        return redirect()->route('system.edit', [$system->id]);
    }

    public function systemAddInlineAttribute(Request $request, FormBuilder $formBuilder, System $system)
    {
        return $this->addInlineAttribute(
            $request,
            $formBuilder,
            SystemRepository::class,
            $system,
            Attribute::MODEL_TYPE_SYSTEM
        );
    }

    public function systemDeleteInlineAttribute(Request $request, System $system)
    {
        return $this->deleteInlineAttribute(
            $request,
            SystemRepository::class,
            $system,
            Attribute::MODEL_TYPE_SYSTEM
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DeleteRequest $request System delete request
     * @param  System        $system
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteRequest $request, System $system)
    {
        $this->dispatch(new DeleteSystem($system));

        flash()->success(trans('system.deleted'), true);

        return redirect()->route('system.index');
    }
}
