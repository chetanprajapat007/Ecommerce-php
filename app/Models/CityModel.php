<?php

namespace App\Models;

class CityModel extends BaseModel
{
    protected $table         = 'cities';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'state_id', 'name', 'status', 'created_by', 'updated_by'
    ];
    
    protected $validationRules = [
        'state_id' => 'required|numeric|is_not_unique[states.id]',
        'name'     => 'required|min_length[2]|max_length[100]',
        'status'   => 'required|in_list[active,inactive]',
    ];
    
    protected $validationMessages = [
        'state_id' => [
            'is_not_unique' => 'The selected state does not exist.',
        ],
    ];
    
    /**
     * Get all active cities
     */
    public function getActiveCities()
    {
        return $this->where('status', 'active')->findAll();
    }
    
    /**
     * Get all active cities for a specific state
     */
    public function getActiveCitiesByState(int $stateId)
    {
        return $this->where('state_id', $stateId)
                   ->where('status', 'active')
                   ->findAll();
    }
    
    /**
     * Get city with state, creator and updater information
     */
    public function getCityWithDetails(int $cityId)
    {
        $city = $this->find($cityId);
        
        if (!$city) {
            return null;
        }
        
        $stateModel = new StateModel();
        $userModel = new UserModel();
        
        $state = $stateModel->find($city['state_id']);
        $city['state_name'] = $state ? $state['name'] : 'Unknown';
        
        if (!empty($city['created_by'])) {
            $creator = $userModel->find($city['created_by']);
            $city['created_by_name'] = $creator ? $creator['name'] : 'Unknown';
        } else {
            $city['created_by_name'] = 'System';
        }
        
        if (!empty($city['updated_by'])) {
            $updater = $userModel->find($city['updated_by']);
            $city['updated_by_name'] = $updater ? $updater['name'] : 'Unknown';
        } else {
            $city['updated_by_name'] = 'N/A';
        }
        
        return $city;
    }
    
    /**
     * Get all cities with state, creator and updater information
     */
    public function getAllCitiesWithDetails()
    {
        $cities = $this->findAll();
        $stateModel = new StateModel();
        $userModel = new UserModel();
        
        // Get all states in one query for efficiency
        $states = [];
        $stateIds = array_unique(array_column($cities, 'state_id'));
        $stateResults = $stateModel->whereIn('id', $stateIds)->findAll();
        foreach ($stateResults as $state) {
            $states[$state['id']] = $state;
        }
        
        // Get all users in one query for efficiency
        $users = [];
        $userIds = array_merge(
            array_filter(array_column($cities, 'created_by')),
            array_filter(array_column($cities, 'updated_by'))
        );
        $userIds = array_unique($userIds);
        if (!empty($userIds)) {
            $userResults = $userModel->whereIn('id', $userIds)->findAll();
            foreach ($userResults as $user) {
                $users[$user['id']] = $user;
            }
        }
        
        // Add details to each city
        foreach ($cities as &$city) {
            $city['state_name'] = isset($states[$city['state_id']]) ? $states[$city['state_id']]['name'] : 'Unknown';
            
            if (!empty($city['created_by']) && isset($users[$city['created_by']])) {
                $city['created_by_name'] = $users[$city['created_by']]['name'];
            } else {
                $city['created_by_name'] = 'System';
            }
            
            if (!empty($city['updated_by']) && isset($users[$city['updated_by']])) {
                $city['updated_by_name'] = $users[$city['updated_by']]['name'];
            } else {
                $city['updated_by_name'] = 'N/A';
            }
        }
        
        return $cities;
    }
}

