<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Employee List -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Manage Employees</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
            <i class="fas fa-plus"></i> Add New
        </button>
    </div>
    <div class="card-body">
        <?php if (session()->has('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Assigned States/Cities</th>
                        <th>Data Entry Rights</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $employee): ?>
                        <tr>
                            <td><?= $employee['id'] ?></td>
                            <td><?= $employee['name'] ?></td>
                            <td><?= $employee['email'] ?></td>
                            <td><?= $employee['contact'] ?></td>
                            <td>
                                <?php if (!empty($employee['assigned_locations'])): ?>
                                    <?php foreach ($employee['assigned_locations'] as $location): ?>
                                        <span class="badge bg-info"><?= $location['state_name'] ?> - <?= $location['city_name'] ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="badge bg-secondary">None</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($employee['is_data_entry_allowed']): ?>
                                    <span class="badge bg-success">Yes</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">No</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-<?= $employee['status'] === 'active' ? 'success' : 'danger' ?>">
                                    <?= ucfirst($employee['status']) ?>
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary edit-employee" data-id="<?= $employee['id'] ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <a href="<?= site_url('admin/employees/toggle-status/' . $employee['id']) ?>" class="btn btn-sm btn-<?= $employee['status'] === 'active' ? 'danger' : 'success' ?>">
                                    <i class="fas fa-toggle-<?= $employee['status'] === 'active' ? 'off' : 'on' ?>"></i> 
                                    <?= $employee['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('admin/employees/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact" class="form-label">Contact</label>
                            <input type="text" class="form-control" id="contact" name="contact" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="states" class="form-label">Assigned States</label>
                            <select class="form-select select2" id="states" name="states[]" multiple required>
                                <?php foreach ($states as $state): ?>
                                    <option value="<?= $state['id'] ?>"><?= $state['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cities" class="form-label">Assigned Cities</label>
                            <select class="form-select select2" id="cities" name="cities[]" multiple required>
                                <option value="">Select State(s) First</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_data_entry_allowed" name="is_data_entry_allowed" value="1">
                                <label class="form-check-label" for="is_data_entry_allowed">
                                    Allow Data Entry Rights
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Employee Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editEmployeeForm" action="" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_contact" class="form-label">Contact</label>
                            <input type="text" class="form-control" id="edit_contact" name="contact" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_password" class="form-label">Password (Leave blank to keep current)</label>
                            <input type="password" class="form-control" id="edit_password" name="password">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_states" class="form-label">Assigned States</label>
                            <select class="form-select select2" id="edit_states" name="states[]" multiple required>
                                <?php foreach ($states as $state): ?>
                                    <option value="<?= $state['id'] ?>"><?= $state['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_cities" class="form-label">Assigned Cities</label>
                            <select class="form-select select2" id="edit_cities" name="cities[]" multiple required>
                                <option value="">Select State(s) First</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="edit_is_data_entry_allowed" name="is_data_entry_allowed" value="1">
                                <label class="form-check-label" for="edit_is_data_entry_allowed">
                                    Allow Data Entry Rights
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_status" class="form-label">Status</label>
                            <select class="form-select" id="edit_status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Initialize Select2 for modals
        $('#addEmployeeModal').on('shown.bs.modal', function() {
            $('.select2').select2({
                dropdownParent: $('#addEmployeeModal'),
                theme: 'bootstrap-5'
            });
        });
        
        $('#editEmployeeModal').on('shown.bs.modal', function() {
            $('.select2').select2({
                dropdownParent: $('#editEmployeeModal'),
                theme: 'bootstrap-5'
            });
        });
        
        // Load cities when states are selected (Add Modal)
        $('#states').on('change', function() {
            var stateIds = $(this).val();
            
            if (stateIds.length === 0) {
                $('#cities').html('<option value="">Select State(s) First</option>');
                return;
            }
            
            var cities = [];
            
            // Make AJAX requests for each state
            var requests = stateIds.map(function(stateId) {
                return $.ajax({
                    url: '<?= site_url('admin/cities/by-state/') ?>' + stateId,
                    type: 'GET',
                    dataType: 'json'
                });
            });
            
            // Process all requests
            Promise.all(requests).then(function(responses) {
                responses.forEach(function(response) {
                    cities = cities.concat(response.cities);
                });
                
                // Update cities dropdown
                var options = '';
                
                cities.forEach(function(city) {
                    options += '<option value="' + city.id + '">' + city.name + ' (' + city.state_name + ')</option>';
                });
                
                $('#cities').html(options);
            });
        });
        
        // Load cities when states are selected (Edit Modal)
        $('#edit_states').on('change', function() {
            var stateIds = $(this).val();
            
            if (stateIds.length === 0) {
                $('#edit_cities').html('<option value="">Select State(s) First</option>');
                return;
            }
            
            var cities = [];
            
            // Make AJAX requests for each state
            var requests = stateIds.map(function(stateId) {
                return $.ajax({
                    url: '<?= site_url('admin/cities/by-state/') ?>' + stateId,
                    type: 'GET',
                    dataType: 'json'
                });
            });
            
            // Process all requests
            Promise.all(requests).then(function(responses) {
                responses.forEach(function(response) {
                    cities = cities.concat(response.cities);
                });
                
                // Update cities dropdown
                var options = '';
                
                cities.forEach(function(city) {
                    options += '<option value="' + city.id + '">' + city.name + ' (' + city.state_name + ')</option>';
                });
                
                $('#edit_cities').html(options);
                
                // Re-select previously selected cities if available
                if (window.selectedCities) {
                    $('#edit_cities').val(window.selectedCities).trigger('change');
                }
            });
        });
        
        // Edit Employee
        $('.edit-employee').on('click', function() {
            var id = $(this).data('id');
            
            $.ajax({
                url: '<?= site_url('admin/employees/edit/') ?>' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#edit_name').val(response.employee.name);
                    $('#edit_email').val(response.employee.email);
                    $('#edit_contact').val(response.employee.contact);
                    $('#edit_password').val('');
                    $('#edit_status').val(response.employee.status);
                    
                    if (response.employee.is_data_entry_allowed == 1) {
                        $('#edit_is_data_entry_allowed').prop('checked', true);
                    } else {
                        $('#edit_is_data_entry_allowed').prop('checked', false);
                    }
                    
                    // Store selected cities for later use
                    window.selectedCities = response.assignedCities;
                    
                    // Set selected states
                    $('#edit_states').val(response.assignedStates).trigger('change');
                    
                    $('#editEmployeeForm').attr('action', '<?= site_url('admin/employees/update/') ?>' + id);
                    $('#editEmployeeModal').modal('show');
                },
                error: function() {
                    alert('Failed to get employee data');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>

