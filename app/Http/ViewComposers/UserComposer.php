<?php

namespace Pimeo\Http\ViewComposers;

use Auth;
use Illuminate\View\View;

class UserComposer
{
    protected $user;

    /**
     * Create a new user composer.
     *
     * @param  User  $user
     * @return void
     */
    public function __construct()
    {
        $this->user = Auth::user();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('user', $this->user);
    }
}
