<?php

namespace App\Controllers;


class Logout extends BaseController {
    public function logout()
    {

        if (!$this->session->has('user')) {
            return redirect()->to('/login');
        }
        $this->session->remove('user');
        return redirect()->to('/login')->with("success", "You have logged out successfully.");
    }
}
