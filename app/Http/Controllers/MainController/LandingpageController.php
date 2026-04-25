<?php

namespace App\Http\Controllers\MainController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LandingpageController extends Controller
{
    public function index()
    {
        return view('User.Home');
    }
}
