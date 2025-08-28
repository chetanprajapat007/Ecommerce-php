<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CityModel;
use App\Models\StateModel;

class CityController extends BaseController
{
    protected $cityModel;
    protected $stateModel;
    
    public function __construct()
    {
        $this->cityModel = new CityModel();
        $this->stateModel = new StateModel();
    }
    
    public function index()
    {
        $data = [
            'title' => 'Manage Cities',
            'cities' => $this->cityModel->getAllCitiesWithDetails(),
            'states' => $this->stateModel->getActiveStates()
        ];
        
        return view('admin/city/index', $data);
    }
    
    public function store()
    {
        $rules = [
            'state_id' => 'required|numeric|is_not_unique[states.id]',
            'name' => 'required|min_length[2]|max_length[100]',
            'status' => 'required|in_list[active,inactive]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'state_id' => $this->request->getPost('state_id'),
            'name' => $this->request->getPost('name'),
            'status' => $this->request->getPost('status'),
            'created_by' => session()->get('user_id'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->cityModel->insert($data)) {
            return redirect()->to('admin/cities')->with('success', 'City added successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to add city');
        }
    }
    
    public function edit($id)
    {
        $city = $this->cityModel->find($id);
        
        if (!$city) {
            return redirect()->to('admin/cities')->with('error', 'City not found');
        }
        
        $data = [
            'title' => 'Edit City',
            'city' => $city,
            'states' => $this->stateModel->getActiveStates()
        ];
        
        return $this->response->setJSON($data);
    }
    
    public function update($id)
    {
        $city = $this->cityModel->find($id);
        
        if (!$city) {
            return redirect()->to('admin/cities')->with('error', 'City not found');
        }
        
        $rules = [
            'state_id' => 'required|numeric|is_not_unique[states.id]',
            'name' => 'required|min_length[2]|max_length[100]',
            'status' => 'required|in_list[active,inactive]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'state_id' => $this->request->getPost('state_id'),
            'name' => $this->request->getPost('name'),
            'status' => $this->request->getPost('status'),
            'updated_by' => session()->get('user_id'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->cityModel->update($id, $data)) {
            return redirect()->to('admin/cities')->with('success', 'City updated successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update city');
        }
    }
    
    public function delete($id)
    {
        $city = $this->cityModel->find($id);
        
        if (!$city) {
            return redirect()->to('admin/cities')->with('error', 'City not found');
        }
        
        // Soft delete by setting status to inactive
        $data = [
            'status' => 'inactive',
            'updated_by' => session()->get('user_id'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->cityModel->update($id, $data)) {
            return redirect()->to('admin/cities')->with('success', 'City deleted successfully');
        } else {
            return redirect()->to('admin/cities')->with('error', 'Failed to delete city');
        }
    }
    
    public function getCitiesByState($stateId)
    {
        $cities = $this->cityModel->getActiveCitiesByState($stateId);
        
        return $this->response->setJSON([
            'cities' => $cities
        ]);
    }
}

