<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Panel' ?> - Outbound Call Center Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 10px 20px;
            margin-bottom: 5px;
        }
        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .sidebar .nav-link.active {
            color: white;
            background-color: #007bff;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        .content {
            padding: 20px;
        }
        .navbar {
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #eee;
            padding: 15px 20px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .status-card {
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
            color: white;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .status-card:hover {
            transform: translateY(-5px);
        }
        .status-card h3 {
            font-size: 2rem;
            margin-bottom: 5px;
        }
        .status-card p {
            margin-bottom: 0;
            font-size: 1rem;
        }
        .status-new { background-color: #007bff; }
        .status-followup { background-color: #ffc107; }
        .status-na { background-color: #6c757d; }
        .status-dead { background-color: #dc3545; }
        .status-interested { background-color: #28a745; }
        .status-win { background-color: #17a2b8; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar d-none d-md-block">
                <div class="text-center mb-4">
                    <h4>OCCMS</h4>
                    <p class="mb-0">Admin Panel</p>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string() == 'admin/dashboard' ? 'active' : '' ?>" href="<?= site_url('admin/dashboard') ?>">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string() == 'admin/states' ? 'active' : '' ?>" href="<?= site_url('admin/states') ?>">
                            <i class="fas fa-map-marker-alt"></i> Manage States
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string() == 'admin/cities' ? 'active' : '' ?>" href="<?= site_url('admin/cities') ?>">
                            <i class="fas fa-city"></i> Manage Cities
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string() == 'admin/employees' ? 'active' : '' ?>" href="<?= site_url('admin/employees') ?>">
                            <i class="fas fa-users"></i> Manage Employees
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string() == 'admin/leads' ? 'active' : '' ?>" href="<?= site_url('admin/leads') ?>">
                            <i class="fas fa-database"></i> Manage Leads
                        </a>
                    </li>
                    <li class="nav-item mt-5">
                        <a class="nav-link" href="<?= site_url('logout') ?>">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 ms-auto">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light mb-4">
                    <div class="container-fluid">
                        <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <span class="navbar-brand"><?= $title ?? 'Admin Panel' ?></span>
                        <div class="ms-auto d-flex align-items-center">
                            <div class="dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle me-1"></i> <?= session()->get('name') ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="<?= site_url('logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
                
                <!-- Mobile Sidebar -->
                <div class="collapse d-md-none" id="sidebarMenu">
                    <ul class="nav flex-column mb-4">
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'admin/dashboard' ? 'active' : '' ?>" href="<?= site_url('admin/dashboard') ?>">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'admin/states' ? 'active' : '' ?>" href="<?= site_url('admin/states') ?>">
                                <i class="fas fa-map-marker-alt"></i> Manage States
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'admin/cities' ? 'active' : '' ?>" href="<?= site_url('admin/cities') ?>">
                                <i class="fas fa-city"></i> Manage Cities
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'admin/employees' ? 'active' : '' ?>" href="<?= site_url('admin/employees') ?>">
                                <i class="fas fa-users"></i> Manage Employees
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'admin/leads' ? 'active' : '' ?>" href="<?= site_url('admin/leads') ?>">
                                <i class="fas fa-database"></i> Manage Leads
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Content -->
                <div class="content">
                    <?php if (session()->has('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= session('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= session('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?= $this->renderSection('content') ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('.datatable').DataTable({
                "pageLength": 10,
                "responsive": true
            });
            
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5'
            });
            
            // Status card click handler
            $('.status-card').on('click', function() {
                var status = $(this).data('status');
                window.location.href = '<?= site_url('admin/leads') ?>?status=' + status;
            });
            
            // Confirm delete
            $('.confirm-delete').on('click', function(e) {
                if (!confirm('Are you sure you want to delete this item?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>

