<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\AccessLevelModel;

class AccessLevel extends BaseController
{
    protected $userModel;
    protected $accessLevelModel;

    public function __construct()
    {
        // Load the User and AccessLevel models
        $this->userModel = new UserModel();
        $this->accessLevelModel = new AccessLevelModel();
    }

    public function accessLevel()
    {
        if (!$this->session->has('user')) {
            return redirect()->to('/login');
        }
        // Fetch all users and their data
        $users = $this->userModel->getAllUsers();
        $loggedInUser = $this->session->get('user');
        $role = $loggedInUser->roles;
        // Fetch all roles from the access_level table
        $roles = $this->accessLevelModel->getAllRoles();

        // Pass the users and roles data to the view
        $mainContent = view('accesslevel', ['users' => $users, 'roles' => $roles, 'role' => $role, 'loggedInUser' => $loggedInUser]);
        return view('Template', ['mainContent' => $mainContent]);
    }

    // public function updateRole($id)
    // {
    //     // Get new role from POST data
    //     $newRole = $this->request->getPost('roles');
    //     $loggedInUser = $this->session->get('user');
    //     if($loggedInUser ->roles !== 'admin'){
    //         return redirect()->to('/accesslevel')->with('error', 'Unauthorized access.');
    //     }
    //     // Update the role of the user with the given ID
    //     $user = $this->userModel->find($id);
    //     if ($user) {
    //         $user->roles = $newRole;
    //         $this->userModel->updateRole($user);
    //     }
    //     // Redirect back to the access level page
    //     return redirect()->to('/accesslevel');
    // }

    public function updateRole($id)
    {
        // Get the logged-in user
        $loggedInUser = $this->session->get('user');

        // Check if the logged-in user is an admin
        if ($loggedInUser->roles !== 'admin') {
            return redirect()->to('/accesslevel')->with('error', 'Unauthorized access.');
        }

        // Get the new role from the POST data
        $newRole = $this->request->getPost('roles');

        // Validate the role
        $validRoles = $this->accessLevelModel->getAllRoles();
        // $roleNames = array_column($validRoles, 'roles');

        // if (!in_array($newRole, $roleNames)) {
        //     return redirect()->back()->with('error', 'Invalid role selected.');
        // }

        // Update the role of the user with the given ID
        $user = $this->userModel->find($id);
        if ($user) {
            $this->userModel->update($id, ['roles' => $newRole]);
        } else {
            return redirect()->to('/accesslevel')->with('error', 'User not found.');
        }

        // Redirect back with a success message
        return redirect()->to('/accesslevel')->with('success', 'Role updated successfully.');
    }
}
