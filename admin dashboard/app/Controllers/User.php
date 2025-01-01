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

    // public function dashboard()
    // {
    //     if (!$this->session->has('user')) {
    //         return redirect()->to('/login');
    //     }
    //     // Get pagination and search parameters
    //     $page = $this->request->getVar('page') ?? 1; // Default to page 1 if no page is set
    //     $perPage = 2; // Define how many users per page
    //     $searchQuery = $this->request->getVar('searchQuery') ?? '';

    //     // Fetch data using the model
    //     $users = $this->userModel->getPaginatedUsers($searchQuery, $page, $perPage);
    //     $totalUsers = $this->userModel->countUsers($searchQuery);
    //     $totalPages = ceil($totalUsers / $perPage);

    //     // Fetch roles from the access_level table
    //     $roles = $this->accessLevelModel->getAllRoles();

    //     // Construct mainContent
    //     $mainContent = view('dashboard', [
    //         'users' => $users,
    //         'totalPages' => $totalPages,
    //         'currentPage' => $page,
    //         'searchQuery' => $searchQuery,
    //         'roles' => $roles, // Pass roles to the view
    //     ]);

    //     return view('Template', ['mainContent' => $mainContent]);
    // }

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
