<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AccessLevelModel;
use App\Models\AuditLogModel;
use App\Models\UserModel;

class User extends BaseController
{
    protected $userModel;
    protected $accessLevelModel;

    public function __construct()
    {
        // Load the User model
        $this->userModel = new UserModel();
        $this->accessLevelModel = new AccessLevelModel();
    }


    public function dashboard()
    {
        if (!$this->session->has('user')) {
            return redirect()->to('/login');
        }

        // Fetch the logged-in user's details
        $loggedInUser = $this->session->get('user');

        // Get the logged-in user's role
        $role = $loggedInUser->roles;

        // Get pagination and search parameters
        $page = $this->request->getVar('page') ?? 1;
        $perPage = 4;
        $searchQuery = $this->request->getVar('searchQuery') ?? '';

        // Filter users based on the logged-in role
        if ($role === 'user') {
            // Regular users can only see other regular users
            $users = $this->userModel->getUsersByRole('user', $searchQuery, $page, $perPage);
            $totalUsers = $this->userModel->countUsersByRole('user', $searchQuery);
        } else {
            // Admins can see all users
            $users = $this->userModel->getPaginatedUsers($searchQuery, $page, $perPage);
            $totalUsers = $this->userModel->countUsers($searchQuery);
        }

        // Pagination logic
        $totalPages = ceil($totalUsers / $perPage);

        // Fetch roles from the access_level table for display (admin can assign roles)
        $roles = $this->accessLevelModel->getAllRoles();

        // Pass data to the view
        $mainContent = view('dashboard', [
            'users' => $users,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'searchQuery' => $searchQuery,
            'roles' => $roles,  // Roles for Add/Edit User
            'loggedInUser' => $loggedInUser,
            'role' => $role
        ]);

        return view('Template', ['mainContent' => $mainContent]);
    }



    // public function updateUser()
    // {

    //     if (!$this->session->has('user')) {
    //         return redirect()->to('/login');
    //     }

    //     $user_model = new UserModel();
    //     // Get the submitted data
    //     $id = $this->request->getPost('id');
    //     $name = $this->request->getPost('name');
    //     $email = $this->request->getPost('email');

    //     // Prepare data for update
    //     $updatedData = [];
    //     if ($name) {
    //         $updatedData['name'] = $name;
    //     }
    //     if ($email) {
    //         $updatedData['email'] = $email;
    //     }

    //     // Update the user via the model
    //     $user_model->updateUserById($id, $updatedData);

    //     return redirect()
    //         ->to('/dashboard')
    //         ->with('success', 'User details updated successfully');
    // }


    public function updateUser()
    {
        // Check if the logged-in user is authenticated
        if (!$this->session->has('user')) {
            return redirect()->to('/login');
        }

        // Get the logged-in user details
        $loggedinUser = $this->session->get('user');
        $loggedinUserId = $loggedinUser->id;
        $loggedinUserName = $loggedinUser->name;

        if ($this->request->getPost()) {
            $user_model = new UserModel();
            $auditlog_model = new AuditLogModel();

            // Get the submitted data
            $id = $this->request->getPost('id');
            $name = $this->request->getPost('name');
            $email = $this->request->getPost('email');

            // Fetch the existing user details
            $existingUser = $user_model->getUserById($id); // Assuming getUserById exists in the UserModel

            if (!$existingUser) {
                return redirect()->back()->with('error', 'User not found.');
            }

            // Prepare data for update and audit log
            $updatedData = [];
            $auditLogs = [];

            if ($name && $name !== $existingUser->name) {
                $updatedData['name'] = $name;
                $auditLogs[] = 'Name was updated. Previous name: "' . $existingUser->name . '", Updated name: "' . $name . '".';
            }

            if ($email && $email !== $existingUser->email) {
                $updatedData['email'] = $email;
                $auditLogs[] = 'Email was updated. Previous email: "' . $existingUser->email . '", Updated email: "' . $email . '".';
            }

            // If there are changes, proceed with the update
            if (!empty($updatedData)) {
                $user_model->updateUserById($id, $updatedData); // Assuming updateUserById exists in the UserModel

                // Log the changes in the audit log
                foreach ($auditLogs as $log) {
                    $auditData = [
                        'datetime' => date('Y-m-d H:i:s'),
                        'action' => 'update',
                        'user_id' => $loggedinUserId,
                        'name' => $loggedinUserName,
                        'logs' => $log,
                    ];

                    $auditlog_model->saveAuditLog($auditData);
                }

                return redirect()
                    ->to('/dashboard')
                    ->with('success', 'User details updated successfully.');
            } else {
                return redirect()
                    ->back()
                    ->with('info', 'No changes were made.');
            }
        }

        return redirect()->back()->with('error', 'Invalid request.');
    }

    public function deleteUser($id)
    {

        if (!$this->session->has('user')) {
            return redirect()->to('/login');
        }

        $loggedinUser = $this->session->get('user');
        $loggedinUserId = $loggedinUser->id;
        $loggedinUserName = $loggedinUser->name;

        $user_model = new UserModel();
        $auditlog_model = new AuditLogModel();

        // Fetch the user details before deletion
        $userToDelete = $user_model->getUserById($id); // Assuming getUserById exists in the UserModel

        if (!$userToDelete) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Delete the user via the model
        $result = $user_model->deleteUserById($id); // Assuming deleteUserById exists in the UserModel

        if ($result) {
            // Log the delete action in the audit log
            $auditData = [
                'datetime' => date('Y-m-d H:i:s'),
                'action' => 'delete',
                'user_id' => $loggedinUserId,
                'name' => $loggedinUserName,
                'logs' => 'User with name "' . $userToDelete->name . '" and email "' . $userToDelete->email . '" was deleted.',
            ];
            $auditlog_model->saveAuditLog($auditData);
        }

        return redirect()
            ->to('/dashboard')
            ->with('success', 'User deleted successfully');
    }
}
