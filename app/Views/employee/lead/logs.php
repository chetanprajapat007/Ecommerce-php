<?= $this->extend('layouts/employee') ?>

<?= $this->section('content') ?>

<!-- Lead Call Logs -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Call Logs</h5>
        <div>
            <a href="<?= site_url('employee/leads/call/' . $lead['id']) ?>" class="btn btn-success me-2">
                <i class="fas fa-phone"></i> Call Now
            </a>
            <a href="<?= site_url('employee/leads') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Leads
            </a>
        </div>
    </div>
    <div class="card-body">
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
                        <h6 class="mb-0">Call History</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>First Call</th>
                                    <td>
                                        <?= !empty($logs) ? date('d M Y h:i A', strtotime($logs[count($logs) - 1]['created_at'])) : 'N/A' ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Last Call</th>
                                    <td>
                                        <?= !empty($logs) ? date('d M Y h:i A', strtotime($logs[0]['created_at'])) : 'N/A' ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Next Follow-up</th>
                                    <td>
                                        <?php
                                        $nextFollowup = null;
                                        foreach ($logs as $log) {
                                            if ($log['new_status'] === 'followup' && $log['follow_up_date_time'] && strtotime($log['follow_up_date_time']) > time()) {
                                                $nextFollowup = $log['follow_up_date_time'];
                                                break;
                                            }
                                        }
                                        echo $nextFollowup ? date('d M Y h:i A', strtotime($nextFollowup)) : 'N/A';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Calls</th>
                                    <td><?= count($logs) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Call Logs Timeline -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Call Logs Timeline</h6>
            </div>
            <div class="card-body">
                <?php if (empty($logs)): ?>
                    <p class="text-center">No call logs found.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped datatable">
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
                                <?php foreach ($logs as $log): ?>
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

