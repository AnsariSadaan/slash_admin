<?php

namespace App\Controllers;

use App\Models\UserModel;

class Login extends BaseController
{
    public function login()
    {
        if ($this->session->get('user') && $this->session->get('user')->roles === 'admin') {
            return redirect()->to('/dashboard');
        }

        if ($this->request->getPost('email')) {
            $user_model = new UserModel();

            // Use the model method to find the user by email
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $user = $user_model->getUserByEmail($email);

            if ($user) {
                if (password_verify($password, $user->password)) {
                    if ($user->roles === "admin") {
                        $this->session->set('user', $user);
                        return redirect()->to('/dashboard')->with('success', 'Login successful!');
                    }
                    return redirect()->to('/login')->with('error', "You are not an admin.");
                } else {
                    return redirect()->back()->with('error', 'Invalid password. Please try again.');
                }
            } else {
                return redirect()->back()->with('error', 'Invalid email or password. Please try again.');
            }
        }

        return view('login');
    }
}
