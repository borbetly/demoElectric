<?php

namespace App\Controllers;

use App\Models\UserModel;

class Home extends BaseController
{
    
  
    public function login()
    {
        if (session()->get('isLogged') === true) {
            return redirect()->to('/dashboard');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'username' => 'required|max_length[100]',
                'password' => 'required|max_length[255]',
            ];

            if (! $this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', 'Enter both your username and password.');
            }

            $credentials = $this->request->getPost(['username', 'password']);
            $user = (new UserModel())->where('username', $credentials['username'])->first();

            // New accounts should store passwords with password_hash(). The second
            // condition keeps existing classroom databases with plaintext passwords working.
            $validPassword = $user !== null
                && (password_verify($credentials['password'], $user['password'])
                    || hash_equals((string) $user['password'], (string) $credentials['password']));

            if (! $validPassword) {
                return redirect()->back()->withInput()->with('error', 'Invalid username or password.');
            }

            session()->regenerate();
            session()->set([
                'isLogged' => true,
                'user_id'  => $user['id'],
                'username' => $user['username'],
            ]);

            return redirect()->to('/dashboard');
        }

        return view('login');
    }

    public function dashboard()
    {
        if (session()->get('isLogged') !== true) {
            return redirect()->to('/login');
        }

        return view('dashboard', [
            'username' => session()->get('username'),
        ]);
    }

    public function logout()
    {
        if (session()->get('isLogged') !== true) {
            return redirect()->to('/login');
        }

        session()->destroy();
        return redirect()->to('/login')->with('success', 'You have been logged out.');
    }


    
}
