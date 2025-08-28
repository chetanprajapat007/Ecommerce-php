<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Import Leads -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Import Leads</h5>
        <a href="<?= site_url('admin/leads') ?>" class="btn btn-secondary">
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
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Import Instructions</h6>
                    </div>
                    <div class="card-body">
                        <ol>
                            <li>Download the sample CSV file to see the required format.</li>
                            <li>Prepare your CSV file with the following columns:
                                <ul>
                                    <li>company_name</li>
                                    <li>email</li>
                                    <li>contact_number</li>
                                    <li>address</li>
                                </ul>
                            </li>
                            <li>Select the State and City (optional) for all imported leads.</li>
                            <li>Upload your CSV file and click Import.</li>
                            <li>All imported leads will be set to "New" status by default.</li>
                        </ol>
                        
                        <div class="mt-3">
                            <a href="<?= site_url('admin/leads/sample-csv') ?>" class="btn btn-info">
                                <i class="fas fa-download"></i> Download Sample CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Upload CSV File</h6>
                    </div>
                    <div class="card-body">
                        <form action="<?= site_url('admin/leads/process-import') ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            
                            <div class="mb-3">
                                <label for="state_id" class="form-label">State *</label>
                                <select class="form-select select2" id="state_id" name="state_id" required>
                                    <option value="">Select State</option>
                                    <?php foreach ($states as $state): ?>
                                        <option value="<?= $state['id'] ?>"><?= $state['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="city_id" class="form-label">City (Optional)</label>
                                <select class="form-select select2" id="city_id" name="city_id">
                                    <option value="">Select State First</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="csv_file" class="form-label">CSV File *</label>
                                <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload"></i> Import Leads
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap-5'
        });
        
        // Load cities when state is selected
        $('#state_id').on('change', function() {
            var stateId = $(this).val();
            
            if (!stateId) {
                $('#city_id').html('<option value="">Select State First</option>');
                return;
            }
            
            $.ajax({
                url: '<?= site_url('admin/cities/by-state/') ?>' + stateId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var options = '<option value="">Select City (Optional)</option>';
                    
                    response.cities.forEach(function(city) {
                        options += '<option value="' + city.id + '">' + city.name + '</option>';
                    });
                    
                    $('#city_id').html(options);
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>

