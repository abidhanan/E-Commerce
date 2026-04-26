<?php

namespace App\Http\Controllers\MainController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function showProfile()
    {
    return view('User.profile');
}
}