<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;

class LoginController extends BaseController
{
    public function index()
    {
        // Check if already logged in
        if (session()->get('isLoggedIn')) {
            // Redirect based on role
            if (session()->get('role') === 'admin') {
                return redirect()->to('/admin/dashboard');
            } else {
                return redirect()->to('/employee/dashboard');
            }
        }
        
        return view('auth/login');
    }
    
    public function authenticate()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();
        
        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Email or password is incorrect');
        }
        
        if ($user['status'] !== 'active') {
            return redirect()->back()->withInput()->with('error', 'Your account is inactive. Please contact the administrator.');
        }
        
        if (!$userModel->verifyPassword($password, $user['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Email or password is incorrect');
        }
        
        // Set session data
        $sessionData = [
            'user_id'             => $user['id'],
            'name'                => $user['name'],
            'email'               => $user['email'],
            'role'                => $user['role'],
            'is_data_entry_allowed' => $user['is_data_entry_allowed'],
            'isLoggedIn'          => true,
        ];
        
        session()->set($sessionData);
        
        // Redirect based on role
        if ($user['role'] === 'admin') {
            return redirect()->to('/admin/dashboard');
        } else {
            return redirect()->to('/employee/dashboard');
        }
    }
}

