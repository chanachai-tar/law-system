<?php

namespace App\Providers;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;
use Illuminate\Support\Arr;

class OidcProvider extends AbstractProvider implements ProviderInterface
{
    protected $scopes = ['openid', 'profile', 'email'];
    protected $scopeSeparator = ' ';

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(config('services.oidc.base_url') . '/v1/oauth/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return config('services.oidc.base_url') . '/v1/oauth/token';
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(config('services.oidc.base_url') . '/v1/oauth/userinfo', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id'       => Arr::get($user, 'sub'),
            'nickname' => Arr::get($user, 'preferred_username'),
            'name'     => Arr::get($user, 'name'),
            'email'    => Arr::get($user, 'email'),
            'avatar'   => Arr::get($user, 'picture'),
        ]);
    }
}
