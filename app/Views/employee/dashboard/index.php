<?= $this->extend('layouts/employee') ?>

<?= $this->section('content') ?>

<!-- Dashboard Filters -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Dashboard Filters</h5>
    </div>
    <div class="card-body">
        <form action="<?= site_url('employee/dashboard') ?>" method="get">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $filters['start_date'] ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $filters['end_date'] ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-end">
                    <a href="<?= site_url('employee/dashboard') ?>" class="btn btn-secondary me-2">Reset</a>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Status Overview Cards -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">New</h5>
                <h2 class="card-text"><?= $leadCounts['new'] ?? 0 ?></h2>
                <a href="<?= site_url('employee/leads?status=new') ?>" class="stretched-link text-white"></a>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h5 class="card-title">Follow-up</h5>
                <h2 class="card-text"><?= $leadCounts['followup'] ?? 0 ?></h2>
                <a href="<?= site_url('employee/leads?status=followup') ?>" class="stretched-link text-dark"></a>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <h5 class="card-title">NA</h5>
                <h2 class="card-text"><?= $leadCounts['na'] ?? 0 ?></h2>
                <a href="<?= site_url('employee/leads?status=na') ?>" class="stretched-link text-white"></a>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h5 class="card-title">Dead</h5>
                <h2 class="card-text"><?= $leadCounts['dead'] ?? 0 ?></h2>
                <a href="<?= site_url('employee/leads?status=dead') ?>" class="stretched-link text-white"></a>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Interested</h5>
                <h2 class="card-text"><?= $leadCounts['interested'] ?? 0 ?></h2>
                <a href="<?= site_url('employee/leads?status=interested') ?>" class="stretched-link text-white"></a>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Win</h5>
                <h2 class="card-text"><?= $leadCounts['win'] ?? 0 ?></h2>
                <a href="<?= site_url('employee/leads?status=win') ?>" class="stretched-link text-white"></a>
            </div>
        </div>
    </div>
</div>

<!-- Follow-up Widget -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Your Upcoming Follow-ups</h5>
    </div>
    <div class="card-body">
        <?php if (empty($followUps)): ?>
            <p class="text-center">No upcoming follow-ups found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Follow-up Date</th>
                            <th>Company</th>
                            <th>Contact</th>
                            <th>Remark</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($followUps as $followUp): ?>
                            <tr>
                                <td><?= date('d M Y h:i A', strtotime($followUp['follow_up_date_time'])) ?></td>
                                <td><?= $followUp['company_name'] ?? 'N/A' ?></td>
                                <td>
                                    <?= $followUp['email'] ?? 'N/A' ?><br>
                                    <?= $followUp['contact_number'] ?? 'N/A' ?>
                                </td>
                                <td><?= $followUp['remark'] ?></td>
                                <td>
                                    <a href="<?= site_url('employee/leads/call/' . $followUp['lead_id']) ?>" class="btn btn-sm btn-success">
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
    <div class="card-header">
        <h5 class="mb-0">Your Recent Leads</h5>
    </div>
    <div class="card-body">
        <?php if (empty($recentLeads)): ?>
            <p class="text-center">No recent leads found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Company</th>
                            <th>Contact</th>
                            <th>State/City</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentLeads as $lead): ?>
                            <tr>
                                <td><?= $lead['id'] ?></td>
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
                                <td><?= date('d M Y', strtotime($lead['created_at'])) ?></td>
                                <td>
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
        <?php endif; ?>
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

