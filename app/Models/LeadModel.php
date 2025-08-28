<?php

namespace App\Models;

class LeadModel extends BaseModel
{
    protected $table = 'leads';
    protected $allowedFields = [
        'company_name', 'email', 'contact_number', 'address', 'state_id', 'city_id',
        'status', 'call_attempt_count', 'created_by', 'created_at', 'updated_by', 'updated_at'
    ];
    
    protected $validationRules = [
        'state_id' => 'required|numeric|is_not_unique[states.id]',
        'status' => 'required|in_list[new,followup,na,dead,interested,win]'
    ];
    
    /**
     * Get leads with details
     *
     * @param int $limit
     * @param int $offset
     * @param array $filters
     * @return array
     */
    public function getLeadsWithDetails($limit = 0, $offset = 0, $filters = [])
    {
        $builder = $this->select('leads.*, states.name as state_name, cities.name as city_name')
            ->join('states', 'states.id = leads.state_id', 'left')
            ->join('cities', 'cities.id = leads.city_id', 'left');
        
        // Apply filters
        if (!empty($filters)) {
            // Status filter
            if (isset($filters['status'])) {
                $builder->where('leads.status', $filters['status']);
            }
            
            // State filter
            if (isset($filters['state_id'])) {
                $builder->where('leads.state_id', $filters['state_id']);
            }
            
            // City filter
            if (isset($filters['city_id'])) {
                $builder->where('leads.city_id', $filters['city_id']);
            }
            
            // Employee filter (based on assigned locations)
            if (isset($filters['employee_id'])) {
                $userAssignedLocationModel = new UserAssignedLocationModel();
                $assignedLocations = $userAssignedLocationModel->where('user_id', $filters['employee_id'])->findAll();
                
                if (!empty($assignedLocations)) {
                    $builder->groupStart();
                    
                    foreach ($assignedLocations as $location) {
                        $builder->orGroupStart()
                            ->where('leads.state_id', $location['state_id']);
                        
                        if ($location['city_id']) {
                            $builder->where('leads.city_id', $location['city_id']);
                        }
                        
                        $builder->groupEnd();
                    }
                    
                    $builder->groupEnd();
                }
            }
            
            // Date range filter
            if (isset($filters['date_range'])) {
                if (!empty($filters['date_range']['start'])) {
                    $builder->where('leads.created_at >=', $filters['date_range']['start'] . ' 00:00:00');
                }
                
                if (!empty($filters['date_range']['end'])) {
                    $builder->where('leads.created_at <=', $filters['date_range']['end'] . ' 23:59:59');
                }
            }
        }
        
        // Apply limit and offset
        if ($limit > 0) {
            $builder->limit($limit, $offset);
        }
        
        // Order by ID descending
        $builder->orderBy('leads.id', 'DESC');
        
        $leads = $builder->findAll();
        
        // Add audit information
        foreach ($leads as &$lead) {
            $lead['created_by_name'] = $this->getCreatedByName($lead['created_by']);
            
            if (!empty($lead['updated_by'])) {
                $lead['updated_by_name'] = $this->getUpdatedByName($lead['updated_by']);
            }
        }
        
        return $leads;
    }
    
    /**
     * Get a single lead with details
     *
     * @param int $id
     * @return array|null
     */
    public function getLeadWithDetails($id)
    {
        $lead = $this->select('leads.*, states.name as state_name, cities.name as city_name')
            ->join('states', 'states.id = leads.state_id', 'left')
            ->join('cities', 'cities.id = leads.city_id', 'left')
            ->where('leads.id', $id)
            ->first();
        
        if (!$lead) {
            return null;
        }
        
        // Add audit information
        $lead['created_by_name'] = $this->getCreatedByName($lead['created_by']);
        
        if (!empty($lead['updated_by'])) {
            $lead['updated_by_name'] = $this->getUpdatedByName($lead['updated_by']);
        }
        
        // Get call logs
        $callLogModel = new CallLogModel();
        $lead['call_logs'] = $callLogModel->getCallLogsByLead($id);
        
        return $lead;
    }
    
    /**
     * Get lead counts by status
     *
     * @param array $filters
     * @return array
     */
    public function getLeadCountsByStatus($filters = [])
    {
        $statuses = ['new', 'followup', 'na', 'dead', 'interested', 'win'];
        $counts = [];
        
        foreach ($statuses as $status) {
            $builder = $this->where('status', $status);
            
            // Apply filters
            if (!empty($filters)) {
                // Employee filter (based on assigned locations)
                if (isset($filters['employee_id'])) {
                    $userAssignedLocationModel = new UserAssignedLocationModel();
                    $assignedLocations = $userAssignedLocationModel->where('user_id', $filters['employee_id'])->findAll();
                    
                    if (!empty($assignedLocations)) {
                        $builder->groupStart();
                        
                        foreach ($assignedLocations as $location) {
                            $builder->orGroupStart()
                                ->where('state_id', $location['state_id']);
                            
                            if ($location['city_id']) {
                                $builder->where('city_id', $location['city_id']);
                            }
                            
                            $builder->groupEnd();
                        }
                        
                        $builder->groupEnd();
                    }
                }
                
                // Date range filter
                if (isset($filters['date_range'])) {
                    if (!empty($filters['date_range']['start'])) {
                        $builder->where('created_at >=', $filters['date_range']['start'] . ' 00:00:00');
                    }
                    
                    if (!empty($filters['date_range']['end'])) {
                        $builder->where('created_at <=', $filters['date_range']['end'] . ' 23:59:59');
                    }
                }
            }
            
            $counts[$status] = $builder->countAllResults();
        }
        
        return $counts;
    }
}

