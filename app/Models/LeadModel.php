<?php

namespace App\Models;

class LeadModel extends BaseModel
{
    protected $table         = 'leads';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'company_name', 'email', 'contact_number', 'address', 
        'state_id', 'city_id', 'status', 'call_attempt_count',
        'created_by', 'updated_by'
    ];
    
    protected $validationRules = [
        'state_id'       => 'required|numeric|is_not_unique[states.id]',
        'city_id'        => 'permit_empty|numeric|is_not_unique[cities.id,id,{id}]',
        'company_name'   => 'permit_empty|max_length[255]',
        'email'          => 'permit_empty|valid_email|max_length[255]',
        'contact_number' => 'permit_empty|max_length[20]',
        'status'         => 'required|in_list[new,followup,na,dead,interested,win]',
    ];
    
    /**
     * Get all leads with state, city, creator and updater information
     */
    public function getLeadsWithDetails($limit = null, $offset = null, $filters = [])
    {
        $builder = $this->builder();
        
        $builder->select('
            leads.*,
            states.name as state_name,
            cities.name as city_name,
            creator.name as created_by_name,
            updater.name as updated_by_name
        ');
        
        $builder->join('states', 'states.id = leads.state_id', 'left');
        $builder->join('cities', 'cities.id = leads.city_id', 'left');
        $builder->join('users as creator', 'creator.id = leads.created_by', 'left');
        $builder->join('users as updater', 'updater.id = leads.updated_by', 'left');
        
        // Apply filters
        if (!empty($filters)) {
            if (isset($filters['state_id']) && !empty($filters['state_id'])) {
                if (is_array($filters['state_id'])) {
                    $builder->whereIn('leads.state_id', $filters['state_id']);
                } else {
                    $builder->where('leads.state_id', $filters['state_id']);
                }
            }
            
            if (isset($filters['city_id']) && !empty($filters['city_id'])) {
                if (is_array($filters['city_id'])) {
                    $builder->whereIn('leads.city_id', $filters['city_id']);
                } else {
                    $builder->where('leads.city_id', $filters['city_id']);
                }
            }
            
            if (isset($filters['status']) && !empty($filters['status'])) {
                if (is_array($filters['status'])) {
                    $builder->whereIn('leads.status', $filters['status']);
                } else {
                    $builder->where('leads.status', $filters['status']);
                }
            }
            
            if (isset($filters['employee_id']) && !empty($filters['employee_id'])) {
                // Get all states and cities assigned to this employee
                $locationModel = new UserAssignedLocationModel();
                $assignedLocations = $locationModel->where('user_id', $filters['employee_id'])->findAll();
                
                if (!empty($assignedLocations)) {
                    $stateIds = [];
                    $cityIds = [];
                    
                    foreach ($assignedLocations as $location) {
                        $stateIds[] = $location['state_id'];
                        if (!empty($location['city_id'])) {
                            $cityIds[] = $location['city_id'];
                        }
                    }
                    
                    $stateIds = array_unique($stateIds);
                    $cityIds = array_unique($cityIds);
                    
                    if (!empty($cityIds)) {
                        $builder->groupStart()
                                ->whereIn('leads.state_id', $stateIds)
                                ->groupStart()
                                    ->whereIn('leads.city_id', $cityIds)
                                    ->orWhere('leads.city_id IS NULL')
                                ->groupEnd()
                                ->groupEnd();
                    } else {
                        $builder->whereIn('leads.state_id', $stateIds);
                    }
                } else {
                    // No locations assigned, return no results
                    $builder->where('leads.id', 0);
                }
            }
            
            if (isset($filters['search']) && !empty($filters['search'])) {
                $search = $filters['search'];
                $builder->groupStart()
                        ->like('leads.company_name', $search)
                        ->orLike('leads.email', $search)
                        ->orLike('leads.contact_number', $search)
                        ->orLike('leads.address', $search)
                        ->orLike('states.name', $search)
                        ->orLike('cities.name', $search)
                        ->groupEnd();
            }
            
            if (isset($filters['date_range']) && !empty($filters['date_range'])) {
                if (isset($filters['date_range']['start']) && !empty($filters['date_range']['start'])) {
                    $builder->where('leads.created_at >=', $filters['date_range']['start'] . ' 00:00:00');
                }
                
                if (isset($filters['date_range']['end']) && !empty($filters['date_range']['end'])) {
                    $builder->where('leads.created_at <=', $filters['date_range']['end'] . ' 23:59:59');
                }
            }
        }
        
        // Apply limit and offset
        if ($limit !== null) {
            $builder->limit($limit, $offset);
        }
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Count all leads with filters
     */
    public function countLeadsWithFilters($filters = [])
    {
        $builder = $this->builder();
        
        // Apply filters
        if (!empty($filters)) {
            if (isset($filters['state_id']) && !empty($filters['state_id'])) {
                if (is_array($filters['state_id'])) {
                    $builder->whereIn('state_id', $filters['state_id']);
                } else {
                    $builder->where('state_id', $filters['state_id']);
                }
            }
            
            if (isset($filters['city_id']) && !empty($filters['city_id'])) {
                if (is_array($filters['city_id'])) {
                    $builder->whereIn('city_id', $filters['city_id']);
                } else {
                    $builder->where('city_id', $filters['city_id']);
                }
            }
            
            if (isset($filters['status']) && !empty($filters['status'])) {
                if (is_array($filters['status'])) {
                    $builder->whereIn('status', $filters['status']);
                } else {
                    $builder->where('status', $filters['status']);
                }
            }
            
            if (isset($filters['employee_id']) && !empty($filters['employee_id'])) {
                // Get all states and cities assigned to this employee
                $locationModel = new UserAssignedLocationModel();
                $assignedLocations = $locationModel->where('user_id', $filters['employee_id'])->findAll();
                
                if (!empty($assignedLocations)) {
                    $stateIds = [];
                    $cityIds = [];
                    
                    foreach ($assignedLocations as $location) {
                        $stateIds[] = $location['state_id'];
                        if (!empty($location['city_id'])) {
                            $cityIds[] = $location['city_id'];
                        }
                    }
                    
                    $stateIds = array_unique($stateIds);
                    $cityIds = array_unique($cityIds);
                    
                    if (!empty($cityIds)) {
                        $builder->groupStart()
                                ->whereIn('state_id', $stateIds)
                                ->groupStart()
                                    ->whereIn('city_id', $cityIds)
                                    ->orWhere('city_id IS NULL')
                                ->groupEnd()
                                ->groupEnd();
                    } else {
                        $builder->whereIn('state_id', $stateIds);
                    }
                } else {
                    // No locations assigned, return no results
                    $builder->where('id', 0);
                }
            }
            
            if (isset($filters['search']) && !empty($filters['search'])) {
                $search = $filters['search'];
                $builder->groupStart()
                        ->like('company_name', $search)
                        ->orLike('email', $search)
                        ->orLike('contact_number', $search)
                        ->orLike('address', $search)
                        ->groupEnd();
            }
            
            if (isset($filters['date_range']) && !empty($filters['date_range'])) {
                if (isset($filters['date_range']['start']) && !empty($filters['date_range']['start'])) {
                    $builder->where('created_at >=', $filters['date_range']['start'] . ' 00:00:00');
                }
                
                if (isset($filters['date_range']['end']) && !empty($filters['date_range']['end'])) {
                    $builder->where('created_at <=', $filters['date_range']['end'] . ' 23:59:59');
                }
            }
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Get lead counts by status
     */
    public function getLeadCountsByStatus($filters = [])
    {
        $statuses = ['new', 'followup', 'na', 'dead', 'interested', 'win'];
        $counts = [];
        
        foreach ($statuses as $status) {
            $filters['status'] = $status;
            $counts[$status] = $this->countLeadsWithFilters($filters);
        }
        
        return $counts;
    }
    
    /**
     * Increment call attempt count
     */
    public function incrementCallAttemptCount(int $leadId)
    {
        $lead = $this->find($leadId);
        
        if (!$lead) {
            return false;
        }
        
        $count = $lead['call_attempt_count'] + 1;
        
        return $this->update($leadId, [
            'call_attempt_count' => $count
        ]);
    }
    
    /**
     * Update lead status and log the call
     */
    public function updateStatusAndLogCall(int $leadId, string $newStatus, string $remark, $followUpDateTime = null)
    {
        $lead = $this->find($leadId);
        
        if (!$lead) {
            return false;
        }
        
        $oldStatus = $lead['status'];
        
        // Start a transaction
        $this->db->transStart();
        
        // Update the lead status
        $this->update($leadId, [
            'status' => $newStatus
        ]);
        
        // Increment call attempt count
        $this->incrementCallAttemptCount($leadId);
        
        // Log the call
        $callLogModel = new CallLogModel();
        $callLogModel->insert([
            'lead_id' => $leadId,
            'user_id' => session()->get('user_id'),
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'remark' => $remark,
            'follow_up_date_time' => $followUpDateTime,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        // Complete the transaction
        $this->db->transComplete();
        
        return $this->db->transStatus();
    }
}

