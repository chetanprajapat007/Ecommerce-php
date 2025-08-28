<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LeadModel;
use App\Models\CallLogModel;
use App\Models\UserModel;
use App\Models\StateModel;
use App\Models\CityModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $leadModel = new LeadModel();
        $callLogModel = new CallLogModel();
        $userModel = new UserModel();
        $stateModel = new StateModel();
        $cityModel = new CityModel();
        
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
        $statusCounts = $leadModel->getLeadCountsByStatus($filters);
        
        // Get upcoming follow-ups
        $followUps = $callLogModel->getUpcomingFollowUps($filters, 10);
        
        // Get all employees for filter
        $employees = $userModel->where('role', 'employee')->findAll();
        
        // Get all leads with filters
        $leads = $leadModel->getLeadsWithDetails(10, 0, $filters);
        
        $data = [
            'title' => 'Dashboard',
            'statusCounts' => $statusCounts,
            'followUps' => $followUps,
            'employees' => $employees,
            'leads' => $leads,
            'filters' => [
                'employee_id' => $employeeId,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ];
        
        return view('admin/dashboard/index', $data);
    }
}

