<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use Exception;
use Firebase\JWT\JWT;
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
                $payload = [
                    'id' => (string) $user->_id,
                    'email' => $user->email,
                ];
                $token = JWT::encode($payload, env('JWT_SECRET'), env('JWT_ALGO'));
                if (empty($token)) {
                    return $this->errorResult(new Exception(__('Authentication attempt failed.')));
                }
            }
            return $this->okResult(
                [
                    'user' => $user->jsonSerialize(),
                    'token' => $token
                ]
            );
        } catch (Exception $e) {
            return $this->errorResult($e);
        }
    }
}
