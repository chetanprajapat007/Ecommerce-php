<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;

class LoginController extends BaseController
{
    public function index()
    {
        // If user is already logged in, redirect to appropriate dashboard
        if (session()->get('isLoggedIn')) {
            if (session()->get('role') === 'admin') {
                return redirect()->to('admin/dashboard');
            } else {
                return redirect()->to('employee/dashboard');
            }
        }
        
        return view('auth/login');
    }
    
    public function authenticate()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password');
        }
        
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();
        
        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'User not found');
        }
        
        if (!$userModel->verifyPassword($password, $user['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid password');
        }
        
        if ($user['status'] !== 'active') {
            return redirect()->back()->withInput()->with('error', 'Your account is inactive. Please contact administrator.');
        }
        
        // Set session data
        $sessionData = [
            'user_id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'is_data_entry_allowed' => $user['is_data_entry_allowed'],
            'isLoggedIn' => true
        ];
        
        session()->set($sessionData);
        
        // Redirect to appropriate dashboard
        if ($user['role'] === 'admin') {
            return redirect()->to('admin/dashboard');
        } else {
            return redirect()->to('employee/dashboard');
        }
    }
}

