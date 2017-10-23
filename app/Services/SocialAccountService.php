<?php

namespace App\Services;

use App\SocialAccount;
use App\User;
use Laravel\Socialite\Contracts\User as ProviderUser;
use App\Notifications\WelcomeUser;

class SocialAccountService
{
    /**
     * Create the user
     * @param  Laravel\Socialite\Contracts\User $providerUser 
     * @return App\User               
     */
    private function createUser($providerUser, $account)
    {
            $user = User::create([
                'email' => $providerUser->getEmail(),
                'name' => $providerUser->getName(),
            ]);

            $user->notify(new WelcomeUser($user->name));

            return $this->associateUsertoAccount($account, $user);
    }

    /**
     * Associate the user to the account
     * @param  App\SocialAccount $account 
     * @param  App\User $user    
     * @return App\User          
     */
    private function associateUsertoAccount($account, $user)
    {
        $account->user()->associate($user);
        $account->save();

        return $user;
    }

    /**
     * Create the Social Account
     * @param  Laravel\Socialite\Contracts\User $providerUser Socialite user
     * @return  App\User
     */
    private function createSocialAccount($providerUser, $provider)
    {
        $account = new SocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => $provider
            ]);

        //Check if email already has an account, if yes associate account, if not create user
        $user = User::existsWithEmail($providerUser->getEmail())->first();
        return  $user ? $this->associateUsertoAccount($account, $user) : $this->createUser($providerUser, $account);
    }

    /**
     * Create or get the user
     * @param  Laravel\Socialite\Contracts\User $providerUser Socialite user
     * @param  String       $provider     
     * @return  App\User                     
     */
    public static function createOrGetUser(ProviderUser $providerUser, $provider)
    {
        $account = SocialAccount::whereProvider($provider)
            ->whereProviderUserId($providerUser->getId())
            ->first();

        return $account ? $account->user : (new self)->createSocialAccount($providerUser, $provider);
    }

}