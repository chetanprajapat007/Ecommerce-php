<?php

namespace App\Models;

class CallLogModel extends BaseModel
{
    protected $table         = 'call_logs';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'lead_id', 'user_id', 'old_status', 'new_status', 
        'remark', 'follow_up_date_time', 'created_at'
    ];
    
    // This model doesn't have created_by, updated_by, and updated_at fields
    protected $hasAuditTrail = false;
    protected $useTimestamps = false;
    
    protected $validationRules = [
        'lead_id'    => 'required|numeric|is_not_unique[leads.id]',
        'user_id'    => 'required|numeric|is_not_unique[users.id]',
        'new_status' => 'required|in_list[new,followup,na,dead,interested,win]',
        'remark'     => 'required',
    ];
    
    /**
     * Get all call logs for a specific lead with user information
     */
    public function getCallLogsForLead(int $leadId)
    {
        $builder = $this->builder();
        
        $builder->select('
            call_logs.*,
            users.name as user_name
        ');
        
        $builder->join('users', 'users.id = call_logs.user_id', 'left');
        $builder->where('call_logs.lead_id', $leadId);
        $builder->orderBy('call_logs.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get upcoming follow-ups
     */
    public function getUpcomingFollowUps($filters = [], $limit = null, $offset = null)
    {
        $builder = $this->builder('call_logs cl');
        
        $builder->select('
            cl.*,
            l.company_name,
            l.email,
            l.contact_number,
            l.state_id,
            l.city_id,
            s.name as state_name,
            c.name as city_name,
            u.name as user_name
        ');
        
        $builder->join('leads l', 'l.id = cl.lead_id');
        $builder->join('states s', 's.id = l.state_id', 'left');
        $builder->join('cities c', 'c.id = l.city_id', 'left');
        $builder->join('users u', 'u.id = cl.user_id', 'left');
        
        $builder->where('cl.new_status', 'followup');
        $builder->where('cl.follow_up_date_time IS NOT NULL');
        
        // Apply filters
        if (!empty($filters)) {
            if (isset($filters['employee_id']) && !empty($filters['employee_id'])) {
                $builder->where('cl.user_id', $filters['employee_id']);
            }
            
            if (isset($filters['date_range']) && !empty($filters['date_range'])) {
                if (isset($filters['date_range']['start']) && !empty($filters['date_range']['start'])) {
                    $builder->where('cl.follow_up_date_time >=', $filters['date_range']['start'] . ' 00:00:00');
                }
                
                if (isset($filters['date_range']['end']) && !empty($filters['date_range']['end'])) {
                    $builder->where('cl.follow_up_date_time <=', $filters['date_range']['end'] . ' 23:59:59');
                }
            } else {
                // Default to today and future follow-ups
                $builder->where('cl.follow_up_date_time >=', date('Y-m-d') . ' 00:00:00');
            }
            
            if (isset($filters['state_id']) && !empty($filters['state_id'])) {
                if (is_array($filters['state_id'])) {
                    $builder->whereIn('l.state_id', $filters['state_id']);
                } else {
                    $builder->where('l.state_id', $filters['state_id']);
                }
            }
            
            if (isset($filters['city_id']) && !empty($filters['city_id'])) {
                if (is_array($filters['city_id'])) {
                    $builder->whereIn('l.city_id', $filters['city_id']);
                } else {
                    $builder->where('l.city_id', $filters['city_id']);
                }
            }
        } else {
            // Default to today and future follow-ups
            $builder->where('cl.follow_up_date_time >=', date('Y-m-d') . ' 00:00:00');
        }
        
        // Get the latest follow-up for each lead
        $builder->orderBy('cl.follow_up_date_time', 'ASC');
        
        // Apply limit and offset
        if ($limit !== null) {
            $builder->limit($limit, $offset);
        }
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Count upcoming follow-ups
     */
    public function countUpcomingFollowUps($filters = [])
    {
        $builder = $this->builder('call_logs cl');
        
        $builder->join('leads l', 'l.id = cl.lead_id');
        
        $builder->where('cl.new_status', 'followup');
        $builder->where('cl.follow_up_date_time IS NOT NULL');
        
        // Apply filters
        if (!empty($filters)) {
            if (isset($filters['employee_id']) && !empty($filters['employee_id'])) {
                $builder->where('cl.user_id', $filters['employee_id']);
            }
            
            if (isset($filters['date_range']) && !empty($filters['date_range'])) {
                if (isset($filters['date_range']['start']) && !empty($filters['date_range']['start'])) {
                    $builder->where('cl.follow_up_date_time >=', $filters['date_range']['start'] . ' 00:00:00');
                }
                
                if (isset($filters['date_range']['end']) && !empty($filters['date_range']['end'])) {
                    $builder->where('cl.follow_up_date_time <=', $filters['date_range']['end'] . ' 23:59:59');
                }
            } else {
                // Default to today and future follow-ups
                $builder->where('cl.follow_up_date_time >=', date('Y-m-d') . ' 00:00:00');
            }
            
            if (isset($filters['state_id']) && !empty($filters['state_id'])) {
                if (is_array($filters['state_id'])) {
                    $builder->whereIn('l.state_id', $filters['state_id']);
                } else {
                    $builder->where('l.state_id', $filters['state_id']);
                }
            }
            
            if (isset($filters['city_id']) && !empty($filters['city_id'])) {
                if (is_array($filters['city_id'])) {
                    $builder->whereIn('l.city_id', $filters['city_id']);
                } else {
                    $builder->where('l.city_id', $filters['city_id']);
                }
            }
        } else {
            // Default to today and future follow-ups
            $builder->where('cl.follow_up_date_time >=', date('Y-m-d') . ' 00:00:00');
        }
        
        return $builder->countAllResults();
    }
}

