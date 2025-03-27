<?php
$baseUrl = BASE_URL;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Alarm</title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="p-4 bg-light">
    <div class="container">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="bi bi-pencil"></i> Edit Alarm</h3>
                    <div>
                        <a href="<?= $baseUrl ?>/?route=alarm" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Alarms
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form method="POST" action="<?= $baseUrl ?>/?route=alarm/update">
                    <input type="hidden" name="id" value="<?= $alarm['id'] ?>">
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="description" name="description" 
                                   value="<?= htmlspecialchars($alarm['description'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="classification" class="form-label">Classification</label>
                            <select class="form-select" id="classification" name="classification" required>
                                <option value="Normal" <?= ($alarm['classification'] ?? '') === 'Normal' ? 'selected' : '' ?>>Normal</option>
                                <option value="Emergent" <?= ($alarm['classification'] ?? '') === 'Emergent' ? 'selected' : '' ?>>Emergent</option>
                                <option value="Urgent" <?= ($alarm['classification'] ?? '') === 'Urgent' ? 'selected' : '' ?>>Urgent</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="equipment_id" class="form-label">Equipment</label>
                            <select class="form-select" id="equipment_id" name="equipment_id" required>
                                <?php foreach ($equipments as $equipment): ?>
                                    <option value="<?= $equipment['id'] ?>" 
                                        <?= ($equipment['id'] == $alarm['equipment_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($equipment['name']) ?> (<?= htmlspecialchars($equipment['serial_number']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Current Status</label>
                            <div class="form-control bg-light">
                                <span class="badge rounded-pill bg-<?= $alarm['status'] === 'on' ? 'success' : 'secondary' ?>">
                                    <i class="bi bi-<?= $alarm['status'] === 'on' ? 'power' : 'power-off' ?>"></i>
                                    <?= htmlspecialchars(ucfirst($alarm['status'])) ?>
                                </span>
                                <small class="text-muted ms-2">
                                    (Change status from alarms list)
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="<?= $baseUrl ?>/?route=alarm" class="btn btn-outline-secondary me-md-2">
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