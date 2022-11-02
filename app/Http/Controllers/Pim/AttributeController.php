<?php

namespace Pimeo\Http\Controllers\Pim;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Pimeo\Http\Controllers\Controller;
use Pimeo\Http\Requests\Pim\Attribute\CreateRequest;
use Pimeo\Http\Requests\Pim\Attribute\DeleteRequest;
use Pimeo\Http\Requests\Pim\Attribute\EditRequest;
use Pimeo\Http\Requests\Pim\Attribute\StoreRequest;
use Pimeo\Http\Requests\Pim\Attribute\UpdateRequest;
use Pimeo\Jobs\Pim\Attribute\CreateAttribute;
use Pimeo\Jobs\Pim\Attribute\DeleteAttribute;
use Pimeo\Jobs\Pim\Attribute\UpdateAttribute;
use Pimeo\Models\Attribute;
use Pimeo\Models\AttributeType;
use Pimeo\Models\AttributeValue;
use Pimeo\Models\BuildingComponent;
use Pimeo\Models\Company;
use Pimeo\Models\Language;
use Pimeo\Models\User;
use Pimeo\Repositories\AttributeRepository;
use Pimeo\Repositories\AttributeTypeRepository;
use Pimeo\Repositories\CompanyRepository;
use Pimeo\Repositories\LanguageRepository;
use Pimeo\Repositories\NatureRepository;

class AttributeController extends Controller
{
    /**
     * @var AttributeRepository
     */
    protected $attributes;

    /**
     * @var NatureRepository
     */
    protected $natures;

    /**
     * @var CompanyRepository
     */
    protected $companies;

    /**
     * @var LanguageRepository
     */
    protected $languages;

    /**
     * @var AttributeTypeRepository
     */
    protected $types;

    /**
     * @var Guard
     */
    protected $auth;

    /**
     * @param AttributeRepository     $attributes
     * @param NatureRepository        $natures
     * @param CompanyRepository       $companies
     * @param LanguageRepository      $languages
     * @param AttributeTypeRepository $types
     * @param Guard                   $auth
     */
    public function __construct(
        AttributeRepository $attributes,
        NatureRepository $natures,
        CompanyRepository $companies,
        LanguageRepository $languages,
        AttributeTypeRepository $types,
        Guard $auth
    ) {
        $this->attributes = $attributes;
        $this->natures = $natures;
        $this->companies = $companies;
        $this->languages = $languages;
        $this->types = $types;
        $this->auth = $auth;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  string $model
     * @return \Illuminate\Http\Response
     */
    public function index($model)
    {
        $this->breadcrumb($model);

        $attributes = $this->attributes->all($model);

        return view('pim.attributes.index', compact('attributes', 'model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateRequest $request
     * @param  string       $model
     * @param  string       $system
     * @return \Illuminate\Http\Response
     */
    public function create(CreateRequest $request, $model, $system = null)
    {
        $this->breadcrumbCreate($model, $system);

        $natures = $this->natures->all();
        $company = current_company();
        $buildingComponents = BuildingComponent::forCurrentCompany()->get();
        $languages = $company->languages;
        $types = $this->types->getAllPublic(['code', 'specs']);
        $typesSelect = $this->normalizeSelectTypes($types);
        $typeViews = $this->normalizeTypeViews($types);
        $values = old('choice');
        $values = $this->normalizeValues($values, $languages);

        $currentMaxKey = count(array_first($values));

        return view(
            'pim.attributes.create',
            compact(
                'natures',
                'languages',
                'company',
                'typeViews',
                'model',
                'system',
                'typesSelect',
                'values',
                'currentMaxKey',
                'buildingComponents'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $this->dispatch(app(CreateAttribute::class, [$request->all(), $request->user()]));

        flash()->success(trans('attribute.create.saved'), true);

        return redirect()->route('attribute.index', $request->model_type);
    }

    /**
     * Display the specified resource.
     *
     * @param  Attribute $attribute
     * @return \Illuminate\Http\Response
     */
    public function show(Attribute $attribute)
    {
        $languages = $this->languages->all();
        $natures = $this->natures->all();
        $buildingComponents = BuildingComponent::forCurrentCompany()->get();

        $this->breadcrumb(
            $attribute->model_type,
            trans('breadcrumb.attributes.show', ['attribute' => $attribute->label->present()->value]),
            ['attribute.show', $attribute]
        );

        return view('pim.attributes.show', compact('attribute', 'languages', 'natures', 'buildingComponents'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param EditRequest $request
     * @param  Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function edit(EditRequest $request, Attribute $attribute)
    {
        $attribute->load(['label', 'type', 'natures']);
        $natures = $this->natures->all();
        $company = current_company();
        $buildingComponents = BuildingComponent::forCurrentCompany()->get();
        $languages = $company->languages;
        $types = $this->types->getAllPublic(['code', 'specs']);
        $typesSelect = $this->normalizeSelectTypes($types);
        $typeViews = $this->normalizeTypeViews($types);
        $values = $attribute->value ? old('choice', $attribute->value->values) : old('choice', []);
        $values = $this->normalizeValues($values, $languages);

        $willAffect = $this->countLinkValues($attribute);

        $currentMaxKey = 0;

        if ($attribute->value && isset(array_last(array_first($values))['key'])) {
            $currentMaxKey = array_last(array_first($values));
        }

        $this->breadcrumbEdit($attribute);

        return view(
            'pim.attributes.edit',
            compact(
                'attribute',
                'natures',
                'company',
                'types',
                'languages',
                'typesSelect',
                'typeViews',
                'values',
                'willAffect',
                'currentMaxKey',
                'buildingComponents'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request
     * @param  Attribute     $attribute
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Attribute $attribute)
    {
        $this->dispatch(app(UpdateAttribute::class, [$attribute, $request->all()]));

        flash()->success(trans('attribute.edit.saved'), true);

        return redirect()->route('attribute.index', $attribute->model_type);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DeleteRequest $request
     * @param  Attribute     $attribute
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteRequest $request, Attribute $attribute)
    {
        $this->dispatch(new DeleteAttribute($attribute));

        flash()->success(trans('attribute.deleted', ['name' => $attribute->label->present()->value]));

        return redirect()->route('attribute.index', $attribute->model_type);
    }

    protected function breadcrumb($model, $title = null, $link = null)
    {
        breadcrumb()->add(trans('breadcrumb.attributes.' . $model), ['attribute.index', $model]);

        if ($title) {
            breadcrumb()->add($title, $link);
        }
    }

    protected function breadcrumbCreate($model, $system)
    {
        $this->breadcrumb(
            $model,
            trans('breadcrumb.attributes-create.' . $model),
            ['attribute.create',
            [$model, $system], ]
        );
    }

    /**
     * @param Attribute $attribute
     * @throws \Laracasts\Presenter\Exceptions\PresenterException
     */
    protected function breadcrumbEdit(Attribute $attribute)
    {
        $this->breadcrumb(
            $attribute->model_type,
            trans('breadcrumb.attributes.edit', ['attribute' => $attribute->label->present()->value]),
            ['attribute.edit', $attribute]
        );
    }

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|User
     */
    protected function getUser()
    {
        return $this->auth->user();
    }

    /**
     * @param  Collection|AttributeType[] $types
     * @return Collection
     */
    protected function normalizeSelectTypes($types)
    {
        return $types->map(function ($type) {
            return [
                'id'   => $type->code,
                'type' => $type->specs['type'],
                'text' => trans('attribute_types.' . $type->code),
            ];
        });
    }

    /**
     * @param  Collection|AttributeType[] $types
     * @return Collection
     */
    protected function normalizeTypeViews($types)
    {
        return $types->unique(function ($type) {
            return $type->specs['type'];
        })->map(function ($type) {
            return ['view' => $type->specs['type'], 'code' => $type->code];
        });
    }

    /**
     * @param  AttributeValue        $attributeValue
     * @param  Collection|Language[] $languages
     * @return array
     */
    protected function normalizeValues($attributeValue, $languages)
    {
        $values = [];

        if (!$attributeValue) {
            foreach ($languages as $language) {
                $values[$language->code][] = '';
            }

            return $values;
        }

        $attributeValue = $this->createMissingLanguageAttributeValue($attributeValue, $languages);

        foreach ($languages as $language) {
            foreach ($attributeValue[$language->code] as $idValue => $value) {
                $values[$language->code][$idValue] = $value;
            }
        }

        return $values;
    }

    protected function countLinkValues(Attribute $attribute)
    {
        return $attribute->attributeLinks()->count();
    }

    /**
     * @param  AttributeValue| mixed[] $attributeValue
     * @param  Collection|Language[] $languages
     * @return array
     */
    private function createMissingLanguageAttributeValue($attributeValue, Collection $languages)
    {
        $values = array_fill(0, count(array_first($attributeValue)), '');
        foreach ($languages as $language) {
            if (!isset($attributeValue[$language->code])) {
                $attributeValue[$language->code] = $values;
            }
        }

        return $attributeValue;
    }
}
