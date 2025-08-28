<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- State List -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Manage States</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStateModal">
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
                        <th>State Name</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($states as $state): ?>
                        <tr>
                            <td><?= $state['id'] ?></td>
                            <td><?= $state['name'] ?></td>
                            <td>
                                <span class="badge bg-<?= $state['status'] === 'active' ? 'success' : 'danger' ?>">
                                    <?= ucfirst($state['status']) ?>
                                </span>
                            </td>
                            <td><?= $state['created_by_name'] ?></td>
                            <td><?= date('d M Y h:i A', strtotime($state['created_at'])) ?></td>
                            <td><?= $state['updated_at'] ? date('d M Y h:i A', strtotime($state['updated_at'])) : 'N/A' ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary edit-state" data-id="<?= $state['id'] ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <a href="<?= site_url('admin/states/delete/' . $state['id']) ?>" class="btn btn-sm btn-danger confirm-delete">
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

<!-- Add State Modal -->
<div class="modal fade" id="addStateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New State</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('admin/states/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">State Name</label>
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

<!-- Edit State Modal -->
<div class="modal fade" id="editStateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit State</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editStateForm" action="" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">State Name</label>
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
        // Edit State
        $('.edit-state').on('click', function() {
            var id = $(this).data('id');
            
            $.ajax({
                url: '<?= site_url('admin/states/edit/') ?>' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#edit_name').val(response.state.name);
                    $('#edit_status').val(response.state.status);
                    $('#editStateForm').attr('action', '<?= site_url('admin/states/update/') ?>' + id);
                    $('#editStateModal').modal('show');
                },
                error: function() {
                    alert('Failed to get state data');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>

