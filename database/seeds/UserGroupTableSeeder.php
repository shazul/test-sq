<?php

use Illuminate\Database\Seeder;
use Pimeo\Models\Company;
use Pimeo\Models\Group;
use Pimeo\Models\User;

class UserGroupTableSeeder extends Seeder
{

    const SUPER_ADMIN_LOGIN_EMAIL = 'superadmin@libeo.com';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->insertGroups();
        $this->insertUsers();

        /** @var User $super_user */
        $super_user = User::where('email', self::SUPER_ADMIN_LOGIN_EMAIL)->first();
        /** @var Group $super_admin_group */
        $super_admin_group = Group::where('code', Group::SUPER_ADMIN_CODE)->first();

        /** attach group */
        $super_admin_group->users()->attach($super_user->id);
        $super_admin_group->save();

        /** @var Company $company */
        $company = Company::where('name', CompanyTableSeeder::COMPANY_NAME)->first();
        $company->users()->attach($super_user->id);
    }

    private function insertGroups()
    {
        factory(Group::class)->create([
            'name' => 'Super Admin',
            'code' => Group::SUPER_ADMIN_CODE
        ]);

        factory(Group::class)->create([
            'name' => 'Admin',
            'code' => Group::ADMIN_CODE
        ]);

        factory(Group::class)->create([
            'name' => 'Editor',
            'code' => Group::EDITOR_CODE
        ]);

        factory(Group::class)->create([
            'name' => 'User',
            'code' => Group::USER_CODE
        ]);
    }

    private function insertUsers()
    {
        factory(User::class)->create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => self::SUPER_ADMIN_LOGIN_EMAIL,
            'password' => bcrypt('1qazxsw2'),
            'active' => 1,
        ]);
    }
}
