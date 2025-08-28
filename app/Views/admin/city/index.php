<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- City List -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Manage Cities</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCityModal">
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
                        <th>City Name</th>
                        <th>State</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cities as $city): ?>
                        <tr>
                            <td><?= $city['id'] ?></td>
                            <td><?= $city['name'] ?></td>
                            <td><?= $city['state_name'] ?></td>
                            <td>
                                <span class="badge bg-<?= $city['status'] === 'active' ? 'success' : 'danger' ?>">
                                    <?= ucfirst($city['status']) ?>
                                </span>
                            </td>
                            <td><?= $city['created_by_name'] ?></td>
                            <td><?= date('d M Y h:i A', strtotime($city['created_at'])) ?></td>
                            <td><?= $city['updated_at'] ? date('d M Y h:i A', strtotime($city['updated_at'])) : 'N/A' ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary edit-city" data-id="<?= $city['id'] ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <a href="<?= site_url('admin/cities/delete/' . $city['id']) ?>" class="btn btn-sm btn-danger confirm-delete">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add City Modal -->
<div class="modal fade" id="addCityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New City</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('admin/cities/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="state_id" class="form-label">State</label>
                        <select class="form-select select2" id="state_id" name="state_id" required>
                            <option value="">Select State</option>
                            <?php foreach ($states as $state): ?>
                                <option value="<?= $state['id'] ?>"><?= $state['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">City Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
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

<!-- Edit City Modal -->
<div class="modal fade" id="editCityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit City</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCityForm" action="" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_state_id" class="form-label">State</label>
                        <select class="form-select select2" id="edit_state_id" name="state_id" required>
                            <option value="">Select State</option>
                            <?php foreach ($states as $state): ?>
                                <option value="<?= $state['id'] ?>"><?= $state['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">City Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
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
        $('#addCityModal').on('shown.bs.modal', function() {
            $('.select2').select2({
                dropdownParent: $('#addCityModal'),
                theme: 'bootstrap-5'
            });
        });
        
        $('#editCityModal').on('shown.bs.modal', function() {
            $('.select2').select2({
                dropdownParent: $('#editCityModal'),
                theme: 'bootstrap-5'
            });
        });
        
        // Edit City
        $('.edit-city').on('click', function() {
            var id = $(this).data('id');
            
            $.ajax({
                url: '<?= site_url('admin/cities/edit/') ?>' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#edit_state_id').val(response.city.state_id).trigger('change');
                    $('#edit_name').val(response.city.name);
                    $('#edit_status').val(response.city.status);
                    $('#editCityForm').attr('action', '<?= site_url('admin/cities/update/') ?>' + id);
                    $('#editCityModal').modal('show');
                },
                error: function() {
                    alert('Failed to get city data');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>

