<?php
$baseUrl = '/teste-tecnico-php-alarmes-luizassano/public';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Equipment</title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="p-4 bg-light">
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0"><i class="bi bi-pencil"></i> Edit Equipment</h3>
            </div>
            
            <div class="card-body">
                <form method="POST" action="<?= $baseUrl ?>/?route=equipment/update">
                    <input type="hidden" name="id" value="<?= $equipment['id'] ?>">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?= htmlspecialchars($equipment['name'] ?? '') ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="serial_number" class="form-label">Serial Number</label>
                        <input type="text" class="form-control" id="serial_number" name="serial_number"
                               value="<?= htmlspecialchars($equipment['serial_number'] ?? '') ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="Voltage" <?= ($equipment['type'] ?? '') === 'Voltage' ? 'selected' : '' ?>>Voltage</option>
                            <option value="Current" <?= ($equipment['type'] ?? '') === 'Current' ? 'selected' : '' ?>>Current</option>
                            <option value="Other" <?= ($equipment['type'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?= $baseUrl ?>/?route=equipment" class="btn btn-secondary me-md-2">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>