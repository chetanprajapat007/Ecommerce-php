<?php

namespace App\Models;

class UserAssignedLocationModel extends BaseModel
{
    protected $table = 'user_assigned_locations';
    protected $allowedFields = [
        'user_id', 'state_id', 'city_id'
    ];
    
    protected $validationRules = [
        'user_id' => 'required|numeric|is_not_unique[users.id]',
        'state_id' => 'required|numeric|is_not_unique[states.id]',
        'city_id' => 'required|numeric|is_not_unique[cities.id]'
    ];
    
    /**
     * Get assigned locations with state and city details
     *
     * @param int $userId
     * @return array
     */
    public function getAssignedLocationsWithDetails($userId)
    {
        return $this->select('user_assigned_locations.*, states.name as state_name, cities.name as city_name')
            ->join('states', 'states.id = user_assigned_locations.state_id')
            ->join('cities', 'cities.id = user_assigned_locations.city_id')
            ->where('user_assigned_locations.user_id', $userId)
            ->findAll();
    }
    
    /**
     * Get assigned states for a user
     *
     * @param int $userId
     * @return array
     */
    public function getAssignedStates($userId)
    {
        $locations = $this->where('user_id', $userId)->findAll();
        
        $stateIds = [];
        
        foreach ($locations as $location) {
            if (!in_array($location['state_id'], $stateIds)) {
                $stateIds[] = $location['state_id'];
            }
        }
        
        return $stateIds;
    }
    
    /**
     * Get assigned cities for a user
     *
     * @param int $userId
     * @return array
     */
    public function getAssignedCities($userId)
    {
        $locations = $this->where('user_id', $userId)->findAll();
        
        $cityIds = [];
        
        foreach ($locations as $location) {
            $cityIds[] = $location['city_id'];
        }
        
        return $cityIds;
    }
}

