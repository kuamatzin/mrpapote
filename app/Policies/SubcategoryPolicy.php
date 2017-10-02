<?php

namespace App\Policies;

use App\User;
use App\Subcategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubcategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the subcategory.
     *
     * @param  \App\User  $user
     * @param  \App\Subcategory  $subcategory
     * @return mixed
     */
    public function view(User $user, Subcategory $subcategory)
    {
        return $user->id == $subcategory->category->user_id;
    }

    /**
     * Determine whether the user can create subcategories.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user, $scategory_id, $category_id)
    {
        return $user->id == $scategory_id;
    }


    public function store(User $user, Subcategory $subcategory, $category_id)
    {
        return $user->id == $category_id;
    }

    /**
     * Determine whether the user can update the subcategory.
     *
     * @param  \App\User  $user
     * @param  \App\Subcategory  $subcategory
     * @return mixed
     */
    public function update(User $user, Subcategory $subcategory)
    {
        return $user->id == $subcategory->category->user_id;
    }

    /**
     * Determine whether the user can delete the subcategory.
     *
     * @param  \App\User  $user
     * @param  \App\Subcategory  $subcategory
     * @return mixed
     */
    public function delete(User $user, Subcategory $subcategory)
    {
        //
    }
}
