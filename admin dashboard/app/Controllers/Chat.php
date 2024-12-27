<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Chat extends BaseController
{
    public function chat()
    {
        // Fetch users from the database
        $userModel = new UserModel(); // Create an instance of the User model
        $users = $userModel->findAll(); // Retrieve all users

        // Prepare the main content with users data
        $mainContent = view('chat', ['users' => $users]); // Pass users to the view

        // Return the template view with main content
        return view('Template', ['mainContent' => $mainContent]);
    }
}