<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use MongoDB\Client;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function test()
    {
        $client = new Client('mongodb+srv://team:Duan2023@teammanagement.nznugpk.mongodb.net');
        $collection = $client->test->user_account;
        return $collection->findOne(['name' => 'ngoc']);
    }
}
