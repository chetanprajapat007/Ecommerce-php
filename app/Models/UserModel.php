<?php

namespace App\Models;

class UserModel extends BaseModel
{
    protected $table = 'users';
    protected $allowedFields = [
        'name', 'email', 'contact', 'password_hash', 'role', 
        'is_data_entry_allowed', 'status', 'created_by', 'created_at', 
        'updated_by', 'updated_at'
    ];
    
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[100]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'contact' => 'required|min_length[10]|max_length[15]',
        'role' => 'required|in_list[admin,employee]',
        'status' => 'required|in_list[active,inactive]'
    ];
    
    /**
     * Verify password
     *
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
    
    /**
     * Get all employees with their assigned locations
     *
     * @return array
     */
    public function getEmployeesWithDetails()
    {
        $employees = $this->where('role', 'employee')->findAll();
        
        $userAssignedLocationModel = new UserAssignedLocationModel();
        
        foreach ($employees as &$employee) {
            $employee['assigned_locations'] = $userAssignedLocationModel->getAssignedLocationsWithDetails($employee['id']);
        }
        
        return $employees;
    }
    
    /**
     * Get assigned states for an employee
     *
     * @param int $userId
     * @return array
     */
    public function getAssignedStates($userId)
    {
        $userAssignedLocationModel = new UserAssignedLocationModel();
        return $userAssignedLocationModel->getAssignedStates($userId);
    }
    
    /**
     * Get assigned cities for an employee
     *
     * @param int $userId
     * @return array
     */
    public function getAssignedCities($userId)
    {
        $userAssignedLocationModel = new UserAssignedLocationModel();
        return $userAssignedLocationModel->getAssignedCities($userId);
    }
}

