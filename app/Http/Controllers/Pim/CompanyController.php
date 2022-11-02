<?php

namespace Pimeo\Http\Controllers\Pim;

use Kris\LaravelFormBuilder\FormBuilder;
use Pimeo\Forms\CompanyCreateForm;
use Pimeo\Forms\CompanyEditForm;
use Pimeo\Http\Controllers\Controller;
use Pimeo\Http\Requests\Pim\Company\CreateRequest;
use Pimeo\Http\Requests\Pim\Company\DeleteRequest;
use Pimeo\Http\Requests\Pim\Company\EditRequest;
use Pimeo\Http\Requests\Pim\Company\ManageRequest;
use Pimeo\Http\Requests\Pim\Company\UpdateRequest;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\Company;
use Pimeo\Models\CompanyModel;
use Pimeo\Models\Language;
use Pimeo\Models\ParentProduct;
use Pimeo\Models\User;
use Pimeo\Repositories\CompanyModelRepository;
use Pimeo\Repositories\CompanyRepository;

class CompanyController extends Controller
{
    /**
     * @var CompanyRepository
     */
    protected $companies;

    /**
     * @param CompanyRepository $companies
     */
    public function __construct(CompanyRepository $companies)
    {
        $this->companies = $companies;
    }

    /**
     * Display a listing of the resource.
     *
     * @param ManageRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ManageRequest $request)
    {
        $userCompanies = auth()->user()->companies;

        $this->breadcrumb('company');

        return view('pim.companies.index', compact('userCompanies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ManageRequest $request
     * @param FormBuilder $formBuilder
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ManageRequest $request, FormBuilder $formBuilder)
    {
        $this->breadcrumb('company', trans('breadcrumb.companies.create'), 'company.create');

        $languages = Language::all();
        $models = $this->getCompanyModels();

        $form = $formBuilder->create(CompanyCreateForm::class, [
            'method' => 'POST',
            'url' => route('company.store'),
        ], ['languages' => $languages, 'models' => $models]);

        return view('pim.companies.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $company = $request->all();

        $company['default_language_id'] = Language::whereCode('en')->first()->id;

        $newCompany = $this->companies->create($company);

        $this->companies->addBaseAttributes($newCompany);

        foreach ((array)$company['models'] as $model) {
            CompanyModel::create(['company_id' => $newCompany->id, 'model' => $model]);
            //adds the child product model with the parent since they cannot be separated
            if ($model == ParentProduct::class) {
                CompanyModel::create(['company_id' => $newCompany->id, 'model' => ChildProduct::class]);
            }
        }

        flash()->success(trans('company.create.saved'), true);

        return redirect()->route('company.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  EditRequest $request
     * @param  Company $company
     * @param FormBuilder $formBuilder
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(EditRequest $request, Company $company, FormBuilder $formBuilder)
    {
        $this->breadcrumb('company', trans('breadcrumb.companies.edit'), 'company.edit');

        $languages = Language::all();
        $users = User::all();

        $models = CompanyModelRepository::getModelsForCompany($company);

        $form = $formBuilder->create(CompanyEditForm::class, [
            'method' => 'PUT',
            'url' => route('company.update', $company),
        ], ['languages' => $languages, 'company' => $company, 'user' => $users, 'model' => $models]);

        return view('pim.companies.edit', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param Company $company
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Company $company)
    {
        $this->companies->update($company->id, $request->all());

        flash()->success(trans('company.edit.saved'), true);

        return redirect()->route('company.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DeleteRequest $request
     * @param  Company $company
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteRequest $request, Company $company)
    {
        $this->companies->delete($company->id);

        flash()->success(trans('company.deleted'), true);

        return redirect()->route('company.index');
    }

    /**
     * Add the given route (title and link) + listing to breadcrumb
     *
     * @param string $model Model name
     * @param string $title
     * @param array|string $link
     */
    protected function breadcrumb($model, $title = null, $link = null)
    {
        breadcrumb()->add(trans('breadcrumb.companies.listing'), $model . '.index');

        if ($title) {
            breadcrumb()->add($title, $link);
        }
    }

    /**
     * Tweak the array to unify both products type under a single product to avoid error
     * @return \string[]
     */
    private function getCompanyModels()
    {
        $models = CompanyModelRepository::getAllAvailableModelForDisplay();
        unset($models[ChildProduct::class]);
        $models[ParentProduct::class] = trans('company.model.products');

        return $models;
    }
}
