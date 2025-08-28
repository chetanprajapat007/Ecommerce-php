<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Dashboard Filters -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Dashboard Filters</h5>
    </div>
    <div class="card-body">
        <form action="<?= site_url('admin/dashboard') ?>" method="get">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="employee_id" class="form-label">Employee</label>
                    <select name="employee_id" id="employee_id" class="form-select select2">
                        <option value="">All Employees</option>
                        <?php foreach ($employees as $employee): ?>
                            <option value="<?= $employee['id'] ?>" <?= $filters['employee_id'] == $employee['id'] ? 'selected' : '' ?>>
                                <?= $employee['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $filters['start_date'] ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $filters['end_date'] ?>">
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Status Cards -->
<div class="row">
    <div class="col-md-2">
        <div class="status-card status-new" data-status="new">
            <h3><?= $statusCounts['new'] ?? 0 ?></h3>
            <p>New</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="status-card status-followup" data-status="followup">
            <h3><?= $statusCounts['followup'] ?? 0 ?></h3>
            <p>Follow-up</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="status-card status-na" data-status="na">
            <h3><?= $statusCounts['na'] ?? 0 ?></h3>
            <p>Not Attended</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="status-card status-dead" data-status="dead">
            <h3><?= $statusCounts['dead'] ?? 0 ?></h3>
            <p>Dead</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="status-card status-interested" data-status="interested">
            <h3><?= $statusCounts['interested'] ?? 0 ?></h3>
            <p>Interested</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="status-card status-win" data-status="win">
            <h3><?= $statusCounts['win'] ?? 0 ?></h3>
            <p>Win</p>
        </div>
    </div>
</div>

<!-- Follow-up Widget -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Upcoming Follow-ups</h5>
        <a href="<?= site_url('admin/leads?status=followup') ?>" class="btn btn-sm btn-primary">View All</a>
    </div>
    <div class="card-body">
        <?php if (empty($followUps)): ?>
            <p class="text-center">No upcoming follow-ups found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Company</th>
                            <th>Contact</th>
                            <th>State/City</th>
                            <th>Follow-up Date</th>
                            <th>Assigned To</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($followUps as $followUp): ?>
                            <tr>
                                <td><?= $followUp['company_name'] ?? 'N/A' ?></td>
                                <td>
                                    <?= $followUp['email'] ?? 'N/A' ?><br>
                                    <?= $followUp['contact_number'] ?? 'N/A' ?>
                                </td>
                                <td>
                                    <?= $followUp['state_name'] ?? 'N/A' ?><br>
                                    <?= $followUp['city_name'] ?? 'N/A' ?>
                                </td>
                                <td><?= date('d M Y h:i A', strtotime($followUp['follow_up_date_time'])) ?></td>
                                <td><?= $followUp['user_name'] ?></td>
                                <td>
                                    <a href="<?= site_url('admin/leads/call/' . $followUp['lead_id']) ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-phone"></i> Call
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Recent Leads -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Leads</h5>
        <a href="<?= site_url('admin/leads') ?>" class="btn btn-sm btn-primary">View All</a>
    </div>
    <div class="card-body">
        <?php if (empty($leads)): ?>
            <p class="text-center">No leads found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Company</th>
                            <th>Contact</th>
                            <th>State/City</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leads as $lead): ?>
                            <tr>
                                <td><?= $lead['company_name'] ?? 'N/A' ?></td>
                                <td>
                                    <?= $lead['email'] ?? 'N/A' ?><br>
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
                                <td><?= date('d M Y h:i A', strtotime($lead['updated_at'])) ?></td>
                                <td>
                                    <a href="<?= site_url('admin/leads/call/' . $lead['id']) ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-phone"></i> Call
                                    </a>
                                    <a href="<?= site_url('admin/leads/logs/' . $lead['id']) ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-history"></i> Logs
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Additional dashboard-specific scripts can be added here
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

