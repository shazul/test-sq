<?php

namespace Pimeo\Http\Controllers\Pim;

use Kris\LaravelFormBuilder\FormBuilder;
use Pimeo\Forms\SystemLayerCreateForm;
use Pimeo\Http\Controllers\Controller;
use Pimeo\Http\Requests\Pim\System\Layer\CreateRequest;
use Pimeo\Http\Requests\Pim\System\Layer\DeleteRequest;
use Pimeo\Http\Requests\Pim\System\Layer\EditRequest;
use Pimeo\Http\Requests\Pim\System\Layer\StoreRequest;
use Pimeo\Http\Requests\Pim\System\Layer\UpdateRequest;
use Pimeo\Jobs\Pim\System\CreateSystemLayer;
use Pimeo\Jobs\Pim\System\UpdateSystem;
use Pimeo\Jobs\Pim\System\UpdateSystemLayer;
use Pimeo\Models\Layer;
use Pimeo\Models\LayerGroup;

class SystemLayerController extends Controller
{
    public function create(CreateRequest $request, LayerGroup $layer_group, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(
            SystemLayerCreateForm::class,
            [
                'method' => 'POST',
                'url'    => route('system.store-layer', $layer_group->id),
            ],
            []
        );

        $system = $layer_group->system;

        return view('pim.systems.add-layer', compact('form', 'system'));
    }

    public function edit(EditRequest $request, Layer $layer, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(
            SystemLayerCreateForm::class,
            [
                'method' => 'PUT',
                'url'    => route('system.update-layer', $layer->id),
            ],
            ['layer' => $layer]
        );

        $system = $layer->layerGroup->system;

        return view('pim.systems.add-layer', compact('form', 'system'));
    }

    public function update(UpdateRequest $request, Layer $layer)
    {
        $this->dispatch(new UpdateSystemLayer($request->all(), $layer));

        flash()->success(trans('system.layer.edit.saved'), true);

        return redirect()->route('system.add-layer-groups', [$layer->layerGroup->system]);
    }

    public function store(StoreRequest $request, LayerGroup $layer_group)
    {
        $this->dispatch(new CreateSystemLayer($request->all(), $layer_group));

        flash()->success(trans('system.layers.saved'), true);

        return redirect()->route('system.add-layer-groups', [$layer_group->system]);
    }

    public function destroy(DeleteRequest $request, Layer $layer)
    {
        $layer->delete();

        $updateSystem = app(UpdateSystem::class, [$layer->layerGroup->system, []]);
        $updateSystem->updateStatus($layer->layerGroup->system);

        flash()->success(trans('system.layers.deleted'), true);

        return redirect()->route('system.add-layer-groups', [$layer->layerGroup->system]);
    }
}
