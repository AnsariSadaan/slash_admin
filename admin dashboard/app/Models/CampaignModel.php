<?php

namespace App\Models;

use CodeIgniter\Model;

class CampaignModel extends Model
{
    protected $table = 'campaign';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['name', 'description', 'client'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    //insert campaign
    public function insertCampaign(array $data)
    {
        return $this->insert($data);
    }

    // delete campaign
    public function deleteCampaignById(int $id)
    {
        return $this->delete($id);
    }

    // update campaign
    public function updateCampaignById(int $id, array $data)
    {
        return $this->update($id, $data);
    }



    public function getCampaigns($page, $perPage, $searchQuery = '')
    {
        $offset = ($page - 1) * $perPage;

        if ($searchQuery) {
            return $this->like('name', $searchQuery)
                ->orderBy('id', 'ASC')
                ->findAll($perPage, $offset);
        } else {
            $campaigns = $this->orderBy('id', 'ASC')->findAll(
                $perPage,
                $offset
            );
        }

        $totalCampaigns = $this->countAll();
        $totalPages = ceil($totalCampaigns / $perPage);
        return [
            'campaigns' => $campaigns,
            'totalPages' => $totalPages,
        ];
    }


    public function getAssignedCampaignsByUser($id)
    {
        return $this->db->table('campaign')
            ->join('user_campaign', 'campaign.id = user_campaign.campaign_id')
            ->where('user_campaign.user_id', $id)
            ->get()
            ->getResult();
    }
}
