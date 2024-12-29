<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AccessLevelModel;
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
        // Get pagination and search parameters
        $page = $this->request->getVar('page') ?? 1; // Default to page 1 if no page is set
        $perPage = 2; // Define how many users per page
        $searchQuery = $this->request->getVar('searchQuery') ?? '';

        // Fetch data using the model
        $users = $this->userModel->getPaginatedUsers($searchQuery, $page, $perPage);
        $totalUsers = $this->userModel->countUsers($searchQuery);
        $totalPages = ceil($totalUsers / $perPage);

        // Fetch roles from the access_level table
        $roles = $this->accessLevelModel->getAllRoles();

        // Construct mainContent
        $mainContent = view('dashboard', [
            'users' => $users,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'searchQuery' => $searchQuery,
            'roles' => $roles, // Pass roles to the view
        ]);

        return view('Template', ['mainContent' => $mainContent]);
    }

    public function updateUser()
    {

        if (!$this->session->has('user')) {
            return redirect()->to('/login');
        }

        $user_model = new UserModel();
        // Get the submitted data
        $id = $this->request->getPost('id');
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');

        // Prepare data for update
        $updatedData = [];
        if ($name) {
            $updatedData['name'] = $name;
        }
        if ($email) {
            $updatedData['email'] = $email;
        }

        // Update the user via the model
        $user_model->updateUserById($id, $updatedData);

        return redirect()
            ->to('/dashboard')
            ->with('success', 'User details updated successfully');
    }

    public function deleteUser($id)
    {

        if (!$this->session->has('user')) {
            return redirect()->to('/login');
        }

        $user_model = new UserModel();

        // Delete the user via the model
        $user_model->deleteUserById($id);

        return redirect()
            ->to('/dashboard')
            ->with('success', 'User deleted successfully');
    }
}
