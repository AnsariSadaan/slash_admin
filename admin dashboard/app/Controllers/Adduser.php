<?php

namespace App\Controllers;

use App\Models\AuditLogModel;
use App\Models\UserModel;

class Adduser extends BaseController
{
    public function adduser()
    {

        // Check if the logged-in user is authenticated
        if (!$this->session->has('user')) {
            return redirect()->to('/login');
        }

        // Get the logged-in user details
        $loggedinUser = $this->session->get('user');
        $loggedinUserId = $loggedinUser->id;
        $loggedinUserName = $loggedinUser->name;
        $loggedinUserRole = $loggedinUser->roles; // assuming userRole is 'admin' or other roles


        // Only proceed if the logged-in user is an admin
        if ($loggedinUserRole !== 'admin') {
            return redirect()->to('/users')->with('error', 'You do not have permission to add a user.');
        }

        if ($this->request->getPost()) {
            $user_model = new UserModel();
            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
                'roles' => $this->request->getPost('roles'),
            ];
            $user = $user_model->getUserByEmail($data['email']);
            if ($user) {
                return redirect()
                    ->back()
                    ->with('error', 'User already added with this email.');
            }

            $result = $user_model->saveUser($data);

            // If the user is added successfully, create an audit log
            if ($result) {
                // Prepare the data for the audit log
                $auditlog_model = new AuditLogModel();
                $auditData = [
                    'datetime' => date('Y-m-d H:i:s'),
                    'action' => 'create',
                    'user_id' => $loggedinUserId,
                    'name' => $loggedinUserName,
                    'logs'=> 'User with name ' . $this->request->getPost('name') . ' was added.'
                ];

                $auditlog_model->saveAuditLog($auditData);

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
