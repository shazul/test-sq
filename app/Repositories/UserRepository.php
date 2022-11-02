<?php

namespace Pimeo\Repositories;

use Pimeo\Models\Company;
use Pimeo\Models\User;

class UserRepository
{
    /**
     * Get all instance of User.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\User[]
     */
    public function all()
    {
        return User::all();
    }

    /**
     * Find an instance of User with the given ID.
     *
     * @param  int  $id
     * @return \Pimeo\Models\User
     */
    public function find($id)
    {
        return User::find($id);
    }

    /**
     * Create a new instance of User.
     *
     * @param  array  $attributes
     * @return \Pimeo\Models\User
     */
    public function create(array $attributes = [])
    {
        return User::create($attributes);
    }

    /**
     * Update the User with the given attributes.
     *
     * @param  int    $id
     * @param  array  $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return User::find($id)->update($attributes);
    }

    /**
     * Delete an entry with the given ID.
     *
     * @param  int  $id
     * @return bool|null
     * @throws \Exception
     */
    public function delete($id)
    {
        return User::find($id)->delete();
    }

    /**
     * Get all instance of User including Groups.
     *
     * @return LengthAwarePaginator|Collection|Paginator|\Pimeo\Models\User[]
     */
    public function allWithGroups()
    {
        return User::with('groups')->get();
    }

    /**
     * Get all instance of User for a Company including Groups.
     *
     * @return LengthAwarePaginator|Collection|Paginator|\Pimeo\Models\User[]
     */
    public function allForCompanyWithGroups(Company $company)
    {
        return User::whereHas('companies', function ($query) use ($company) {
            return $query->whereId($company->id);
        })->with('groups')->get();
    }

    public static function getAllSuperAdminUsers()
    {
        return User::whereHas('groups', function ($query) {
            $query->whereCode('super_admin');
        })->get();
    }
}
