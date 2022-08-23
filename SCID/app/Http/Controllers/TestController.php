<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
        return 'mu';
    }

    public function test(Request $request)
    {
        return $request;
    }
}
