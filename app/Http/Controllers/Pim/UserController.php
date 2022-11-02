<?php

namespace Pimeo\Http\Controllers\Pim;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Pimeo\Http\Controllers\Controller;
use Pimeo\Http\Requests\Pim\User\CreateRequest;
use Pimeo\Http\Requests\Pim\User\DeleteRequest;
use Pimeo\Http\Requests\Pim\User\EditRequest;
use Pimeo\Http\Requests\Pim\User\ManageRequest;
use Pimeo\Http\Requests\Pim\User\ProfileUpdateRequest;
use Pimeo\Http\Requests\Pim\User\UpdateRequest;
use Pimeo\Jobs\Pim\User\CreateUser;
use Pimeo\Jobs\Pim\User\DeleteUser;
use Pimeo\Jobs\Pim\User\UpdateCurrentUser;
use Pimeo\Jobs\Pim\User\UpdateUser;
use Pimeo\Models\Company;
use Pimeo\Models\Language;
use Pimeo\Models\User;
use Pimeo\Repositories\CompanyRepository;
use Pimeo\Repositories\GroupRepository;
use Pimeo\Repositories\UserRepository;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @var GroupRepository
     */
    protected $groups;

    /**
     * @param GroupRepository $groups
     * @param UserRepository  $users
     */
    public function __construct(GroupRepository $groups, UserRepository $users)
    {
        $this->groups = $groups;
        $this->users = $users;
    }

    /**
     * Display a listing of the resource.
     *
     * @param ManageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(ManageRequest $request)
    {
        $users = User::with(['groups', 'companies'])->get();

        $this->breadcrumb('user');

        return view('pim.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ManageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(ManageRequest $request)
    {
        $groups = $this->groups->manageableByUser($request->user());
        $companies = Company::all();

        $this->breadcrumb('user', trans('breadcrumb.users.create'), 'user.create');

        return view('pim.users.create', compact('groups', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $this->dispatch(new CreateUser($request->all()));

        flash()->success(trans('user.create.saved'), true);

        return redirect()->route('user.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  EditRequest $request
     * @param  User        $user
     * @return \Illuminate\Http\Response
     */
    public function edit(EditRequest $request, User $user)
    {
        $groups = $this->groups->manageableByUser($request->user());
        $companies = Company::all();

        $this->breadcrumb('user', trans('breadcrumb.users.edit'), 'user.edit');

        return view('pim.users.edit', compact('user', 'groups', 'companies'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editMyself()
    {
        $user = auth()->user();

        $this->breadcrumb('user', trans('breadcrumb.users.edit-my-profile'), 'user.edit-my-profile');

        return view('pim.users.myprofile', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request
     * @param  User          $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, User $user)
    {
        $this->dispatch(new UpdateUser($user, $request->all()));

        flash()->success(trans('user.edit.saved'), true);

        return redirect()->route('user.index');
    }

    /**
     * Update current user's profile in storage.
     *
     * @param  ProfileUpdateRequest $request
     * @param  User                 $user
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(ProfileUpdateRequest $request, User $user)
    {
        $this->dispatch(new UpdateCurrentUser($user, $request->all()));

        flash()->success(trans('user.my_profile.saved'), true);

        return redirect()->route('user.edit-my-profile');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DeleteRequest $request
     * @param  User          $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteRequest $request, User $user)
    {
        $this->dispatch(new DeleteUser($user));

        flash()->success(trans('user.deleted'), true);

        return redirect()->route('user.index');
    }

    /**
     * @param Guard    $auth
     * @param Language $language
     * @return RedirectResponse
     */
    public function changeLanguage(Guard $auth, Language $language)
    {
        /** @var User $user */
        $this->switchLanguage($auth, $language);

        return back();
    }

    /**
     * @param Guard    $auth
     * @param Company $company
     * @return RedirectResponse
     */
    public function changeCompany(Guard $auth, Company $company)
    {
        $auth->user()->update([
            'active_company_id' => $company->id,
        ]);

        if ($company->languages()->where('code', current_language_code())->count() == 0) {
            $this->switchLanguage($auth, $company->defaultLanguage);
        }

        return redirect()->route('home');
    }

    protected function switchLanguage($auth, Language $language)
    {
        $user                     = $auth->user();
        $user->active_language_id = $language->id;
        $user->save();

        app()->setLocale($language->code);
    }
}
