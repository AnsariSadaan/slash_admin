<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CampaignModel;

class Campaign extends BaseController
{


    public function showCampaign()
    {

        if (!$this->session->has('user')) {
            return redirect()->to('/login');
        }
        $campaign_model = new CampaignModel();

        // Pagination setup
        $page = $this->request->getVar('page') ?? 1; // Default to page 1 if no page is set
        $perPage = 2; // Define how many users per page
        // Get search query from URL
        $searchQuery = $this->request->getVar('searchQuery') ?? '';
        $campaignData = $campaign_model->getCampaigns($page, $perPage, $searchQuery);        
        $mainContent = view('showCampaign', [
            'campaign' => $campaignData['campaigns'],
            'totalPages' => $campaignData['totalPages'],
            'currentPage' => $page,
            'searchQuery' => $searchQuery,
        ]);
        return view('Template', ['mainContent' => $mainContent]);
    }




    public function addCampaign()
    {
        if (!$this->session->has('user')) {
            return redirect()->to('/login');
        }

        $mainContent = view('showCampaign');
        return view('Template', ['mainContent' => $mainContent]);
    }




    public function storeCampaign()
    {

        if (!$this->session->has('user')) {
            return redirect()->to('/login');
        }
        $model = new CampaignModel();

        // Validate the input
        $validation = $this->validate([
            'name' => 'required|min_length[3]',
            'description' => 'required',
            'client' => 'required',
        ]);

        if (!$validation) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Please fill in all fields correctly.');
        }

        // Prepare data for insertion
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'client' => $this->request->getPost('client'),
        ];

        // Insert data into the database
        if ($model->insertCampaign($data)) {
            return redirect()
                ->to('showCampaign')
                ->with('success', 'Campaign added successfully.');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to add campaign.');
        }
    }




    public function updateCampaign()
    {
        $campaign_model = new CampaignModel();
        // Get the submitted data
        $id = $this->request->getPost('id');
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $client = $this->request->getPost('client');

        // Prepare data for update in MySQL
        $updatedData = [];
        if ($name) {
            $updatedData['name'] = $name;
        }
        if ($description) {
            $updatedData['description'] = $description;
        }
        if ($client) {
            $updatedData['client'] = $client;
        }

        // Step 1: Update the user in MySQL
        $campaign_model->updateCampaignById($id, $updatedData);
        return redirect()
            ->to('/showCampaign')
            ->with('success', 'user details updated successfully');
    }





    public function deleteCampaign($id)
    {
        $campaign_model = new CampaignModel();
        $campaign_model->deleteCampaignById($id);

        return redirect()
            ->to('/showCampaign')
            ->with('success', 'user deleted successfully');
    }
}
