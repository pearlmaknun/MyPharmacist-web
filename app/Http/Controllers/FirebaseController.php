<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class FirebaseController extends Controller
{
    public function index()
    {
        $factory = (new Factory())
            ->withServiceAccount(__DIR__.'/FirebaseKey.json')->create();

        $database = $factory->getDatabase();
        $ref = $database->getReference('Chats');
        
        $key = $ref->push()->getKey();

        return $key;
    }
}
