<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AccessLevel extends BaseController
{
    protected $userModel;
    public function __construct()
    {
        // Load the User model
        $this->userModel = new UserModel();
    }

    public function accessLevel()
    {
        // Fetch all users and their data
        $users = $this->userModel->findAll();
        // Pass the users data to the view
        $mainContent = view('accesslevel', ['users' => $users]);
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
