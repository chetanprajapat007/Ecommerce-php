<?php

namespace App\Models;

class CallLogModel extends BaseModel
{
    protected $table = 'call_logs';
    protected $allowedFields = [
        'lead_id', 'user_id', 'old_status', 'new_status', 'remark', 
        'follow_up_date_time', 'created_at'
    ];
    
    protected $validationRules = [
        'lead_id' => 'required|numeric|is_not_unique[leads.id]',
        'user_id' => 'required|numeric|is_not_unique[users.id]',
        'old_status' => 'required|in_list[new,followup,na,dead,interested,win]',
        'new_status' => 'required|in_list[new,followup,na,dead,interested,win]',
        'remark' => 'required|max_length[500]'
    ];
    
    /**
     * Get call logs for a lead with user details
     *
     * @param int $leadId
     * @return array
     */
    public function getCallLogsByLead($leadId)
    {
        $logs = $this->select('call_logs.*, users.name as user_name')
            ->join('users', 'users.id = call_logs.user_id')
            ->where('call_logs.lead_id', $leadId)
            ->orderBy('call_logs.created_at', 'DESC')
            ->findAll();
        
        return $logs;
    }
    
    /**
     * Get upcoming follow-ups
     *
     * @param array $filters
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getUpcomingFollowUps($filters = [], $limit = 0, $offset = 0)
    {
        $builder = $this->select('call_logs.*, leads.company_name, leads.email, leads.contact_number, users.name as user_name')
            ->join('leads', 'leads.id = call_logs.lead_id')
            ->join('users', 'users.id = call_logs.user_id')
            ->where('call_logs.new_status', 'followup')
            ->where('call_logs.follow_up_date_time >=', date('Y-m-d H:i:s'))
            ->orderBy('call_logs.follow_up_date_time', 'ASC');
        
        // Apply filters
        if (!empty($filters)) {
            // Employee filter
            if (isset($filters['employee_id'])) {
                $builder->where('call_logs.user_id', $filters['employee_id']);
            }
            
            // Date range filter
            if (isset($filters['date_range'])) {
                if (!empty($filters['date_range']['start'])) {
                    $builder->where('call_logs.follow_up_date_time >=', $filters['date_range']['start'] . ' 00:00:00');
                }
                
                if (!empty($filters['date_range']['end'])) {
                    $builder->where('call_logs.follow_up_date_time <=', $filters['date_range']['end'] . ' 23:59:59');
                }
            }
        }
        
        // Apply limit and offset
        if ($limit > 0) {
            $builder->limit($limit, $offset);
        }
        
        return $builder->findAll();
    }
}

