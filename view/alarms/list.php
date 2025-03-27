<?php
$baseUrl = BASE_URL;
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alarm Management</title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>

<body class="p-4 bg-light">
    <div class="container">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

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
                    <h3 class="mb-0"><i class="bi bi-alarm"></i> Alarm Management</h3>
                    <div>
                        <a href="<?= $baseUrl ?>/?route=equipment" class="btn btn-info me-2">
                            <i class="bi bi-gear"></i> Equipment
                        </a>
                        <a href="<?= $baseUrl ?>/?route=alarm-activity" class="btn btn-secondary me-2">
                            <i class="bi bi-clock-history"></i> Alarm History
                        </a>
                        <a href="<?= $baseUrl ?>/?route=alarm/create" class="btn btn-success">
                            <i class="bi bi-plus-circle"></i> New Alarm
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- <div class="row mb-4">
                    <div class="col-md-8">
                    </div>

                    <?php if (!empty($topAlarms)): ?>
                        <div class="col-md-4">
                            <div class="alert alert-info mb-0 p-2">
                                <h6 class="mb-1"><i class="bi bi-exclamation-triangle-fill"></i> Most Triggered:</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    <?php foreach ($topAlarms as $top): ?>
                                        <span class="badge bg-warning text-dark">
                                            <?= htmlspecialchars($top['description']) ?> (<?= $top['trigger_count'] ?>)
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div> -->

                <?php if (empty($alarms)): ?>
                    <div class="alert alert-warning">No alarms registered.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Description</th>
                                    <th>Classification</th>
                                    <th>Status</th>
                                    <th>Equipment</th>
                                    <th>Created</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($alarms as $alarm): ?>
                                    <tr
                                        class="<?= in_array($alarm['id'], array_column($topAlarms, 'id')) ? 'table-warning' : '' ?>">
                                        <td>
                                            <div class="fw-bold"><?= htmlspecialchars($alarm['description']) ?></div>
                                            <small class="text-muted">ID: <?= $alarm['id'] ?></small>
                                        </td>
                                        <td>
                                            <span
                                                class="badge rounded-pill 
                                                <?= $alarm['classification'] === 'Urgent' ? 'bg-danger' :
                                                    ($alarm['classification'] === 'Emergent' ? 'bg-warning' : 'bg-primary') ?>">
                                                <?= htmlspecialchars($alarm['classification']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge rounded-pill bg-<?= $alarm['status'] === 'on' ? 'success' : 'secondary' ?>">
                                                <i class="bi bi-<?= $alarm['status'] === 'on' ? 'power' : 'power-off' ?>"></i>
                                                <?= htmlspecialchars(ucfirst($alarm['status'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div><?= htmlspecialchars($alarm['equipment_name']) ?></div>
                                        </td>
                                        <td>
                                            <?= date('d/m/Y', strtotime($alarm['created_at'])) ?>
                                            <small
                                                class="d-block text-muted"><?= date('H:i', strtotime($alarm['created_at'])) ?></small>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <?php if ($alarm['status'] === 'off'): ?>
                                                    <a href="<?= $baseUrl ?>/?route=alarm/activate&id=<?= $alarm['id'] ?>"
                                                        class="btn btn-outline-success" title="Activate">
                                                        <i class="bi bi-power"></i> Activate
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?= $baseUrl ?>/?route=alarm/deactivate&id=<?= $alarm['id'] ?>"
                                                        class="btn btn-outline-warning" title="Deactivate">
                                                        <i class="bi bi-plug-fill"></i> Deactivate
                                                    </a>
                                                <?php endif; ?>

                                                <a href="<?= $baseUrl ?>/?route=alarm/delete&id=<?= $alarm['id'] ?>"
                                                    class="btn btn-outline-danger"
                                                    onclick="return confirm('Delete this alarm?')" title="Delete">
                                                    <i class="bi bi-trash"></i> Delete
                                                </a>
                                                <a href="<?= $baseUrl ?>/?route=alarm/edit&id=<?= $alarm['id'] ?>"
                                                    class="btn btn-outline-primary me-2" title="Edit">
                                                    <i class="bi bi-pencil"></i> Edit
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
                        <i class="bi bi-info-circle"></i> Showing <?= count($alarms) ?> alarm(s)
                    </small>
                    <div>
                        <a href="<?= $baseUrl ?>/?route=alarm-activity" class="btn btn-outline-info btn-sm me-2">
                            <i class="bi bi-clock-history"></i> Alarm History
                        </a>
                        <a href="<?= $baseUrl ?>/?route=equipment" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-gear"></i> View Equipment List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>