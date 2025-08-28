<?php

namespace App\Controllers\Employee;

use App\Controllers\BaseController;
use App\Models\LeadModel;
use App\Models\CallLogModel;
use App\Models\UserAssignedLocationModel;

class DashboardController extends BaseController
{
    protected $leadModel;
    protected $callLogModel;
    protected $userAssignedLocationModel;
    
    public function __construct()
    {
        $this->leadModel = new LeadModel();
        $this->callLogModel = new CallLogModel();
        $this->userAssignedLocationModel = new UserAssignedLocationModel();
    }
    
    public function index()
    {
        // Get current employee ID
        $employeeId = session()->get('user_id');
        
        // Get filter parameters
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        
        // Prepare filters
        $filters = [
            'employee_id' => $employeeId
        ];
        
        if (!empty($startDate) || !empty($endDate)) {
            $filters['date_range'] = [
                'start' => $startDate,
                'end' => $endDate
            ];
        }
        
        // Get lead counts by status
        $leadCounts = $this->leadModel->getLeadCountsByStatus($filters);
        
        // Get upcoming follow-ups
        $followUps = $this->callLogModel->getUpcomingFollowUps($filters, 10);
        
        // Get recent leads
        $recentLeads = $this->leadModel->getLeadsWithDetails(10, 0, $filters);
        
        $data = [
            'title' => 'Dashboard',
            'leadCounts' => $leadCounts,
            'followUps' => $followUps,
            'recentLeads' => $recentLeads,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ];
        
        return view('employee/dashboard/index', $data);
    }
}

