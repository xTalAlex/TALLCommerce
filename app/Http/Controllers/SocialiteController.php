<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return RedirectResponse
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return RedirectResponse
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            session()->flash('flash.banner', __('banner_notifications.socialite.no_user'));
            session()->flash('flash.bannerStyle', 'danger');

            return redirect()->route('login');
        }

        $user = User::where('email', insensitiveLike(), $googleUser->email)->first();

        if($user){
            if($user->socialite_provider != 'google')
                $user = null;
        }
        else{
            $user = User::create([
                'email' => $googleUser->email,
                'socialite_provider' => 'google',
                'socialite_id' => $googleUser->id,
                'name' => $googleUser->user['given_name'].' '.$googleUser->user['family_name'],
                'password' => Hash::make(Str::random(10)),
                // 'socialite_token' => $googleUser->token,
                // 'socialite_refresh_token' => $googleUser->refreshToken,
            ]);
        }

        if ($user) auth()->login($user, true);
        return $user ? redirect()->intended('/')
            : redirect()->to('/login')->withMessage(__('auth.failed'));
    }
}
