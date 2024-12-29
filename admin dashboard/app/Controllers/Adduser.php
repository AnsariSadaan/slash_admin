<?php
namespace App\Controllers;

use App\Models\UserModel;

class Adduser extends BaseController
{
    public function adduser()
    {
        if ($this->request->getPost()) {
            $user_model = new UserModel();
            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'),PASSWORD_BCRYPT),
                'roles'=> $this->request->getPost('roles'),
            ];
            $user = $user_model->getUserByEmail($data['email']);
            if ($user) {
                return redirect()
                    ->back()
                    ->with('error', 'User already added with this email.');
            }

            $result = $user_model->saveUser($data);
            if ($result) {
                return redirect()
                    ->to('/dashboard')
                    ->with(
                        'success',
                        'user added successfull'
                    );
            } else {
                return redirect()
                    ->back()
                    ->with('error', 'Failed to register. Please try again.');
            }
        }
        return view('dashboard');
    }
}
