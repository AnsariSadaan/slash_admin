// To implement the requested features, we need to make some adjustments to the current setup. The objective is to allow admins to create campaigns and assign them to users, while regular users can only view assigned campaigns.

// Here’s how you can achieve this:

// 1. Database Structure
// You'll need a relationship table to manage campaign assignments. A new table, say user_campaign, will be needed. This table will store the user ID and campaign ID, representing the assignments.

// sql
// Copy code
// CREATE TABLE user_campaign (
//     user_id INT NOT NULL,
//     campaign_id INT NOT NULL,
//     PRIMARY KEY (user_id, campaign_id),
//     FOREIGN KEY (user_id) REFERENCES users(id),
//     FOREIGN KEY (campaign_id) REFERENCES campaign(id)
// );
// 2. Campaign Model Changes
// You'll need to fetch campaigns assigned to users, and add functions to handle these assignments in your CampaignModel.

// Add a method to get assigned campaigns for users:

// php
// Copy code
<?php 
    public function getAssignedCampaignsByUser($userId)
    {
    return $this->db->table('campaign')
                    ->join('user_campaign', 'campaign.id = user_campaign.campaign_id')
                    ->where('user_campaign.user_id', $userId)
                    ->get()
                    ->getResult();
    }
?>
<!-- 3. Controller Updates
Modify your Campaign controller to handle campaign assignments for users.

Show Campaigns Based on User Role
In your showCampaign method, check the role of the logged-in user and display the campaigns accordingly.

For Admin:

php
Copy code -->
<?php
public function showCampaign()
{
    if (!$this->session->has('user')) {
        return redirect()->to('/login');
    }

    $user = $this->session->get('user');
    $campaign_model = new CampaignModel();
    
    if ($user->roles === 'admin') {
        $campaignData = $campaign_model->getCampaigns($page, $perPage, $searchQuery);
    } else {
        $campaignData = $campaign_model->getAssignedCampaignsByUser($user->id);
    }

    $mainContent = view('showCampaign', [
        'campaign' => $campaignData,
        'totalPages' => $campaignData['totalPages'] ?? null,
        'currentPage' => $page,
        'searchQuery' => $searchQuery,
    ]);
    
    return view('Template', ['mainContent' => $mainContent]);
}
?>
<!-- Admin Assigning Campaigns
Add functionality for admin users to assign campaigns to users. You can create a form to handle campaign assignments.

php
Copy code -->
<?php
public function assignCampaign()
{
    $userId = $this->request->getPost('user_id');
    $campaignId = $this->request->getPost('campaign_id');
    
    $data = [
        'user_id' => $userId,
        'campaign_id' => $campaignId,
    ];

    $model = new CampaignModel();
    if ($model->db->table('user_campaign')->insert($data)) {
        return redirect()->to('showCampaign')->with('success', 'Campaign assigned successfully');
    }

    return redirect()->back()->with('error', 'Failed to assign campaign');
}
?>
<!-- 4. Frontend Changes
You need to display the campaigns and their assignments in the table view.

Campaign View (Admin & User)
Update the table to display campaigns, as well as assigned users (if logged in as an admin). Modify the view to show the campaigns with details such as user name, campaign name, description, and client.

php
Copy code -->
<table class="min-w-full table-auto border-collapse">
    <thead>
        <tr class="bg-indigo-600 text-white">
            <th class="px-4 py-2 text-center">Campaign ID</th>
            <th class="px-4 py-2 text-center">Assigned User</th>
            <th class="px-4 py-2 text-center">Campaign Name</th>
            <th class="px-4 py-2 text-center">Description</th>
            <th class="px-4 py-2 text-center">Client</th>
            <?php if ($role === 'admin'): ?>
                <th class="px-4 py-2 text-center">Actions</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($campaign as $row): ?>
            <tr class="border-b">
                <td class="px-4 py-2 text-center"><?php echo $row->id; ?></td>
                <td class="px-4 py-2 text-center"><?php echo $row->user_name ?? 'N/A'; ?></td>
                <td class="px-4 py-2 text-center"><?php echo $row->name; ?></td>
                <td class="px-4 py-2 text-center"><?php echo $row->description; ?></td>
                <td class="px-4 py-2 text-center"><?php echo $row->client; ?></td>
                <?php if ($role === 'admin'): ?>
                    <td class="px-4 py-2 text-center">
                        <button class="bg-blue-500 text-white py-1 px-4 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mr-2"
                            onclick="openEditModal(<?php echo $row->id; ?>)">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </button>
                        <button class="bg-red-500 text-white py-1 px-4 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500"
                            onclick="confirmDelete(<?php echo $row->id; ?>)">
                            <i class="fa-solid fa-trash"></i> Delete
                        </button>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<!-- Admin Campaign Assignment Form
Add a form for the admin to assign a campaign to a user.

html
Copy code -->
<form method="POST" action="/assign-campaign">
    <label for="user_id">Select User:</label>
    <select name="user_id" id="user_id">
        <!-- Add user options here -->
    </select>

    <label for="campaign_id">Select Campaign:</label>
    <select name="campaign_id" id="campaign_id">
        <!-- Add campaign options here -->
    </select>

    <button type="submit">Assign Campaign</button>
</form>
<!-- 5. JavaScript for Modal
Handle modals for editing and creating new campaigns. You already have the modal setup for adding and editing campaigns, just ensure the action URLs are correctly pointed to the respective methods (/store for adding and /update-campaign for updating).

javascript
Copy code -->
<script>
function openAddModal() {
    document.getElementById('addModal').classList.remove('hidden');
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
}

function openEditModal(campaignId) {
    // Pre-fill the edit modal with the campaign data
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
<!-- Conclusion
Admin: Can create, update, delete campaigns, and assign them to users.
User: Can only view campaigns assigned to them by the admin.
Ensure that roles are validated in the backend and the correct data is displayed in the front end. You can fine-tune the UI further to display user names assigned to campaigns or provide additional search and pagination functionality. -->


<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserCampaignTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'campaign_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ]);

        $this->forge->addKey(['user_id', 'campaign_id'], true); // Composite primary key

        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('campaign_id', 'campaign', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('user_campaign');
    }

    public function down()
    {
        $this->forge->dropTable('user_campaign');
    }
}


namespace App\Models;

use CodeIgniter\Model;

class UserCampaignModel extends Model
{
    // Specify the table name
    protected $table = 'user_campaign';

    // Specify the primary key (composite key in this case)
    protected $primaryKey = ['user_id', 'campaign_id'];

    // Disable auto-incrementing primary key (since it's a composite key)
    protected $autoIncrement = false;

    // Specify the allowed fields for mass assignment
    protected $allowedFields = ['user_id', 'campaign_id'];

    // Enable timestamps (if you want to track when records are created/updated)
    protected $useTimestamps = false;

    // Validation rules (optional)
    protected $validationRules = [
        'user_id' => 'required|integer',
        'campaign_id' => 'required|integer',
    ];

    // Validation messages (optional)
    protected $validationMessages = [
        'user_id' => [
            'required' => 'The user ID is required.',
            'integer' => 'The user ID must be an integer.',
        ],
        'campaign_id' => [
            'required' => 'The campaign ID is required.',
            'integer' => 'The campaign ID must be an integer.',
        ],
    ];
}