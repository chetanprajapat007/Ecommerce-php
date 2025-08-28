<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\StateModel;
use App\Models\CityModel;
use App\Models\UserAssignedLocationModel;

class EmployeeController extends BaseController
{
    protected $userModel;
    protected $stateModel;
    protected $cityModel;
    protected $userAssignedLocationModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->stateModel = new StateModel();
        $this->cityModel = new CityModel();
        $this->userAssignedLocationModel = new UserAssignedLocationModel();
    }
    
    public function index()
    {
        $data = [
            'title' => 'Manage Employees',
            'employees' => $this->userModel->getEmployeesWithDetails(),
            'states' => $this->stateModel->getActiveStates()
        ];
        
        return view('admin/employee/index', $data);
    }
    
    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'contact' => 'required|min_length[10]|max_length[15]',
            'password' => 'required|min_length[6]',
            'states' => 'required',
            'cities' => 'required',
            'is_data_entry_allowed' => 'permit_empty|in_list[0,1]',
            'status' => 'required|in_list[active,inactive]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Start transaction
        $this->userModel->db->transBegin();
        
        try {
            // Insert user data
            $userData = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'contact' => $this->request->getPost('contact'),
                'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role' => 'employee',
                'is_data_entry_allowed' => $this->request->getPost('is_data_entry_allowed') ? 1 : 0,
                'status' => $this->request->getPost('status'),
                'created_by' => session()->get('user_id'),
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $userId = $this->userModel->insert($userData);
            
            if (!$userId) {
                throw new \Exception('Failed to create employee');
            }
            
            // Insert assigned locations
            $states = $this->request->getPost('states');
            $cities = $this->request->getPost('cities');
            
            foreach ($cities as $cityId) {
                $city = $this->cityModel->find($cityId);
                
                if (!$city) {
                    continue;
                }
                
                $locationData = [
                    'user_id' => $userId,
                    'state_id' => $city['state_id'],
                    'city_id' => $cityId
                ];
                
                $this->userAssignedLocationModel->insert($locationData);
            }
            
            $this->userModel->db->transCommit();
            
            return redirect()->to('admin/employees')->with('success', 'Employee added successfully');
        } catch (\Exception $e) {
            $this->userModel->db->transRollback();
            
            return redirect()->back()->withInput()->with('error', 'Failed to add employee: ' . $e->getMessage());
        }
    }
    
    public function edit($id)
    {
        $employee = $this->userModel->find($id);
        
        if (!$employee || $employee['role'] !== 'employee') {
            return redirect()->to('admin/employees')->with('error', 'Employee not found');
        }
        
        // Get assigned locations
        $assignedLocations = $this->userAssignedLocationModel->where('user_id', $id)->findAll();
        
        $assignedStates = [];
        $assignedCities = [];
        
        foreach ($assignedLocations as $location) {
            if (!in_array($location['state_id'], $assignedStates)) {
                $assignedStates[] = $location['state_id'];
            }
            
            $assignedCities[] = $location['city_id'];
        }
        
        $data = [
            'employee' => $employee,
            'assignedStates' => $assignedStates,
            'assignedCities' => $assignedCities,
            'states' => $this->stateModel->getActiveStates(),
            'cities' => $this->cityModel->getActiveCitiesByStates($assignedStates)
        ];
        
        return $this->response->setJSON($data);
    }
    
    public function update($id)
    {
        $employee = $this->userModel->find($id);
        
        if (!$employee || $employee['role'] !== 'employee') {
            return redirect()->to('admin/employees')->with('error', 'Employee not found');
        }
        
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => "required|valid_email|is_unique[users.email,id,$id]",
            'contact' => 'required|min_length[10]|max_length[15]',
            'states' => 'required',
            'cities' => 'required',
            'is_data_entry_allowed' => 'permit_empty|in_list[0,1]',
            'status' => 'required|in_list[active,inactive]'
        ];
        
        // Add password validation only if password is provided
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Start transaction
        $this->userModel->db->transBegin();
        
        try {
            // Update user data
            $userData = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'contact' => $this->request->getPost('contact'),
                'is_data_entry_allowed' => $this->request->getPost('is_data_entry_allowed') ? 1 : 0,
                'status' => $this->request->getPost('status'),
                'updated_by' => session()->get('user_id'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Update password if provided
            if ($this->request->getPost('password')) {
                $userData['password_hash'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            }
            
            $this->userModel->update($id, $userData);
            
            // Delete existing assigned locations
            $this->userAssignedLocationModel->where('user_id', $id)->delete();
            
            // Insert new assigned locations
            $cities = $this->request->getPost('cities');
            
            foreach ($cities as $cityId) {
                $city = $this->cityModel->find($cityId);
                
                if (!$city) {
                    continue;
                }
                
                $locationData = [
                    'user_id' => $id,
                    'state_id' => $city['state_id'],
                    'city_id' => $cityId
                ];
                
                $this->userAssignedLocationModel->insert($locationData);
            }
            
            $this->userModel->db->transCommit();
            
            return redirect()->to('admin/employees')->with('success', 'Employee updated successfully');
        } catch (\Exception $e) {
            $this->userModel->db->transRollback();
            
            return redirect()->back()->withInput()->with('error', 'Failed to update employee: ' . $e->getMessage());
        }
    }
    
    public function toggleStatus($id)
    {
        $employee = $this->userModel->find($id);
        
        if (!$employee || $employee['role'] !== 'employee') {
            return redirect()->to('admin/employees')->with('error', 'Employee not found');
        }
        
        $newStatus = ($employee['status'] === 'active') ? 'inactive' : 'active';
        
        $data = [
            'status' => $newStatus,
            'updated_by' => session()->get('user_id'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->userModel->update($id, $data)) {
            return redirect()->to('admin/employees')->with('success', 'Employee status updated successfully');
        } else {
            return redirect()->to('admin/employees')->with('error', 'Failed to update employee status');
        }
    }
}

