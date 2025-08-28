<?php

namespace App\Controllers\Employee;

use App\Controllers\BaseController;
use App\Models\LeadModel;
use App\Models\StateModel;
use App\Models\CityModel;
use App\Models\UserModel;
use App\Models\CallLogModel;
use App\Models\UserAssignedLocationModel;

class LeadController extends BaseController
{
    protected $leadModel;
    protected $stateModel;
    protected $cityModel;
    protected $userModel;
    protected $callLogModel;
    protected $userAssignedLocationModel;
    
    public function __construct()
    {
        $this->leadModel = new LeadModel();
        $this->stateModel = new StateModel();
        $this->cityModel = new CityModel();
        $this->userModel = new UserModel();
        $this->callLogModel = new CallLogModel();
        $this->userAssignedLocationModel = new UserAssignedLocationModel();
    }
    
    public function index()
    {
        // Get current employee ID
        $employeeId = session()->get('user_id');
        
        // Get assigned locations
        $assignedLocations = $this->userAssignedLocationModel->where('user_id', $employeeId)->findAll();
        
        $assignedStateIds = [];
        $assignedCityIds = [];
        
        foreach ($assignedLocations as $location) {
            if (!in_array($location['state_id'], $assignedStateIds)) {
                $assignedStateIds[] = $location['state_id'];
            }
            
            $assignedCityIds[] = $location['city_id'];
        }
        
        // Get filter parameters
        $status = $this->request->getGet('status');
        $stateId = $this->request->getGet('state_id');
        $cityId = $this->request->getGet('city_id');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        
        // Prepare filters
        $filters = [
            'employee_id' => $employeeId
        ];
        
        if (!empty($status)) {
            $filters['status'] = $status;
        }
        
        if (!empty($stateId)) {
            $filters['state_id'] = $stateId;
        }
        
        if (!empty($cityId)) {
            $filters['city_id'] = $cityId;
        }
        
        if (!empty($startDate) || !empty($endDate)) {
            $filters['date_range'] = [
                'start' => $startDate,
                'end' => $endDate
            ];
        }
        
        $data = [
            'title' => 'Manage Leads',
            'leads' => $this->leadModel->getLeadsWithDetails(0, 0, $filters),
            'states' => $this->stateModel->whereIn('id', $assignedStateIds)->findAll(),
            'cities' => !empty($stateId) ? $this->cityModel->whereIn('id', $assignedCityIds)->where('state_id', $stateId)->findAll() : [],
            'filters' => [
                'status' => $status,
                'state_id' => $stateId,
                'city_id' => $cityId,
                'start_date' => $startDate,
                'end_date' => $endDate
            ],
            'statuses' => ['new', 'followup', 'na', 'dead', 'interested', 'win'],
            'is_data_entry_allowed' => session()->get('is_data_entry_allowed')
        ];
        
        return view('employee/lead/index', $data);
    }
    
    public function store()
    {
        // Check if employee has data entry rights
        if (!session()->get('is_data_entry_allowed')) {
            return redirect()->to('employee/leads')->with('error', 'You do not have permission to add leads');
        }
        
        $rules = [
            'state_id' => 'required|numeric|is_not_unique[states.id]',
            'city_id' => 'permit_empty|numeric|is_not_unique[cities.id]',
            'company_name' => 'permit_empty|max_length[100]',
            'email' => 'permit_empty|valid_email|max_length[100]',
            'contact_number' => 'permit_empty|max_length[15]',
            'address' => 'permit_empty|max_length[255]',
            'status' => 'required|in_list[new,followup,na,dead,interested,win]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Check if employee is assigned to the selected state/city
        $employeeId = session()->get('user_id');
        $stateId = $this->request->getPost('state_id');
        $cityId = $this->request->getPost('city_id');
        
        $isAssigned = $this->userAssignedLocationModel
            ->where('user_id', $employeeId)
            ->where('state_id', $stateId)
            ->where(function($builder) use ($cityId) {
                if ($cityId) {
                    $builder->where('city_id', $cityId);
                }
            })
            ->countAllResults() > 0;
        
        if (!$isAssigned) {
            return redirect()->back()->withInput()->with('error', 'You are not assigned to the selected state/city');
        }
        
        $data = [
            'state_id' => $stateId,
            'city_id' => $cityId ?: null,
            'company_name' => $this->request->getPost('company_name') ?: null,
            'email' => $this->request->getPost('email') ?: null,
            'contact_number' => $this->request->getPost('contact_number') ?: null,
            'address' => $this->request->getPost('address') ?: null,
            'status' => $this->request->getPost('status'),
            'call_attempt_count' => 0,
            'created_by' => $employeeId,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->leadModel->insert($data)) {
            return redirect()->to('employee/leads')->with('success', 'Lead added successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to add lead');
        }
    }
    
    public function edit($id)
    {
        // Get current employee ID
        $employeeId = session()->get('user_id');
        
        // Check if lead exists and is assigned to the employee
        $lead = $this->leadModel->find($id);
        
        if (!$lead) {
            return redirect()->to('employee/leads')->with('error', 'Lead not found');
        }
        
        // Check if employee is assigned to the lead's state/city
        $isAssigned = $this->userAssignedLocationModel
            ->where('user_id', $employeeId)
            ->where('state_id', $lead['state_id'])
            ->where(function($builder) use ($lead) {
                if ($lead['city_id']) {
                    $builder->where('city_id', $lead['city_id']);
                }
            })
            ->countAllResults() > 0;
        
        if (!$isAssigned) {
            return redirect()->to('employee/leads')->with('error', 'You are not assigned to this lead');
        }
        
        // Get assigned locations
        $assignedLocations = $this->userAssignedLocationModel->where('user_id', $employeeId)->findAll();
        
        $assignedStateIds = [];
        $assignedCityIds = [];
        
        foreach ($assignedLocations as $location) {
            if (!in_array($location['state_id'], $assignedStateIds)) {
                $assignedStateIds[] = $location['state_id'];
            }
            
            $assignedCityIds[] = $location['city_id'];
        }
        
        $data = [
            'lead' => $lead,
            'states' => $this->stateModel->whereIn('id', $assignedStateIds)->findAll(),
            'cities' => !empty($lead['state_id']) ? $this->cityModel->whereIn('id', $assignedCityIds)->where('state_id', $lead['state_id'])->findAll() : []
        ];
        
        return $this->response->setJSON($data);
    }
    
    public function update($id)
    {
        // Check if employee has data entry rights
        if (!session()->get('is_data_entry_allowed')) {
            return redirect()->to('employee/leads')->with('error', 'You do not have permission to update leads');
        }
        
        // Get current employee ID
        $employeeId = session()->get('user_id');
        
        // Check if lead exists and is assigned to the employee
        $lead = $this->leadModel->find($id);
        
        if (!$lead) {
            return redirect()->to('employee/leads')->with('error', 'Lead not found');
        }
        
        // Check if employee is assigned to the lead's state/city
        $isAssigned = $this->userAssignedLocationModel
            ->where('user_id', $employeeId)
            ->where('state_id', $lead['state_id'])
            ->where(function($builder) use ($lead) {
                if ($lead['city_id']) {
                    $builder->where('city_id', $lead['city_id']);
                }
            })
            ->countAllResults() > 0;
        
        if (!$isAssigned) {
            return redirect()->to('employee/leads')->with('error', 'You are not assigned to this lead');
        }
        
        $rules = [
            'state_id' => 'required|numeric|is_not_unique[states.id]',
            'city_id' => 'permit_empty|numeric|is_not_unique[cities.id]',
            'company_name' => 'permit_empty|max_length[100]',
            'email' => 'permit_empty|valid_email|max_length[100]',
            'contact_number' => 'permit_empty|max_length[15]',
            'address' => 'permit_empty|max_length[255]',
            'status' => 'required|in_list[new,followup,na,dead,interested,win]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Check if employee is assigned to the new state/city
        $stateId = $this->request->getPost('state_id');
        $cityId = $this->request->getPost('city_id');
        
        $isAssignedToNew = $this->userAssignedLocationModel
            ->where('user_id', $employeeId)
            ->where('state_id', $stateId)
            ->where(function($builder) use ($cityId) {
                if ($cityId) {
                    $builder->where('city_id', $cityId);
                }
            })
            ->countAllResults() > 0;
        
        if (!$isAssignedToNew) {
            return redirect()->back()->withInput()->with('error', 'You are not assigned to the selected state/city');
        }
        
        $data = [
            'state_id' => $stateId,
            'city_id' => $cityId ?: null,
            'company_name' => $this->request->getPost('company_name') ?: null,
            'email' => $this->request->getPost('email') ?: null,
            'contact_number' => $this->request->getPost('contact_number') ?: null,
            'address' => $this->request->getPost('address') ?: null,
            'status' => $this->request->getPost('status'),
            'updated_by' => $employeeId,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->leadModel->update($id, $data)) {
            return redirect()->to('employee/leads')->with('success', 'Lead updated successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update lead');
        }
    }
    
    public function updateField($id)
    {
        // Check if employee has data entry rights
        if (!session()->get('is_data_entry_allowed')) {
            return $this->response->setJSON(['success' => false, 'message' => 'You do not have permission to update leads']);
        }
        
        // Get current employee ID
        $employeeId = session()->get('user_id');
        
        // Check if lead exists and is assigned to the employee
        $lead = $this->leadModel->find($id);
        
        if (!$lead) {
            return $this->response->setJSON(['success' => false, 'message' => 'Lead not found']);
        }
        
        // Check if employee is assigned to the lead's state/city
        $isAssigned = $this->userAssignedLocationModel
            ->where('user_id', $employeeId)
            ->where('state_id', $lead['state_id'])
            ->where(function($builder) use ($lead) {
                if ($lead['city_id']) {
                    $builder->where('city_id', $lead['city_id']);
                }
            })
            ->countAllResults() > 0;
        
        if (!$isAssigned) {
            return $this->response->setJSON(['success' => false, 'message' => 'You are not assigned to this lead']);
        }
        
        $field = $this->request->getPost('field');
        $value = $this->request->getPost('value');
        
        $allowedFields = ['email', 'contact_number'];
        
        if (!in_array($field, $allowedFields)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid field']);
        }
        
        // Validate field value
        $rules = [];
        
        if ($field === 'email') {
            $rules['value'] = 'permit_empty|valid_email|max_length[100]';
        } elseif ($field === 'contact_number') {
            $rules['value'] = 'permit_empty|max_length[15]';
        }
        
        if (!empty($rules) && !$this->validate(['value' => $rules['value']])) {
            return $this->response->setJSON(['success' => false, 'message' => $this->validator->getError('value')]);
        }
        
        $data = [
            $field => $value ?: null,
            'updated_by' => $employeeId,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->leadModel->update($id, $data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Field updated successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update field']);
        }
    }
    
    public function call($id)
    {
        // Get current employee ID
        $employeeId = session()->get('user_id');
        
        // Check if lead exists and is assigned to the employee
        $lead = $this->leadModel->getLeadWithDetails($id);
        
        if (!$lead) {
            return redirect()->to('employee/leads')->with('error', 'Lead not found');
        }
        
        // Check if employee is assigned to the lead's state/city
        $isAssigned = $this->userAssignedLocationModel
            ->where('user_id', $employeeId)
            ->where('state_id', $lead['state_id'])
            ->where(function($builder) use ($lead) {
                if ($lead['city_id']) {
                    $builder->where('city_id', $lead['city_id']);
                }
            })
            ->countAllResults() > 0;
        
        if (!$isAssigned) {
            return redirect()->to('employee/leads')->with('error', 'You are not assigned to this lead');
        }
        
        $data = [
            'title' => 'Call Lead',
            'lead' => $lead,
            'statuses' => ['new', 'followup', 'na', 'dead', 'interested', 'win']
        ];
        
        return view('employee/lead/call', $data);
    }
    
    public function logCall($id)
    {
        // Get current employee ID
        $employeeId = session()->get('user_id');
        
        // Check if lead exists and is assigned to the employee
        $lead = $this->leadModel->find($id);
        
        if (!$lead) {
            return redirect()->to('employee/leads')->with('error', 'Lead not found');
        }
        
        // Check if employee is assigned to the lead's state/city
        $isAssigned = $this->userAssignedLocationModel
            ->where('user_id', $employeeId)
            ->where('state_id', $lead['state_id'])
            ->where(function($builder) use ($lead) {
                if ($lead['city_id']) {
                    $builder->where('city_id', $lead['city_id']);
                }
            })
            ->countAllResults() > 0;
        
        if (!$isAssigned) {
            return redirect()->to('employee/leads')->with('error', 'You are not assigned to this lead');
        }
        
        $rules = [
            'status' => 'required|in_list[new,followup,na,dead,interested,win]',
            'remark' => 'required|max_length[500]',
            'follow_up_date_time' => 'permit_empty|valid_date[Y-m-d\TH:i]'
        ];
        
        // If status is followup, follow_up_date_time is required
        if ($this->request->getPost('status') === 'followup') {
            $rules['follow_up_date_time'] = 'required|valid_date[Y-m-d\TH:i]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Start transaction
        $this->leadModel->db->transBegin();
        
        try {
            // Insert call log
            $callLogData = [
                'lead_id' => $id,
                'user_id' => $employeeId,
                'old_status' => $lead['status'],
                'new_status' => $this->request->getPost('status'),
                'remark' => $this->request->getPost('remark'),
                'follow_up_date_time' => $this->request->getPost('status') === 'followup' ? $this->request->getPost('follow_up_date_time') : null,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->callLogModel->insert($callLogData);
            
            // Update lead status and increment call attempt count
            $leadData = [
                'status' => $this->request->getPost('status'),
                'call_attempt_count' => $lead['call_attempt_count'] + 1,
                'updated_by' => $employeeId,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $this->leadModel->update($id, $leadData);
            
            $this->leadModel->db->transCommit();
            
            return redirect()->to('employee/leads')->with('success', 'Call logged successfully');
        } catch (\Exception $e) {
            $this->leadModel->db->transRollback();
            
            return redirect()->back()->withInput()->with('error', 'Failed to log call: ' . $e->getMessage());
        }
    }
    
    public function logs($id)
    {
        // Get current employee ID
        $employeeId = session()->get('user_id');
        
        // Check if lead exists and is assigned to the employee
        $lead = $this->leadModel->getLeadWithDetails($id);
        
        if (!$lead) {
            return redirect()->to('employee/leads')->with('error', 'Lead not found');
        }
        
        // Check if employee is assigned to the lead's state/city
        $isAssigned = $this->userAssignedLocationModel
            ->where('user_id', $employeeId)
            ->where('state_id', $lead['state_id'])
            ->where(function($builder) use ($lead) {
                if ($lead['city_id']) {
                    $builder->where('city_id', $lead['city_id']);
                }
            })
            ->countAllResults() > 0;
        
        if (!$isAssigned) {
            return redirect()->to('employee/leads')->with('error', 'You are not assigned to this lead');
        }
        
        $logs = $this->callLogModel->getCallLogsByLead($id);
        
        $data = [
            'title' => 'Call Logs',
            'lead' => $lead,
            'logs' => $logs
        ];
        
        return view('employee/lead/logs', $data);
    }
    
    public function import()
    {
        // Check if employee has data entry rights
        if (!session()->get('is_data_entry_allowed')) {
            return redirect()->to('employee/leads')->with('error', 'You do not have permission to import leads');
        }
        
        // Get current employee ID
        $employeeId = session()->get('user_id');
        
        // Get assigned locations
        $assignedLocations = $this->userAssignedLocationModel->where('user_id', $employeeId)->findAll();
        
        $assignedStateIds = [];
        $assignedCityIds = [];
        
        foreach ($assignedLocations as $location) {
            if (!in_array($location['state_id'], $assignedStateIds)) {
                $assignedStateIds[] = $location['state_id'];
            }
            
            $assignedCityIds[] = $location['city_id'];
        }
        
        $data = [
            'title' => 'Import Leads',
            'states' => $this->stateModel->whereIn('id', $assignedStateIds)->findAll(),
            'cities' => []
        ];
        
        return view('employee/lead/import', $data);
    }
    
    public function sampleCsv()
    {
        $filename = 'leads_import_sample.csv';
        $data = "company_name,email,contact_number,address\n";
        $data .= "ABC Company,info@abc.com,1234567890,123 Main St\n";
        $data .= "XYZ Corporation,contact@xyz.com,9876543210,456 Oak Ave\n";
        
        return $this->response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($data);
    }
    
    public function processImport()
    {
        // Check if employee has data entry rights
        if (!session()->get('is_data_entry_allowed')) {
            return redirect()->to('employee/leads')->with('error', 'You do not have permission to import leads');
        }
        
        // Get current employee ID
        $employeeId = session()->get('user_id');
        
        $rules = [
            'state_id' => 'required|numeric|is_not_unique[states.id]',
            'city_id' => 'permit_empty|numeric|is_not_unique[cities.id]',
            'csv_file' => 'uploaded[csv_file]|ext_in[csv_file,csv]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $file = $this->request->getFile('csv_file');
        
        if (!$file->isValid()) {
            return redirect()->back()->with('error', 'Invalid file');
        }
        
        $stateId = $this->request->getPost('state_id');
        $cityId = $this->request->getPost('city_id') ?: null;
        
        // Check if employee is assigned to the selected state/city
        $isAssigned = $this->userAssignedLocationModel
            ->where('user_id', $employeeId)
            ->where('state_id', $stateId)
            ->where(function($builder) use ($cityId) {
                if ($cityId) {
                    $builder->where('city_id', $cityId);
                }
            })
            ->countAllResults() > 0;
        
        if (!$isAssigned) {
            return redirect()->back()->withInput()->with('error', 'You are not assigned to the selected state/city');
        }
        
        // Read CSV file
        $handle = fopen($file->getTempName(), 'r');
        
        // Skip header row
        fgetcsv($handle);
        
        $importCount = 0;
        $errorCount = 0;
        
        // Start transaction
        $this->leadModel->db->transBegin();
        
        try {
            while (($row = fgetcsv($handle)) !== false) {
                // Check if row has enough columns
                if (count($row) < 4) {
                    $errorCount++;
                    continue;
                }
                
                $data = [
                    'state_id' => $stateId,
                    'city_id' => $cityId,
                    'company_name' => !empty($row[0]) ? $row[0] : null,
                    'email' => !empty($row[1]) ? $row[1] : null,
                    'contact_number' => !empty($row[2]) ? $row[2] : null,
                    'address' => !empty($row[3]) ? $row[3] : null,
                    'status' => 'new',
                    'call_attempt_count' => 0,
                    'created_by' => $employeeId,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                if ($this->leadModel->insert($data)) {
                    $importCount++;
                } else {
                    $errorCount++;
                }
            }
            
            fclose($handle);
            
            $this->leadModel->db->transCommit();
            
            return redirect()->to('employee/leads')->with('success', "Import completed: $importCount leads imported, $errorCount errors");
        } catch (\Exception $e) {
            $this->leadModel->db->transRollback();
            
            return redirect()->back()->with('error', 'Failed to import leads: ' . $e->getMessage());
        }
    }
    
    public function export()
    {
        // Get current employee ID
        $employeeId = session()->get('user_id');
        
        // Get filter parameters
        $status = $this->request->getGet('status');
        $stateId = $this->request->getGet('state_id');
        $cityId = $this->request->getGet('city_id');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        
        // Prepare filters
        $filters = [
            'employee_id' => $employeeId
        ];
        
        if (!empty($status)) {
            $filters['status'] = $status;
        }
        
        if (!empty($stateId)) {
            $filters['state_id'] = $stateId;
        }
        
        if (!empty($cityId)) {
            $filters['city_id'] = $cityId;
        }
        
        if (!empty($startDate) || !empty($endDate)) {
            $filters['date_range'] = [
                'start' => $startDate,
                'end' => $endDate
            ];
        }
        
        // Get leads with filters
        $leads = $this->leadModel->getLeadsWithDetails(0, 0, $filters);
        
        // Create CSV data
        $filename = 'leads_export_' . date('Y-m-d_H-i-s') . '.csv';
        $data = "ID,Company Name,Email,Contact Number,Address,State,City,Status,Call Attempts,Created At,Updated At\n";
        
        foreach ($leads as $lead) {
            $data .= '"' . $lead['id'] . '",';
            $data .= '"' . ($lead['company_name'] ?? '') . '",';
            $data .= '"' . ($lead['email'] ?? '') . '",';
            $data .= '"' . ($lead['contact_number'] ?? '') . '",';
            $data .= '"' . ($lead['address'] ?? '') . '",';
            $data .= '"' . ($lead['state_name'] ?? '') . '",';
            $data .= '"' . ($lead['city_name'] ?? '') . '",';
            $data .= '"' . ucfirst($lead['status']) . '",';
            $data .= '"' . $lead['call_attempt_count'] . '",';
            $data .= '"' . date('Y-m-d H:i:s', strtotime($lead['created_at'])) . '",';
            $data .= '"' . ($lead['updated_at'] ? date('Y-m-d H:i:s', strtotime($lead['updated_at'])) : '') . '"';
            $data .= "\n";
        }
        
        return $this->response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($data);
    }
}

