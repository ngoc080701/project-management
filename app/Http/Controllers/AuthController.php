<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseFormRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyRequest;
use App\Jobs\SendMailRegisterJob;
use Carbon\Carbon;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
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
            $data = [
                'full_name' => $request->full_name,
                'email' => $request->email,
                'dob' => $request->dob ?? null,
                'address' => $request->address ?? null,
                'phone_number' => $request->phone_number ?? null,
                'gender' => $request->gender ?? null,
            ];
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
                $expiresAt = time() + 43200;
                $payload = [
                    'email' => $user->email,
                    'password' => $user->password,
                    "iat" => time(),
                    "exp" => $expiresAt
                ];
                $token = JWT::encode($payload, config('app.jwt_secret'), config('app.jwt_algo'));
                if (empty($token)) {
                    return $this->errorResult(new Exception(__('Authentication attempt failed.')));
                }
            }
            $urlVerify = URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(30),
                [
                    'id' => $user->_id,
                    'hash' => sha1($user->email),
                ]
            );
            SendMailRegisterJob::dispatch($user, $urlVerify);
            return $this->okResult(
                [
                    'user' => $user->jsonSerialize(),
                    'token' => $token,
                    'url' => $urlVerify
                ]
            );
        } catch (Exception $e) {
            return $this->errorResult($e);
        }
    }

    public function verifyEmail(VerifyRequest $request)
    {

    }

    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $client = new Client('mongodb+srv://team:Duan2023@teammanagement.nznugpk.mongodb.net');
            $collection = $client->team_management->users;
            $data = $request->all();
            $user = $collection->findOne(['email' => $data['email']]);
            if (!Hash::check($request->password, $user->password)) {
                return $this->notFoundResult('', 'user is not exists');
            }
            $expiresAt = time() + 43200;
            $payload = [
                'id' => (string)$user->_id,
                'email' => $user->email,
                "iat" => time(),
                "exp" => $expiresAt
            ];
            $token = JWT::encode($payload, config('app.jwt_secret'), config('app.jwt_algo'));
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

    public function logout(BaseFormRequest $request): JsonResponse
    {
        try {
            $token = $request->bearerToken();
            if ($token) {
                $client = new Client('mongodb+srv://team:Duan2023@teammanagement.nznugpk.mongodb.net');
                $collection = $client->team_management->invalid_tokens;
                $result = $collection->insertOne(['token' => $token]);
                if ($result->getInsertedCount() == 1) {
                    return $this->okResult([]);
                } else {
                    return $this->errorResult(new Exception(__('Unable to invalidate token.')));
                }
            } else {
                return $this->unprocessableEntityResult('', '', '', ['token' => ['Token is missing.']]);
            }
        } catch (Exception $e) {
            return $this->errorResult($e);
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            $client = new Client('mongodb+srv://team:Duan2023@teammanagement.nznugpk.mongodb.net');
            $collection = $client->team_management->users;
            $data = $request->all();
            $user = $collection->findOne(['email' => $data['email']]);
            if ($request->email != $user->email) {
                return $this->notFoundResult('', 'user is not exists');
            }
            $expiresAt = time() + 43200;
            $payload = [
                'id' => (string)$user->_id,
                'email' => $user->email,
                "iat" => time(),
                "exp" => $expiresAt
            ];
            $token = JWT::encode($payload, config('app.jwt_secret'), config('app.jwt_algo'));
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
