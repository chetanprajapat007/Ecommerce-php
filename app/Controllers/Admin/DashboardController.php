<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LeadModel;
use App\Models\CallLogModel;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    protected $leadModel;
    protected $callLogModel;
    protected $userModel;
    
    public function __construct()
    {
        $this->leadModel = new LeadModel();
        $this->callLogModel = new CallLogModel();
        $this->userModel = new UserModel();
    }
    
    public function index()
    {
        // Get filter parameters
        $employeeId = $this->request->getGet('employee_id');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        
        // Prepare filters
        $filters = [];
        
        if (!empty($employeeId)) {
            $filters['employee_id'] = $employeeId;
        }
        
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
            'employees' => $this->userModel->where('role', 'employee')->findAll(),
            'filters' => [
                'employee_id' => $employeeId,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ];
        
        return view('admin/dashboard/index', $data);
    }
}

