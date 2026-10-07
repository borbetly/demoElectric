<?php

namespace App\Controllers;

use App\Models\User;
use CodeIgniter\HTTP\RedirectResponse;

class Auth extends BaseController
{
    private User $users;

    public function __construct()
    {
        $this->users = new User();
    }

    public function login(): string|RedirectResponse
    {
        if (session()->get('isLoggedIn') === true) {
            return redirect()->to(route_to('auth.dashboard'));
        }

        if ($this->request->getMethod() === 'POST') {
            return $this->attemptLogin();
        }

        return view('auth/login', [
            'title'      => 'Log In - Puihaha Electric',
            'page'       => 'login',
            'success'    => session()->getFlashdata('success'),
            'error'      => session()->getFlashdata('error'),
            'validation' => session()->getFlashdata('validation'),
        ]);
    }

    public function dashboard(): string
    {
        return view('dashboard', [
            'title'    => 'Dashboard - Puihaha Electric',
            'page'     => 'dashboard',
            'userName' => session()->get('userName'),
        ]);
    }

    public function logout(): RedirectResponse
    {
        session()->destroy();

        return redirect()->to(route_to('auth.login'))->with('success', 'You have been logged out.');
    }

    private function attemptLogin(): RedirectResponse
    {
        $credentials = [
            'email'    => $this->postString('email'),
            'password' => $this->postString('password'),
        ];

        if (! $this->validateData($credentials, [
            'email'    => 'required|valid_email|max_length[100]',
            'password' => 'required|max_length[255]',
        ])) {
            return redirect()->back()->withInput()->with('validation', $this->validator->getErrors());
        }

        $user = $this->users->findByEmail($credentials['email']);
        $isActive = $user !== null && (bool) $user['is_active'];
        $validPassword = $user !== null && $this->users->verifyPassword($credentials['password'], $user['password']);

        if (! $isActive || ! $validPassword) {
            return redirect()->back()->withInput()->with('error', 'Invalid email address or password.');
        }

        session()->regenerate();
        session()->set([
            'isLoggedIn' => true,
            'userId'     => (int) $user['id'],
            'userName'   => trim($user['first_name'] . ' ' . $user['last_name']),
            'userType'   => $user['user_type'],
        ]);

        return redirect()->to(route_to('auth.dashboard'))->with('success', 'Welcome back, ' . $user['first_name'] . '!');
    }

    private function postString(string $key): string
    {
        $value = $this->request->getPost($key);

        return is_string($value) ? trim($value) : '';
    }
}
