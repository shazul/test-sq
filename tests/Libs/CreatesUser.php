<?php

namespace Tests\Libs;

use Pimeo\Events\Pim\UserWasUpdated;
use Pimeo\Jobs\Pim\User\UpdateUser;
use Pimeo\Models\Company;
use Pimeo\Models\Group;
use Pimeo\Models\User;

trait CreatesUser
{
    /**
     * @return User
     */
    public function createAdminUser()
    {
        /** @var User $admin */
        $admin = factory(User::class)->create([
            'first_name' => 'admin',
            'last_name'  => 'admin',
        ]);

        $admin_group = Group::where('code', Group::ADMIN_CODE)->first();
        $admin->groups()->attach($admin_group);

        return $admin;
    }

    public function createSuperAdminUser()
    {
        /** @var User $admin */
        $admin = factory(User::class)->create([
            'first_name' => 'super',
            'last_name'  => 'admin',
            'active_language_id' => 2,
            'active_company_id' => 1,
        ]);

        $admin_group = Group::where('code', Group::SUPER_ADMIN_CODE)->first();
        $admin->groups()->attach($admin_group);
        $admin->companies()->attach(1);

        return $admin;
    }

    /**
     * @return User
     */
    public function createEditor()
    {
        $company = Company::first();

        /** @var User $editor */
        $editor = factory(User::class)->create([
            'last_name'  => 'editor_lastname',
            'first_name' => 'editor_firstname',
        ]);

        $editor->companies()->attach($company);

        $editor_group = Group::where('code', Group::EDITOR_CODE)->first();
        $editor->groups()->attach($editor_group);

        return $editor;
    }

    /**
     * @return User
     */
    public function createUser()
    {
        $company = Company::first();

        /** @var User $user */
        $user = factory(User::class)->create([
            'last_name'  => 'Hello',
            'first_name' => 'World',
            'password'   => '1qazxsw2',
        ]);

        $user->companies()->attach($company);

        $user_group = Group::where('code', Group::USER_CODE)->first();
        $user->groups()->attach($user_group);

        return $user;
    }

    /**
     * @param User $user
     * @return User
     */
    public function editUser(User $user)
    {
        $user_group = Group::where('code', Group::USER_CODE)->first();

        $data = [
            'email'            => 'test@test.test',
            'groups'           => [$user_group->id],
            'last_name'        => 'Cat',
            'first_name'       => 'Murphy',
            'current_password' => '1qazxsw2',
        ];

        $edit_request = new UpdateUser($user, $data);
        $this->expectsEvents(UserWasUpdated::class);
        $edit_request->handle();

        return User::find($user->id);
    }
}
