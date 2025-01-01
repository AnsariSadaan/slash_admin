<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['name', 'email', 'password', 'roles'];
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getAllUsers()
    {
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

    // Get all users (for admin)
    public function getPaginatedUsers($searchQuery, $page, $perPage)
    {
        return $this->db
            ->table('users')
            ->like('name', $searchQuery)
            ->orLike('email', $searchQuery)
            ->limit($perPage, ($page - 1) * $perPage)
            ->get()
            ->getResult();
    }

    // Get users by role 'user' (for regular users)
    public function getUsersByRole($role, $searchQuery, $page, $perPage)
    {
        return $this->db
            ->table('users')
            ->where('roles', $role)
            ->like('name', $searchQuery)
            ->orLike('email', $searchQuery)
            ->limit($perPage, ($page - 1) * $perPage)
            ->get()
            ->getResult();
    }

    // Count all users (for admin)
    public function countUsers($searchQuery)
    {
        return $this->db
            ->table('users')
            ->like('name', $searchQuery)
            ->orLike('email', $searchQuery)
            ->countAllResults();
    }

    // Count users by role 'user' (for regular users)
    public function countUsersByRole($role, $searchQuery)
    {
        return $this->db
            ->table('users')
            ->where('roles', $role)
            ->like('name', $searchQuery)
            ->orLike('email', $searchQuery)
            ->countAllResults();
    }
}
