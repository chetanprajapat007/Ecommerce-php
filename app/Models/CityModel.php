<?php

namespace App\Models;

class CityModel extends BaseModel
{
    protected $table = 'cities';
    protected $allowedFields = [
        'state_id', 'name', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at'
    ];
    
    protected $validationRules = [
        'state_id' => 'required|numeric|is_not_unique[states.id]',
        'name' => 'required|min_length[2]|max_length[100]',
        'status' => 'required|in_list[active,inactive]'
    ];
    
    /**
     * Get all cities with state information and audit details
     *
     * @return array
     */
    public function getAllCitiesWithDetails()
    {
        $cities = $this->select('cities.*, states.name as state_name')
            ->join('states', 'states.id = cities.state_id')
            ->findAll();
        
        foreach ($cities as &$city) {
            $city['created_by_name'] = $this->getCreatedByName($city['created_by']);
            
            if (!empty($city['updated_by'])) {
                $city['updated_by_name'] = $this->getUpdatedByName($city['updated_by']);
            }
        }
        
        return $cities;
    }
    
    /**
     * Get active cities for a state
     *
     * @param int $stateId
     * @return array
     */
    public function getActiveCitiesByState($stateId)
    {
        return $this->select('cities.*, states.name as state_name')
            ->join('states', 'states.id = cities.state_id')
            ->where('cities.state_id', $stateId)
            ->where('cities.status', 'active')
            ->findAll();
    }
    
    /**
     * Get active cities for multiple states
     *
     * @param array $stateIds
     * @return array
     */
    public function getActiveCitiesByStates($stateIds)
    {
        if (empty($stateIds)) {
            return [];
        }
        
        return $this->select('cities.*, states.name as state_name')
            ->join('states', 'states.id = cities.state_id')
            ->whereIn('cities.state_id', $stateIds)
            ->where('cities.status', 'active')
            ->findAll();
    }
}

