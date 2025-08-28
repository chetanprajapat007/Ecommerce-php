<?php

namespace App\Models;

class UserModel extends BaseModel
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'name', 'email', 'password_hash', 'contact', 'role', 
        'is_data_entry_allowed', 'status', 'created_by', 'updated_by'
    ];
    
    protected $validationRules = [
        'name'     => 'required|min_length[3]|max_length[255]',
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password_hash' => 'required_with[password]',
        'contact'  => 'permit_empty|min_length[10]|max_length[20]',
        'role'     => 'required|in_list[admin,employee]',
        'status'   => 'required|in_list[active,inactive]',
    ];
    
    protected $validationMessages = [
        'email' => [
            'is_unique' => 'This email is already in use.',
        ],
    ];
    
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];
    
    /**
     * Hash the password if it exists in the data
     */
    protected function hashPassword(array $data)
    {
        if (! isset($data['data']['password'])) {
            return $data;
        }
        
        $data['data']['password_hash'] = password_hash($data['data']['password'], PASSWORD_BCRYPT);
        unset($data['data']['password']);
        
        return $data;
    }
    
    /**
     * Verify if the provided password matches the hash
     */
    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
    
    /**
     * Get all assigned locations for a user
     */
    public function getAssignedLocations(int $userId)
    {
        $locationModel = new UserAssignedLocationModel();
        return $locationModel->where('user_id', $userId)->findAll();
    }
    
    /**
     * Check if a user has access to a specific state
     */
    public function hasAccessToState(int $userId, int $stateId): bool
    {
        $locationModel = new UserAssignedLocationModel();
        return $locationModel->where('user_id', $userId)
                            ->where('state_id', $stateId)
                            ->countAllResults() > 0;
    }
    
    /**
     * Check if a user has access to a specific city
     */
    public function hasAccessToCity(int $userId, int $cityId): bool
    {
        $locationModel = new UserAssignedLocationModel();
        return $locationModel->where('user_id', $userId)
                            ->where('city_id', $cityId)
                            ->countAllResults() > 0;
    }
    
    /**
     * Get all states assigned to a user
     */
    public function getAssignedStates(int $userId)
    {
        $locationModel = new UserAssignedLocationModel();
        $stateIds = $locationModel->select('DISTINCT(state_id)')
                                 ->where('user_id', $userId)
                                 ->findAll();
        
        if (empty($stateIds)) {
            return [];
        }
        
        $stateModel = new StateModel();
        return $stateModel->whereIn('id', array_column($stateIds, 'state_id'))->findAll();
    }
    
    /**
     * Get all cities assigned to a user
     */
    public function getAssignedCities(int $userId)
    {
        $locationModel = new UserAssignedLocationModel();
        $cityIds = $locationModel->select('DISTINCT(city_id)')
                                ->where('user_id', $userId)
                                ->where('city_id IS NOT NULL')
                                ->findAll();
        
        if (empty($cityIds)) {
            return [];
        }
        
        $cityModel = new CityModel();
        return $cityModel->whereIn('id', array_column($cityIds, 'city_id'))->findAll();
    }
}

