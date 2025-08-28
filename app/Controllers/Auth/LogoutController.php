<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;

class LogoutController extends BaseController
{
    public function index()
    {
        // Destroy session
        session()->destroy();
        
        return redirect()->to('login')->with('success', 'You have been logged out successfully');
    }
}

