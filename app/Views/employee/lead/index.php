<?= $this->extend('layouts/employee') ?>

<?= $this->section('content') ?>

<!-- Lead Filters -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Lead Filters</h5>
        <div>
            <a href="<?= site_url('employee/leads/export') . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '') ?>" class="btn btn-success me-2">
                <i class="fas fa-file-export"></i> Export
            </a>
            <?php if ($is_data_entry_allowed): ?>
                <a href="<?= site_url('employee/leads/import') ?>" class="btn btn-info me-2">
                    <i class="fas fa-file-import"></i> Import
                </a>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLeadModal">
                    <i class="fas fa-plus"></i> Add New
                </button>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
        <form action="<?= site_url('employee/leads') ?>" method="get">
            <div class="row">
                <div class="col-md-2 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Statuses</option>
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?= $status ?>" <?= $filters['status'] == $status ? 'selected' : '' ?>>
                                <?= ucfirst($status) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="state_id" class="form-label">State</label>
                    <select class="form-select select2" id="state_id" name="state_id">
                        <option value="">All States</option>
                        <?php foreach ($states as $state): ?>
                            <option value="<?= $state['id'] ?>" <?= $filters['state_id'] == $state['id'] ? 'selected' : '' ?>>
                                <?= $state['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="city_id" class="form-label">City</label>
                    <select class="form-select select2" id="city_id" name="city_id">
                        <option value="">All Cities</option>
                        <?php foreach ($cities as $city): ?>
                            <option value="<?= $city['id'] ?>" <?= $filters['city_id'] == $city['id'] ? 'selected' : '' ?>>
                                <?= $city['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $filters['start_date'] ?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $filters['end_date'] ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-end">
                    <a href="<?= site_url('employee/leads') ?>" class="btn btn-secondary me-2">Reset</a>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Lead List -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Lead List</h5>
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
                        <th>Company</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>State/City</th>
                        <th>Status</th>
                        <th>Call Attempts</th>
                        <th>Last Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leads as $lead): ?>
                        <tr>
                            <td><?= $lead['id'] ?></td>
                            <td><?= $lead['company_name'] ?? 'N/A' ?></td>
                            <td class="<?= $is_data_entry_allowed ? 'editable' : '' ?>" data-field="email" data-id="<?= $lead['id'] ?>">
                                <?= $lead['email'] ?? 'N/A' ?>
                            </td>
                            <td class="<?= $is_data_entry_allowed ? 'editable' : '' ?>" data-field="contact_number" data-id="<?= $lead['id'] ?>">
                                <?= $lead['contact_number'] ?? 'N/A' ?>
                            </td>
                            <td>
                                <?= $lead['state_name'] ?? 'N/A' ?><br>
                                <?= $lead['city_name'] ?? 'N/A' ?>
                            </td>
                            <td>
                                <span class="badge bg-<?= getStatusBadgeClass($lead['status']) ?>">
                                    <?= ucfirst($lead['status']) ?>
                                </span>
                            </td>
                            <td><?= $lead['call_attempt_count'] ?></td>
                            <td><?= $lead['updated_at'] ? date('d M Y h:i A', strtotime($lead['updated_at'])) : date('d M Y h:i A', strtotime($lead['created_at'])) ?></td>
                            <td>
                                <?php if ($is_data_entry_allowed): ?>
                                    <button type="button" class="btn btn-sm btn-primary edit-lead" data-id="<?= $lead['id'] ?>">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                <?php endif; ?>
                                <a href="<?= site_url('employee/leads/call/' . $lead['id']) ?>" class="btn btn-sm btn-success">
                                    <i class="fas fa-phone"></i> Call
                                </a>
                                <a href="<?= site_url('employee/leads/logs/' . $lead['id']) ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-history"></i> Logs
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if ($is_data_entry_allowed): ?>
<!-- Add Lead Modal -->
<div class="modal fade" id="addLeadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('employee/leads/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="state_id" class="form-label">State *</label>
                            <select class="form-select select2" id="add_state_id" name="state_id" required>
                                <option value="">Select State</option>
                                <?php foreach ($states as $state): ?>
                                    <option value="<?= $state['id'] ?>"><?= $state['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="city_id" class="form-label">City</label>
                            <select class="form-select select2" id="add_city_id" name="city_id">
                                <option value="">Select State First</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="company_name" name="company_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="contact_number" name="contact_number">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select" id="add_status" name="status" required>
                                <?php foreach ($statuses as $status): ?>
                                    <option value="<?= $status ?>" <?= $status === 'new' ? 'selected' : '' ?>>
                                        <?= ucfirst($status) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"></textarea>
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

<!-- Edit Lead Modal -->
<div class="modal fade" id="editLeadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editLeadForm" action="" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_state_id" class="form-label">State *</label>
                            <select class="form-select select2" id="edit_state_id" name="state_id" required>
                                <option value="">Select State</option>
                                <?php foreach ($states as $state): ?>
                                    <option value="<?= $state['id'] ?>"><?= $state['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_city_id" class="form-label">City</label>
                            <select class="form-select select2" id="edit_city_id" name="city_id">
                                <option value="">Select State First</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_company_name" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="edit_company_name" name="company_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_contact_number" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="edit_contact_number" name="contact_number">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_status" class="form-label">Status *</label>
                            <select class="form-select" id="edit_status" name="status" required>
                                <?php foreach ($statuses as $status): ?>
                                    <option value="<?= $status ?>">
                                        <?= ucfirst($status) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="edit_address" class="form-label">Address</label>
                            <textarea class="form-control" id="edit_address" name="address" rows="3"></textarea>
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
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        <?php if ($is_data_entry_allowed): ?>
        // Initialize Select2 for modals
        $('#addLeadModal').on('shown.bs.modal', function() {
            $('.select2').select2({
                dropdownParent: $('#addLeadModal'),
                theme: 'bootstrap-5'
            });
        });
        
        $('#editLeadModal').on('shown.bs.modal', function() {
            $('.select2').select2({
                dropdownParent: $('#editLeadModal'),
                theme: 'bootstrap-5'
            });
        });
        <?php endif; ?>
        
        // Load cities when state is selected (Filter)
        $('#state_id').on('change', function() {
            var stateId = $(this).val();
            
            if (!stateId) {
                $('#city_id').html('<option value="">All Cities</option>');
                return;
            }
            
            $.ajax({
                url: '<?= site_url('admin/cities/by-state/') ?>' + stateId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var options = '<option value="">All Cities</option>';
                    
                    response.cities.forEach(function(city) {
                        options += '<option value="' + city.id + '">' + city.name + '</option>';
                    });
                    
                    $('#city_id').html(options);
                }
            });
        });
        
        <?php if ($is_data_entry_allowed): ?>
        // Load cities when state is selected (Add Modal)
        $('#add_state_id').on('change', function() {
            var stateId = $(this).val();
            
            if (!stateId) {
                $('#add_city_id').html('<option value="">Select State First</option>');
                return;
            }
            
            $.ajax({
                url: '<?= site_url('admin/cities/by-state/') ?>' + stateId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var options = '<option value="">Select City</option>';
                    
                    response.cities.forEach(function(city) {
                        options += '<option value="' + city.id + '">' + city.name + '</option>';
                    });
                    
                    $('#add_city_id').html(options);
                }
            });
        });
        
        // Load cities when state is selected (Edit Modal)
        $('#edit_state_id').on('change', function() {
            var stateId = $(this).val();
            
            if (!stateId) {
                $('#edit_city_id').html('<option value="">Select State First</option>');
                return;
            }
            
            $.ajax({
                url: '<?= site_url('admin/cities/by-state/') ?>' + stateId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var options = '<option value="">Select City</option>';
                    
                    response.cities.forEach(function(city) {
                        options += '<option value="' + city.id + '">' + city.name + '</option>';
                    });
                    
                    $('#edit_city_id').html(options);
                    
                    // Re-select previously selected city if available
                    if (window.selectedCityId) {
                        $('#edit_city_id').val(window.selectedCityId).trigger('change');
                    }
                }
            });
        });
        
        // Edit Lead
        $('.edit-lead').on('click', function() {
            var id = $(this).data('id');
            
            $.ajax({
                url: '<?= site_url('employee/leads/edit/') ?>' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#edit_state_id').val(response.lead.state_id).trigger('change');
                    
                    // Store selected city for later use
                    window.selectedCityId = response.lead.city_id;
                    
                    $('#edit_company_name').val(response.lead.company_name);
                    $('#edit_email').val(response.lead.email);
                    $('#edit_contact_number').val(response.lead.contact_number);
                    $('#edit_address').val(response.lead.address);
                    $('#edit_status').val(response.lead.status);
                    
                    $('#editLeadForm').attr('action', '<?= site_url('employee/leads/update/') ?>' + id);
                    $('#editLeadModal').modal('show');
                },
                error: function() {
                    alert('Failed to get lead data');
                }
            });
        });
        
        // Inline editing
        $('.editable').on('dblclick', function() {
            var cell = $(this);
            var field = cell.data('field');
            var id = cell.data('id');
            var value = cell.text().trim();
            
            if (value === 'N/A') {
                value = '';
            }
            
            var input = $('<input type="text" class="form-control form-control-sm inline-edit" value="' + value + '">');
            
            cell.html(input);
            input.focus();
            
            input.on('blur', function() {
                var newValue = $(this).val().trim();
                
                $.ajax({
                    url: '<?= site_url('employee/leads/update-field/') ?>' + id,
                    type: 'POST',
                    data: {
                        field: field,
                        value: newValue,
                        <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            cell.html(newValue || 'N/A');
                        } else {
                            cell.html(value || 'N/A');
                            alert(response.message);
                        }
                    },
                    error: function() {
                        cell.html(value || 'N/A');
                        alert('Failed to update field');
                    }
                });
            });
            
            input.on('keypress', function(e) {
                if (e.which === 13) {
                    $(this).blur();
                }
            });
        });
        <?php endif; ?>
    });
</script>
<?= $this->endSection() ?>

<?php
// Helper function to get badge class based on status
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'new':
            return 'primary';
        case 'followup':
            return 'warning';
        case 'na':
            return 'secondary';
        case 'dead':
            return 'danger';
        case 'interested':
            return 'success';
        case 'win':
            return 'info';
        default:
            return 'secondary';
    }
}
?>

