<?php


namespace App\Http\Controllers;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Laravel\Socialite\Contracts\Provider;

use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class SocialAccountController extends Controller
{

    public function create(Provider $provider, $request)
    {
        $providerUser = $provider->stateless()->user();
        $providerName = class_basename($provider);

        $account = SocialAccount::whereProvider($providerName)
            ->whereProviderUserId($providerUser->getId())
            ->first();

        if ($account) {
            $accessToken = JWTAuth::fromUser($account->user);
            return response()->json([
                "message" => "Success",
                "access_token" => $accessToken
            ]);;
        } else {
            $account = new SocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => $providerName
            ]);

            $user = User::whereEmail($providerUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'display_name' => $providerUser->getName(),
                    'email' => $providerUser->getEmail(),
                    'password' => bcrypt('Ptuan123987@'),
                ]);
            }
            $accessToken = JWTAuth::fromUser($user);

            $account->user()->associate($user);
            $account->save();


            return response()->json([
                "message" => "Success",
                "access_token" => $accessToken
            ]);
        }
    }

    public function redirectToProvider($provider, Request $request)
    {

        $url = Socialite::driver($provider)->stateless()
                ->redirectUrl($request->redirect_url)
                ->redirect()
                ->getTargetUrl();

        return response()->json([
            "login_url" => $url,
        ]);
    }

    public function handleProviderCallback($provider,Request $request)
    {
        if ($provider !== 'google' && $provider !== 'github') {
            return response()->json([
                'message' => 'Unsupported provider',
            ], 400);
        }
        $user = $this->create(Socialite::driver($provider), $request);
        return $user;
    }
}
