<?= $this->extend('layouts/employee') ?>

<?= $this->section('content') ?>

<!-- Lead Call -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Call Lead</h5>
        <a href="<?= site_url('employee/leads') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Leads
        </a>
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
        
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Lead Information</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Company</th>
                                <td><?= $lead['company_name'] ?? 'N/A' ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?= $lead['email'] ?? 'N/A' ?></td>
                            </tr>
                            <tr>
                                <th>Contact</th>
                                <td><?= $lead['contact_number'] ?? 'N/A' ?></td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td><?= $lead['address'] ?? 'N/A' ?></td>
                            </tr>
                            <tr>
                                <th>State/City</th>
                                <td>
                                    <?= $lead['state_name'] ?? 'N/A' ?><br>
                                    <?= $lead['city_name'] ?? 'N/A' ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Current Status</th>
                                <td>
                                    <span class="badge bg-<?= getStatusBadgeClass($lead['status']) ?>">
                                        <?= ucfirst($lead['status']) ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Call Attempts</th>
                                <td><?= $lead['call_attempt_count'] ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Log Call</h6>
                    </div>
                    <div class="card-body">
                        <form action="<?= site_url('employee/leads/log-call/' . $lead['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            
                            <div class="mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select" id="status" name="status" required>
                                    <?php foreach ($statuses as $status): ?>
                                        <option value="<?= $status ?>" <?= $lead['status'] === $status ? 'selected' : '' ?>>
                                            <?= ucfirst($status) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="remark" class="form-label">Remark *</label>
                                <textarea class="form-control" id="remark" name="remark" rows="4" required></textarea>
                            </div>
                            
                            <div class="mb-3 follow-up-date-container" style="display: <?= $lead['status'] === 'followup' ? 'block' : 'none' ?>;">
                                <label for="follow_up_date_time" class="form-label">Follow-up Date/Time *</label>
                                <input type="datetime-local" class="form-control" id="follow_up_date_time" name="follow_up_date_time">
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Log Call
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Previous Call Logs -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Previous Call Logs</h6>
            </div>
            <div class="card-body">
                <?php if (empty($lead['call_logs'])): ?>
                    <p class="text-center">No previous call logs found.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date/Time</th>
                                    <th>Status Change</th>
                                    <th>Remark</th>
                                    <th>Follow-up Date</th>
                                    <th>Logged By</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lead['call_logs'] as $log): ?>
                                    <tr>
                                        <td><?= date('d M Y h:i A', strtotime($log['created_at'])) ?></td>
                                        <td>
                                            <span class="badge bg-<?= getStatusBadgeClass($log['old_status']) ?>">
                                                <?= ucfirst($log['old_status']) ?>
                                            </span>
                                            <i class="fas fa-arrow-right mx-1"></i>
                                            <span class="badge bg-<?= getStatusBadgeClass($log['new_status']) ?>">
                                                <?= ucfirst($log['new_status']) ?>
                                            </span>
                                        </td>
                                        <td><?= $log['remark'] ?></td>
                                        <td>
                                            <?= $log['follow_up_date_time'] ? date('d M Y h:i A', strtotime($log['follow_up_date_time'])) : 'N/A' ?>
                                        </td>
                                        <td><?= $log['user_name'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Show/hide follow-up date based on status
        $('#status').on('change', function() {
            if ($(this).val() === 'followup') {
                $('.follow-up-date-container').show();
                $('#follow_up_date_time').prop('required', true);
            } else {
                $('.follow-up-date-container').hide();
                $('#follow_up_date_time').prop('required', false);
            }
        });
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

