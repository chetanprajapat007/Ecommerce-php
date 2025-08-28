<?php

namespace App\Models;

class UserAssignedLocationModel extends BaseModel
{
    protected $table         = 'user_assigned_locations';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'user_id', 'state_id', 'city_id'
    ];
    
    // This model doesn't have created_by and updated_by fields
    protected $hasAuditTrail = false;
    
    protected $validationRules = [
        'user_id'  => 'required|numeric|is_not_unique[users.id]',
        'state_id' => 'required|numeric|is_not_unique[states.id]',
        'city_id'  => 'permit_empty|numeric|is_not_unique[cities.id,id,{id}]',
    ];
    
    /**
     * Assign a state to a user
     */
    public function assignStateToUser(int $userId, int $stateId)
    {
        // Check if assignment already exists
        $existing = $this->where('user_id', $userId)
                        ->where('state_id', $stateId)
                        ->where('city_id IS NULL')
                        ->first();
        
        if ($existing) {
            return true; // Already assigned
        }
        
        return $this->insert([
            'user_id'  => $userId,
            'state_id' => $stateId,
            'city_id'  => null
        ]);
    }
    
    /**
     * Assign a city to a user
     */
    public function assignCityToUser(int $userId, int $cityId)
    {
        // Get the state_id for this city
        $cityModel = new CityModel();
        $city = $cityModel->find($cityId);
        
        if (!$city) {
            return false;
        }
        
        // Check if assignment already exists
        $existing = $this->where('user_id', $userId)
                        ->where('state_id', $city['state_id'])
                        ->where('city_id', $cityId)
                        ->first();
        
        if ($existing) {
            return true; // Already assigned
        }
        
        return $this->insert([
            'user_id'  => $userId,
            'state_id' => $city['state_id'],
            'city_id'  => $cityId
        ]);
    }
    
    /**
     * Remove all location assignments for a user
     */
    public function removeAllAssignmentsForUser(int $userId)
    {
        return $this->where('user_id', $userId)->delete();
    }
    
    /**
     * Get all users assigned to a specific state
     */
    public function getUsersByState(int $stateId)
    {
        $userIds = $this->select('DISTINCT(user_id)')
                       ->where('state_id', $stateId)
                       ->findAll();
        
        if (empty($userIds)) {
            return [];
        }
        
        $userModel = new UserModel();
        return $userModel->whereIn('id', array_column($userIds, 'user_id'))->findAll();
    }
    
    /**
     * Get all users assigned to a specific city
     */
    public function getUsersByCity(int $cityId)
    {
        $userIds = $this->select('DISTINCT(user_id)')
                       ->where('city_id', $cityId)
                       ->findAll();
        
        if (empty($userIds)) {
            return [];
        }
        
        $userModel = new UserModel();
        return $userModel->whereIn('id', array_column($userIds, 'user_id'))->findAll();
    }
}

