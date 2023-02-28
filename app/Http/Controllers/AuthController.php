<?php

namespace App\Http\Controllers;

use App\Enum\AuthConst;
use App\Http\Requests\RegisterRequest;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use MongoDB\Client;

class AuthController extends BaseController
{
    public function __construct()
    {
    }

    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $client = new Client('mongodb+srv://team:Duan2023@teammanagement.nznugpk.mongodb.net');
            $collection = $client->team_management->users;
            dd($collection);
            $data = $request->all();
            $data['password'] = bcrypt($request->password);
            $user = $collection->findOne(['email' => $data['email']]);
            if (isset($user->email_verified_at)) {
                return $this->unprocessableEntityResult('',
                    '',
                    '', [
                        "email" => [
                            'email has already exists'
                        ]
                    ]);
            } else {
                if (!isset($user)) {
                    $collection->insertOne(array_merge($data, ['email_verified_at' => null]));
                } else {
                    $collection->updateOne(
                        ['email' => $data['email']],
                        ['$set' => array_merge($data, ['email_verified_at' => null])]
                    );
                }
                $user = $collection->findOne(['email' => $data['email']]);
                $token = Auth::attempt($request->only('email', 'password'));
                dd($token);
                $token = auth(AuthConst::GUARD_API)->attempt([
                    'email' => $user->email,
                    'password' => $data["password"]
                ]);
                if (empty($token)) {
                    return $this->errorResult(new Exception(__('Authentication attempt failed.')));
                }
                event(new Registered($user));

            }
            event(new Registered($user));

            DB::commit();
            return $this->okResult(
                new RegisterResource(
                    new UserResource($user),
                    $token,
                    AuthConst::TOKEN_TYPE,
                    auth(AuthConst::GUARD_API)->factory(null)->getTTL() * 60,
                    $user->verification_url
                )
            );
            return $this->okResult([]);
        } catch (Exception $e) {
            return $this->errorResult($e);
        }
    }
}
