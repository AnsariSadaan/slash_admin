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

        // Fetch all roles from the access_level table
        $roles = $this->accessLevelModel->getAllRoles();

        // Pass the users and roles data to the view
        $mainContent = view('accesslevel', ['users' => $users, 'roles' => $roles]);
        return view('Template', ['mainContent' => $mainContent]);
    }

    public function updateRole($id)
    {
        // Get new role from POST data
        $newRole = $this->request->getPost('roles');

        // Update the role of the user with the given ID
        $user = $this->userModel->find($id);
        if ($user) {
            $user->roles = $newRole;
            $this->userModel->save($user);
        }

        // Redirect back to the access level page
        return redirect()->to('/accesslevel');
    }
}
