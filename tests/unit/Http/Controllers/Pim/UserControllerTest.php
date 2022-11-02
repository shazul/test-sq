<?php

use Pimeo\Events\Pim\UserWasDeleted;
use Pimeo\Indexer\CompanyIndexer;
use Pimeo\Jobs\Pim\User\DeleteUser;
use Pimeo\Models\User;
use Tests\Libs\DatabaseSetup;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseSetup;

    /** @before */
    public function before()
    {
        $this->setupTestDatabase();
    }

    public function test_create_user_created_by()
    {
        $newUser = $this->createUser();

        $this->seeInDatabase('users', [
            'id'         => $newUser->id,
            'created_by' => $this->superAdmin->id,
            'updated_by' => $this->superAdmin->id,
        ]);
    }

    public function test_a_user_can_see_his_profile_page()
    {
        $this->actingAs($this->user);

        $this->get('/users/my-profile')->seeStatusCode(200);
    }

    public function test_a_user_cannot_see_user_page()
    {
        $this->actingAs($this->user);

        $this->get('/user')->seeStatusCode(403);
    }

    public function test_a_user_can_edit_his_profile()
    {
        $this->actingAs($this->user);

        $edited_user = $this->editUser($this->user);

        $this->seeInDatabase(
            'users',
            [
                'id'          => $edited_user->id,
                'email'       => 'test@test.test',
                'last_name'   => 'Cat',
                'first_name'  => 'Murphy',
            ]
        );
    }

    public function test_edit_a_user_updated_by()
    {
        $this->actingAs($this->user);
        $newUser = $this->createUser();

        $this->actingAs($this->admin);
        $edited_user = $this->editUser($newUser);

        $this->seeInDatabase(
            'users',
            [
                'id'          => $edited_user->id,
                'created_by'  => $this->user->id,
                'updated_by'  => $this->admin->id,
            ]
        );
    }

    public function test_an_administrator_can_delete_a_user()
    {
        $this->actingAs($this->admin);
        $user_id = $this->user->id;

        $delete_request = new DeleteUser($this->user);
        $this->expectsEvents(UserWasDeleted::class);
        $delete_request->handle();
        $deleted_user = User::find($user_id);

        self::assertNull($deleted_user);
    }
}
