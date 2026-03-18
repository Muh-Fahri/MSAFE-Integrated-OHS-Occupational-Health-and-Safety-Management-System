<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MicrosoftController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('microsoft')
            ->scopes(['openid', 'profile', 'email', 'offline_access'])
            ->redirect();
    }

    public function callback()
    {
    	try {
	        $microsoftUser = Socialite::driver('microsoft')->user();
        	$user = User::where('email', $microsoftUser->getEmail())->where('status', 'active')->first();
        	if($user==null){
        		echo "USER NOT FOUND";exit;
        	} else {
        		Auth::login($user);
        	}
	    } catch (\Exception $e) {
	    	return redirect('/login')->withErrors('Microsoft login failed. Please try again.');
	    }
        // $user = User::updateOrCreate(
        //     ['email' => $microsoftUser->getEmail()],
        //     [
        //         'name' => $microsoftUser->getName() ?? $microsoftUser->getNickname(),
        //         'microsoft_id' => $microsoftUser->getId(),
        //         'password' => bcrypt(str()->random(16)),
        //     ]
        // );
        return redirect('/dashboard');
    }
}
