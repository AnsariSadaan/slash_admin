<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        if ($this->session->has('user')) {
            return redirect()->to('/dashboard');
        }
        return view('login');
    }
    
    
}
