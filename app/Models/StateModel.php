<?php

namespace App\Models;

class StateModel extends BaseModel
{
    protected $table         = 'states';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'name', 'status', 'created_by', 'updated_by'
    ];
    
    protected $validationRules = [
        'name'   => 'required|min_length[2]|max_length[100]|is_unique[states.name,id,{id}]',
        'status' => 'required|in_list[active,inactive]',
    ];
    
    protected $validationMessages = [
        'name' => [
            'is_unique' => 'This state name already exists.',
        ],
    ];
    
    /**
     * Get all cities belonging to this state
     */
    public function getCities(int $stateId)
    {
        $cityModel = new CityModel();
        return $cityModel->where('state_id', $stateId)->findAll();
    }
    
    /**
     * Get all active states
     */
    public function getActiveStates()
    {
        return $this->where('status', 'active')->findAll();
    }
    
    /**
     * Get state with creator and updater information
     */
    public function getStateWithAudit(int $stateId)
    {
        $state = $this->find($stateId);
        
        if (!$state) {
            return null;
        }
        
        $userModel = new UserModel();
        
        if (!empty($state['created_by'])) {
            $creator = $userModel->find($state['created_by']);
            $state['created_by_name'] = $creator ? $creator['name'] : 'Unknown';
        } else {
            $state['created_by_name'] = 'System';
        }
        
        if (!empty($state['updated_by'])) {
            $updater = $userModel->find($state['updated_by']);
            $state['updated_by_name'] = $updater ? $updater['name'] : 'Unknown';
        } else {
            $state['updated_by_name'] = 'N/A';
        }
        
        return $state;
    }
    
    /**
     * Get all states with creator and updater information
     */
    public function getAllStatesWithAudit()
    {
        $states = $this->findAll();
        $userModel = new UserModel();
        
        foreach ($states as &$state) {
            if (!empty($state['created_by'])) {
                $creator = $userModel->find($state['created_by']);
                $state['created_by_name'] = $creator ? $creator['name'] : 'Unknown';
            } else {
                $state['created_by_name'] = 'System';
            }
            
            if (!empty($state['updated_by'])) {
                $updater = $userModel->find($state['updated_by']);
                $state['updated_by_name'] = $updater ? $updater['name'] : 'Unknown';
            } else {
                $state['updated_by_name'] = 'N/A';
            }
        }
        
        return $states;
    }
}

