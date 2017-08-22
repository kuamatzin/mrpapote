<?php

namespace App\Http\Controllers;

use App\Services\SocialAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{

    /**
     * Get the user from Socialite and create or get the user in the db
     * @param  Laravel\Socialite\Contracts\User $providerUser 
     * @return  View
     */
    private function processUser($providerUser)
    {
        $user = SocialAccountService::createOrGetUser($providerUser, 'facebook');

        Auth::login($user, true);

        return redirect('/');
    }

    /**
     * Call the social provider login
     */
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();   
    }   

    /**
     * This methods triggers when provider cheks the login
     */
    public function callback()
    {
        return Input::get('code') ? $this->processUser(Socialite::driver('facebook')->user()) : redirect('/');
    }
}
