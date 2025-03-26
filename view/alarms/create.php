<?php
$baseUrl = '/teste-tecnico-php-alarmes-luizassano/public';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Alarm</title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="p-4">
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Create New Alarm</h3>
            </div>
            
            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= $baseUrl ?>/?route=alarm/create">
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Classification</label>
                        <select name="classification" class="form-select" required>
                            <option value="">Select classification...</option>
                            <option value="Urgent">Urgent</option>
                            <option value="Emergent">Emergent</option>
                            <option value="Ordinary">Ordinary</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Equipment</label>
                        <select name="equipment_id" class="form-select" required>
                            <option value="">Select equipment...</option>
                            <?php foreach ($equipments as $eq): ?>
                                <option value="<?= $eq['id'] ?>">
                                    <?= htmlspecialchars($eq['name']) ?> (SN: <?= htmlspecialchars($eq['serial_number']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= $baseUrl ?>/?route=alarm" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Create Alarm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>