<?php
namespace App\Controllers;

use App\Models\UserModel;

class Register extends BaseController
{
    public function register()
    {
        log_message('info', 'Register method accessed.');
        log_message('info', 'Request method: ' . $this->request->getMethod());

        if ($this->session->has('user')) {
            log_message('info', 'User already logged in.');
            return redirect()->to('/dashboard');
        }

        if ($this->request->getPost()) {
            log_message(
                'info',
                'POST data received: ' . json_encode($this->request->getPost())
            );

            $user_model = new UserModel();
            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'password' => password_hash(
                    $this->request->getPost('password'),
                    PASSWORD_BCRYPT
                ),
            ];

            log_message('info', 'Prepared data: ' . json_encode($data));

            $user = $user_model->getUserByEmail($data['email']);
            if ($user) {
                log_message(
                    'info',
                    'Email already registered: ' . $data['email']
                );
                return redirect()
                    ->back()
                    ->with('error', 'User already registered with this email.');
            }

            $result = $user_model->saveUser($data);
            if ($result) {
                log_message('info', 'User registered successfully.');
                return redirect()
                    ->to('/login')
                    ->with(
                        'success',
                        'Registration successful! Please log in.'
                    );
            } else {
                log_message('error', 'Failed to register user.');
                return redirect()
                    ->back()
                    ->with('error', 'Failed to register. Please try again.');
            }
        }

        log_message('info', 'Rendering registration view.');
        return view('register');
    }
}
