<?php
$baseUrl = '/teste-tecnico-php-alarmes-luizassano/public';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment Management</title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>

<body class="p-4 bg-light">
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="bi bi-gear"></i> Equipment Management</h3>
                    <div>
                        <a href="<?= $baseUrl ?>/?route=alarm" class="btn btn-info me-2">
                            <i class="bi bi-alarm"></i> Alarms
                        </a>
                        <a href="<?= $baseUrl ?>/?route=equipment/create" class="btn btn-success">
                            <i class="bi bi-plus-circle"></i> New Equipment
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row mb-4">
                </div>

                <?php if (empty($equipments)): ?>
                    <div class="alert alert-warning">No equipment found.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Serial Number</th>
                                    <th>Type</th>
                                    <th>Created</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($equipments as $equipment): ?>
                                    <tr>
                                        <td><?= $equipment['id'] ?></td>
                                        <td>
                                            <div class="fw-bold"><?= htmlspecialchars($equipment['name']) ?></div>
                                        </td>
                                        <td><?= htmlspecialchars($equipment['serial_number']) ?></td>
                                        <td>
                                            <span class="badge rounded-pill 
        <?= $equipment['type'] === 'Voltage' ? 'bg-danger' :
            ($equipment['type'] === 'Current' ? 'bg-warning' : 'bg-info') ?>">
                                                <?= $equipment['type'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?= date('d/m/Y', strtotime($equipment['created_at'])) ?>
                                            <small
                                                class="d-block text-muted"><?= date('H:i', strtotime($equipment['created_at'])) ?></small>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?= $baseUrl ?>/?route=equipment/delete&id=<?= $equipment['id'] ?>"
                                                    class="btn btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this equipment?')"
                                                    title="Delete">
                                                    <i class="bi bi-trash"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="bi bi-info-circle"></i> Showing <?= count($equipments) ?> equipment(s)
                    </small>
                    <a href="<?= $baseUrl ?>/?route=alarm" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-alarm"></i> View Alarms
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>