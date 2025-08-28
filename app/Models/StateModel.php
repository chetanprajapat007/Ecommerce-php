<?php

namespace App\Models;

class StateModel extends BaseModel
{
    protected $table = 'states';
    protected $allowedFields = [
        'name', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at'
    ];
    
    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]|is_unique[states.name,id,{id}]',
        'status' => 'required|in_list[active,inactive]'
    ];
    
    /**
     * Get all states with audit information
     *
     * @return array
     */
    public function getAllStatesWithAudit()
    {
        $states = $this->findAll();
        
        foreach ($states as &$state) {
            $state['created_by_name'] = $this->getCreatedByName($state['created_by']);
            
            if (!empty($state['updated_by'])) {
                $state['updated_by_name'] = $this->getUpdatedByName($state['updated_by']);
            }
        }
        
        return $states;
    }
    
    /**
     * Get active states
     *
     * @return array
     */
    public function getActiveStates()
    {
        return $this->where('status', 'active')->findAll();
    }
    
    /**
     * Get cities for a state
     *
     * @param int $stateId
     * @return array
     */
    public function getCities($stateId)
    {
        $cityModel = new CityModel();
        return $cityModel->where('state_id', $stateId)->findAll();
    }
}

