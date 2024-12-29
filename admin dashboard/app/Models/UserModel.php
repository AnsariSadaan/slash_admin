<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'email', 'password', 'roles'];
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';


    public function getAllUsers(){
        return $this->findAll();
    }
    /**
     * Check if a user exists with the given email.
     */
    public function getUserByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Save a new user to the database.
     */
    public function saveUser(array $data)
    {
        return $this->insert($data);
    }


    public function updateUserById(int $id, array $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Delete user by ID.
     */
    public function deleteUserById(int $id)
    {
        return $this->delete($id);
    }


    public function getPaginatedUsers(string $searchQuery = '', int $page = 1, int $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;

        if ($searchQuery) {
            return $this->like('name', $searchQuery)
                ->orderBy('id', 'ASC')
                ->findAll($perPage, $offset);
        }

        return $this->orderBy('id', 'ASC')
            ->findAll($perPage, $offset);
    }

    /**
     * Count users with optional search query.
     */
    public function countUsers(string $searchQuery = '')
    {
        if ($searchQuery) {
            return $this->like('name', $searchQuery)->countAllResults();
        }

        return $this->countAll();
    }
}
