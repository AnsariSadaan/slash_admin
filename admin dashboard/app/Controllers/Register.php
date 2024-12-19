<?php

namespace App\Controllers;
use App\Models\UserModel;

class Register extends BaseController
{

public function register()
    {
        if ($this->session->has('user')) {
            return redirect()->to('/dashboard');
        }
        if (isset($_POST['name'])) {
            $user_model = new UserModel();
            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT)
            ];
            $result = $user_model->save($data);
            if ($result) {
                    return redirect()->to('/login')->with('success', 'Registration successful! Please log in.');
            } else {
                return redirect()->back()->with('error', 'Failed to register. Please try again.');
            }
        }
        return view('register');
    }

}