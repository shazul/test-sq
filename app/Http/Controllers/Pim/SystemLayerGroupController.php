<?php

namespace Pimeo\Http\Controllers\Pim;

use Kris\LaravelFormBuilder\FormBuilder;
use Pimeo\Forms\EmptyForm;
use Pimeo\Forms\SystemLayerGroupsCreateForm;
use Pimeo\Forms\SystemLayerGroupsEditForm;
use Pimeo\Http\Controllers\Controller;
use Pimeo\Http\Requests\Pim\System\LayerGroup\CreateRequest;
use Pimeo\Http\Requests\Pim\System\LayerGroup\DeleteRequest;
use Pimeo\Http\Requests\Pim\System\LayerGroup\EditRequest;
use Pimeo\Http\Requests\Pim\System\LayerGroup\StoreRequest;
use Pimeo\Http\Requests\Pim\System\LayerGroup\UpdateRequest;
use Pimeo\Jobs\Pim\System\CreateSystemLayerGroup;
use Pimeo\Jobs\Pim\System\UpdateSystem;
use Pimeo\Models\LayerGroup;
use Pimeo\Models\System;

class SystemLayerGroupController extends Controller
{
    /**
     * @param CreateRequest $request
     * @param System      $system
     * @param FormBuilder $formBuilder
     * @return mixed
     */
    public function create(
        CreateRequest $request,
        System $system,
        FormBuilder $formBuilder
    ) {
        /** @var LayerGroup[] $layerGroups */
        $layerGroups = LayerGroup::whereSystemId($system->id)->with(
            [
                'layers' => function ($query) {
                    return $query->orderBy('position');
                },
            ]
        )->orderBy('position')->get();

        $form = $formBuilder->create(EmptyForm::class);

        if (count($layerGroups) < 2) {
             $form = $formBuilder->create(
                 SystemLayerGroupsCreateForm::class,
                 [
                     'method' => 'POST',
                     'url'    => route('system.store-layer-groups', $system->id),
                 ]
             );
        }

        $this->breadcrumb(
            'system',
            trans('breadcrumb.systems.add-layer-groups'),
            ['system.add-layer-groups']
        );

        return view('pim.systems.add-layer-groups', compact('form', 'system', 'layerGroups'));
    }

    public function edit(EditRequest $request, LayerGroup $layer_group, FormBuilder $formBuilder)
    {
        $system = $layer_group->system;

        $layerGroups = LayerGroup::whereSystemId($system->id)->with(
            [
                'layers' => function ($query) {
                    return $query->orderBy('position');
                },
            ]
        )->where('id', '!=', $layer_group->id)->orderBy('position')->get();

        $form = $formBuilder->create(
            SystemLayerGroupsEditForm::class,
            [
                'method' => 'PUT',
                'url'    => route('system.update-layer-group', $layer_group->id),
            ],
            ['layer_group' => $layer_group]
        );

        $this->breadcrumb(
            'system',
            trans('breadcrumb.systems.add-layer-groups'),
            ['system.edit-layer-group', [$layer_group->id]]
        );

        return view('pim.systems.add-layer-groups', compact('form', 'system', 'layerGroups'));
    }

    public function store(StoreRequest $request, System $system)
    {
        $this->dispatch(new CreateSystemLayerGroup($request->all(), $system));

        flash()->success(trans('system.layer-groups.saved'), true);

        return redirect()->route('system.add-layer-groups', [$system]);
    }

    public function update(UpdateRequest $request, LayerGroup $layer_group)
    {
        $layer_group->name = $request->input('name');
        $layer_group->position = $request->input('position');
        $layer_group->save();

        $system = $layer_group->system;

        flash()->success(trans('system.layer-groups.saved'), true);

        return redirect()->route('system.add-layer-groups', [$system]);
    }

    public function destroy(DeleteRequest $request, LayerGroup $layer_group)
    {
        $layer_group->delete();

        $updateSystem = app(UpdateSystem::class, [$layer_group->system, []]);
        $updateSystem->updateStatus($layer_group->system);

        flash()->success(trans('system.layer-groups.deleted'), true);

        return redirect()->route(
            'system.add-layer-groups',
            [$layer_group->system->id]
        );
    }
}
