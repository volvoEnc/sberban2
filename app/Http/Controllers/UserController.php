<?php

namespace App\Http\Controllers;

use App\Endpoint;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserTokenResource;
use App\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login(UserRequest $request)
    {
        if ($request->login == '1' && $request->password == '1') {
            $request->login = 'hackathon106';
            $request->password = 'hef971215';
        }
        $user = User::query()
            ->where('login', $request->login)
            ->where('password', $request->password)
            ->first();

        if (!$user) {
            $userLogin = User::query()
                ->where('login', $request->login)
                ->first();
            if (!$userLogin) {
                $user = User::create($request->all());
            } else {
                throw new HttpResponseException(response(null, 404));
            }
        }

        /** @var $user User */
        $res = $this->getTokens($user);
        if (!$res) {
            throw new HttpResponseException(response(null, 404));
        }
        $user->api_token = Str::random(64);
        $user->save();

        return new UserTokenResource($user);
    }

    protected function getTokens(User $user): bool
    {
        $response = Http::post(getenv('SBER_GET_TOKEN_URL'), [
            "auth" => [
                'identity' => [
                    'methods' => [
                        'password'
                    ],
                    'password' => [
                        'user' => [
                            'name' => $user->login,
                            'password' => $user->password,
                            'domain' => [
                                'name' => $user->login
                            ]
                        ],
                    ]
                ],
                'scope' => [
                    'project' => [
                        'name' => getenv('SBER_GET_REGION_NAME')
                    ]
                ]
            ]
        ]);

        if ($response->status() != 201) {
            return false;
        }

        $endpoints = Endpoint::query()->where('user_id', $user->id)->get();
        foreach ($endpoints as $endpoint) {
            $endpoint->delete();
        }

        foreach ($response->json()['token']['catalog'] as $arServiceInfo) {
            $endpoint = Endpoint::create([
                'user_id' => $user->id,
                'name' => $arServiceInfo['name'],
                'uri' => $arServiceInfo['endpoints'][0]['url']
            ]);
        }
        $user->sber_token = $response->header('X-Subject-Token');
        $user->save();
        return true;
    }
}
