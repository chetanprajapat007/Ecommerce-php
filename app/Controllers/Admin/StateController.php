<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StateModel;

class StateController extends BaseController
{
    protected $stateModel;
    
    public function __construct()
    {
        $this->stateModel = new StateModel();
    }
    
    public function index()
    {
        $data = [
            'title' => 'Manage States',
            'states' => $this->stateModel->getAllStatesWithAudit()
        ];
        
        return view('admin/state/index', $data);
    }
    
    public function store()
    {
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]|is_unique[states.name]',
            'status' => 'required|in_list[active,inactive]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'name' => $this->request->getPost('name'),
            'status' => $this->request->getPost('status'),
            'created_by' => session()->get('user_id'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->stateModel->insert($data)) {
            return redirect()->to('admin/states')->with('success', 'State added successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to add state');
        }
    }
    
    public function edit($id)
    {
        $state = $this->stateModel->find($id);
        
        if (!$state) {
            return redirect()->to('admin/states')->with('error', 'State not found');
        }
        
        $data = [
            'title' => 'Edit State',
            'state' => $state
        ];
        
        return $this->response->setJSON($data);
    }
    
    public function update($id)
    {
        $state = $this->stateModel->find($id);
        
        if (!$state) {
            return redirect()->to('admin/states')->with('error', 'State not found');
        }
        
        $rules = [
            'name' => "required|min_length[2]|max_length[100]|is_unique[states.name,id,$id]",
            'status' => 'required|in_list[active,inactive]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'name' => $this->request->getPost('name'),
            'status' => $this->request->getPost('status'),
            'updated_by' => session()->get('user_id'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->stateModel->update($id, $data)) {
            return redirect()->to('admin/states')->with('success', 'State updated successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update state');
        }
    }
    
    public function delete($id)
    {
        $state = $this->stateModel->find($id);
        
        if (!$state) {
            return redirect()->to('admin/states')->with('error', 'State not found');
        }
        
        // Check if state has cities
        $cities = $this->stateModel->getCities($id);
        
        if (!empty($cities)) {
            return redirect()->to('admin/states')->with('error', 'Cannot delete state with associated cities');
        }
        
        // Soft delete by setting status to inactive
        $data = [
            'status' => 'inactive',
            'updated_by' => session()->get('user_id'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->stateModel->update($id, $data)) {
            return redirect()->to('admin/states')->with('success', 'State deleted successfully');
        } else {
            return redirect()->to('admin/states')->with('error', 'Failed to delete state');
        }
    }
}

