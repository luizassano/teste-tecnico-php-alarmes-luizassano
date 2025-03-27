<?php
$baseUrl = BASE_URL;

if (!isset($triggeredAlarms)) {
    $triggeredAlarms = [];
}
if (!isset($topAlarms)) {
    $topAlarms = [];
}
if (!isset($filters)) {
    $filters = [
        'description' => '',
        'equipment' => '',
        'status' => ''
    ];
}
if (!isset($orderBy)) {
    $orderBy = 'started_at';
}
if (!isset($orderDir)) {
    $orderDir = 'DESC';
}
if (!isset($sortUrls)) {
    $sortUrls = [
        'started_at' => BASE_URL . '/?route=alarm-activity',
        'duration_seconds' => BASE_URL . '/?route=alarm-activity'
    ];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alarm History</title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>

<body class="p-4 bg-light">
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="bi bi-clock-history"></i> Alarm History</h3>
                    <div>
                        <a href="<?= $baseUrl ?>/?route=equipment" class="btn btn-info me-2">
                            <i class="bi bi-gear"></i> Equipment
                        </a>
                        <a href="<?= $baseUrl ?>/?route=alarm" class="btn btn-success">
                            <i class="bi bi-alarm"></i> Alarms
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form method="get" action="<?= $baseUrl ?>/?route=alarm-activity" class="mb-4">
                    <input type="hidden" name="route" value="alarm-activity">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="description" class="form-label">Alarm Description</label>
                            <input type="text" class="form-control" id="description" name="description"
                                value="<?= htmlspecialchars($filters['description'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="equipment" class="form-label">Equipment</label>
                            <input type="text" class="form-control" id="equipment" name="equipment"
                                value="<?= htmlspecialchars($filters['equipment'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All</option>
                                <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>
                                    Active</option>
                                <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>

                <div class="alert alert-info mb-4">
                    <h5><i class="bi bi-trophy"></i> Top 3 Most Frequent Alarms</h5>
                    <?php if (!empty($topAlarms)): ?>
                        <div class="d-flex flex-wrap gap-3 mt-2">
                            <?php
                            $topThree = array_slice($topAlarms, 0, 3);
                            $medalClasses = ['bg-warning text-dark', 'bg-secondary text-white', 'bg-danger text-white'];

                            foreach ($topThree as $index => $top): ?>
                                <div class="badge <?= $medalClasses[$index] ?? 'bg-light text-dark' ?> p-2">
                                    <i class="bi bi-<?=
                                        $index === 0 ? 'trophy-fill' :
                                        ($index === 1 ? 'award-fill' : 'exclamation-triangle-fill')
                                        ?>"></i>
                                    <?= htmlspecialchars($top['description'] ?? 'N/A') ?>
                                    <span class="badge bg-dark rounded-pill ms-1">
                                        <?= $top['trigger_count'] ?? 0 ?> time<?= ($top['trigger_count'] ?? 0) > 1 ? 's' : '' ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="mt-2 text-muted">
                            <i class="bi bi-info-circle"></i> No frequent alarm data available
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (empty($triggeredAlarms)): ?>
                    <div class="alert alert-warning">No alarm history found.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>
                                        <a href="<?= $sortUrls['started_at'] ?>" class="text-white text-decoration-none">
                                            Activation Date
                                            <?php if ($orderBy === 'started_at'): ?>
                                                <i class="bi bi-caret-<?= $orderDir === 'ASC' ? 'up' : 'down' ?>-fill"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th>Description / Classification</th>
                                    <th>Equipment</th>
                                    <th>
                                        <a href="<?= $sortUrls['duration_seconds'] ?>"
                                            class="text-white text-decoration-none">
                                            Duration
                                            <?php if ($orderBy === 'duration_seconds'): ?>
                                                <i class="bi bi-caret-<?= $orderDir === 'ASC' ? 'up' : 'down' ?>-fill"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($triggeredAlarms as $alarm): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?= date('d/m/Y', strtotime($alarm['started_at'])) ?></div>
                                            <small
                                                class="text-muted"><?= date('H:i', strtotime($alarm['started_at'])) ?></small>
                                            <?php if (!empty($alarm['ended_at'])): ?>
                                                <div class="mt-1">
                                                    <small class="text-muted">Deactivated:
                                                        <?= date('d/m/Y H:i', strtotime($alarm['ended_at'])) ?></small>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?= htmlspecialchars($alarm['alarm_description']) ?></div>
                                            <span
                                                class="badge rounded-pill 
                                                <?= $alarm['classification'] === 'Urgent' ? 'bg-danger' :
                                                    ($alarm['classification'] === 'Emergent' ? 'bg-warning' : 'bg-primary') ?>">
                                                <?= htmlspecialchars($alarm['classification']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?= htmlspecialchars($alarm['equipment_name']) ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($alarm['serial_number']) ?></small>
                                        </td>
                                        <td class="fw-bold">
                                            <?php
                                            $duration = $alarm['duration_seconds'];
                                            $hours = floor($duration / 3600);
                                            $minutes = floor(($duration % 3600) / 60);
                                            $seconds = $duration % 60;

                                            if ($hours > 0) {
                                                echo sprintf("%dh %02dm %02ds", $hours, $minutes, $seconds);
                                            } elseif ($minutes > 0) {
                                                echo sprintf("%dm %02ds", $minutes, $seconds);
                                            } else {
                                                echo sprintf("%ds", $seconds);
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <span
                                                class="badge rounded-pill bg-<?= $alarm['status'] === 'active' ? 'success' : 'secondary' ?>">
                                                <i
                                                    class="bi bi-<?= $alarm['status'] === 'active' ? 'power' : 'power-off' ?>"></i>
                                                <?= $alarm['status'] === 'active' ? 'Active' : 'Inactive' ?>
                                            </span>
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
                        <i class="bi bi-info-circle"></i> Showing <?= count($triggeredAlarms) ?> record(s)
                    </small>
                    <a href="<?= $baseUrl ?>/?route=alarm" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-arrow-left"></i> Back to Alarms
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>