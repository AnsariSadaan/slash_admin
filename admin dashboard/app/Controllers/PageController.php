<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class PageController extends Controller
{
    public function renderPage($page)
    {
        // Define a list of available pages
        $availablePages = [
            'home' => 'Home/index',
            'register' => 'Register/Register',
            'login' => 'Login/Login',
            'dashboard' => 'User /Dashboard',
            'campaign' => 'Campaign/addCampaign',
            'accesslevel' => 'AccessLevel/accessLevel',
            'chat' => 'Chat/chat',
            // Add more pages as needed
        ];

        // Check if the requested page exists in the available pages
        if (array_key_exists($page, $availablePages)) {
            return view($availablePages[$page]);
        } else {
            // If the page does not exist, show a 404 error
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound($page);
        }
    }
}
