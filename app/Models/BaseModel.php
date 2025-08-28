<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseModel extends Model
{
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    protected $skipValidation = false;
    
    /**
     * Get the name of the user who created the record
     *
     * @param int $userId
     * @return string
     */
    protected function getCreatedByName($userId)
    {
        $userModel = new UserModel();
        $user = $userModel->find($userId);
        
        return $user ? $user['name'] : 'Unknown';
    }
    
    /**
     * Get the name of the user who updated the record
     *
     * @param int $userId
     * @return string
     */
    protected function getUpdatedByName($userId)
    {
        if (!$userId) {
            return null;
        }
        
        $userModel = new UserModel();
        $user = $userModel->find($userId);
        
        return $user ? $user['name'] : 'Unknown';
    }
}

