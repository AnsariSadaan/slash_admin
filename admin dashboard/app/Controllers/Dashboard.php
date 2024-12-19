<?php

namespace App\Controllers;

use App\Models\CampaignModel;
use App\Models\UserModel;
class Dashboard extends BaseController
{
    public function dashboard()
    {
        if (!$this->session->has('user')) {
            return redirect()->to('/login');
        }
        $user_model = new UserModel();

        // Pagination setup
        $page = $this->request->getVar('page') ?? 1;  // Default to page 1 if no page is set
        $perPage = 2;  // Define how many users per page
        $offset = ($page - 1) * $perPage;  // Offset for the SQL query

        // Get search query from URL
        $searchQuery = $this->request->getVar('searchQuery') ?? '';

        // Apply search filter
        if ($searchQuery) {
            // If search query is set, filter by name (or other fields)
            $users = $user_model->like('name', $searchQuery)
                ->orderBy('id', 'ASC')
                ->findAll($perPage, $offset);
        } else {
            $users = $user_model->orderBy('id', 'ASC')
                ->findAll($perPage, $offset);
        }

        // Get the total number of users for pagination
        $totalUsers = $user_model->countAll();

        // Calculate the number of pages
        $totalPages = ceil($totalUsers / $perPage);


        // $users = $user_model->findAll();
        return view('Template', [
            'users' => $users,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'searchQuery' => $searchQuery
        ]);
    }


    public function updateUser()
    {
        $user_model = new UserModel();

        // Get the submitted data
        $id = $this->request->getPost('id');
        // print( $mongoId) ; die;// Get MongoDB ID from form
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');

        // Prepare data for update in MySQL
        $updatedData = [];
        if ($name) $updatedData['name'] = $name;
        if ($email) $updatedData['email'] = $email;

        // Step 1: Update the user in MySQL
        $user_model->update($id, $updatedData);
        return redirect()->to('/dashboard')->with('success', 'user details updated successfully');
    }


    public function deleteUser($id)
    {
        $user_model = new UserModel();

        // Delete the user from the relational database (MySQL, etc.)
        $result = $user_model->delete($id);

        return redirect()->to('/dashboard')->with('success', 'user deleted successfully');
    }


    public function addCampaign()
    {
        return view('campaign');
    }

    public function storeCampaign()
    {
        $model = new CampaignModel();

        // Validate the input
        $validation = $this->validate([
            'name' => 'required|min_length[3]',
            'description' => 'required',
            'client' => 'required',
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('error', 'Please fill in all fields correctly.');
        }

        // Prepare data for insertion
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'client' => $this->request->getPost('client'),
        ];

        // Insert data into the database
        if ($model->insert($data)) {
            return redirect()->to('campaign')->with('success', 'Campaign added successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to add campaign.');
        }
    }


    public function updateCampaign()
    {
        $campaign_model = new CampaignModel();

        // Get the submitted data
        $id = $this->request->getPost('id');
        // print( $mongoId) ; die;// Get MongoDB ID from form
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $client = $this->request->getPost('client');

        // Prepare data for update in MySQL
        $updatedData = [];
        if ($name) $updatedData['name'] = $name;
        if ($description) $updatedData['description'] = $description;
        if ($client) $updatedData['client'] = $client;

        // Step 1: Update the user in MySQL
        $campaign_model->update($id, $updatedData);
        return redirect()->to('/showCampaign')->with('success', 'user details updated successfully');
    }

    public function deleteCampaign($id)
    {
        $campaign_model = new CampaignModel();

        // Delete the user from the relational database (MySQL, etc.)
        $result = $campaign_model->delete($id);

        return redirect()->to('/showCampaign')->with('success', 'user deleted successfully');
    }

    public function showCampaign(){
        $campaign_model = new CampaignModel();

        // Pagination setup
        $page = $this->request->getVar('page') ?? 1;  // Default to page 1 if no page is set
        $perPage = 2;  // Define how many users per page
        $offset = ($page - 1) * $perPage;  // Offset for the SQL query

        // Get search query from URL
        $searchQuery = $this->request->getVar('searchQuery') ?? '';

        // Apply search filter
        if ($searchQuery) {
            // If search query is set, filter by name (or other fields)
            $campaign = $campaign_model->like('name', $searchQuery)
                ->orderBy('id', 'ASC')
                ->findAll($perPage, $offset);
        } else {
            $campaign = $campaign_model->orderBy('id', 'ASC')
                ->findAll($perPage, $offset);
        }

        // Get the total number of users for pagination
        $totalUsers = $campaign_model->countAll();

        // Calculate the number of pages
        $totalPages = ceil($totalUsers / $perPage);

        return view('showCampaign', ['campaign'=>$campaign, 'totalPages' => $totalPages,
            'currentPage' => $page,
            'searchQuery' => $searchQuery]);
    }


    public function chat() {
        return view('chat');
    }
}
