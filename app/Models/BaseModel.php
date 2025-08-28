<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseModel extends Model
{
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $useSoftDeletes = false;
    
    // Fields for audit trail
    protected $createdByField = 'created_by';
    protected $updatedByField = 'updated_by';
    
    // Whether this model has audit trail fields
    protected $hasAuditTrail = true;
    
    /**
     * Override the insert method to include created_by
     */
    public function insert($data = null, bool $returnID = true)
    {
        if ($this->hasAuditTrail && isset($this->createdByField) && !isset($data[$this->createdByField])) {
            $data[$this->createdByField] = session()->get('user_id') ?? null;
        }
        
        return parent::insert($data, $returnID);
    }
    
    /**
     * Override the update method to include updated_by
     */
    public function update($id = null, $data = null): bool
    {
        if ($this->hasAuditTrail && isset($this->updatedByField) && !isset($data[$this->updatedByField])) {
            $data[$this->updatedByField] = session()->get('user_id') ?? null;
        }
        
        return parent::update($id, $data);
    }
    
    /**
     * Get the user who created the record
     */
    public function getCreatedByUser($id)
    {
        $data = $this->find($id);
        if (!$data || !isset($data[$this->createdByField]) || !$data[$this->createdByField]) {
            return null;
        }
        
        $userModel = new UserModel();
        return $userModel->find($data[$this->createdByField]);
    }
    
    /**
     * Get the user who last updated the record
     */
    public function getUpdatedByUser($id)
    {
        $data = $this->find($id);
        if (!$data || !isset($data[$this->updatedByField]) || !$data[$this->updatedByField]) {
            return null;
        }
        
        $userModel = new UserModel();
        return $userModel->find($data[$this->updatedByField]);
    }
}

