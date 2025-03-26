<div class="container mt-4">
    <h2>Manage Alarm</h2>
    <p>Description: <?= htmlspecialchars($alarm['description']) ?></p>
    <p>Status: <?= $alarm['status'] ?></p>

    <?php if ($alarm['status'] === 'off'): ?>
        <a href="/?route=alarm/activate&id=<?= $alarm['id'] ?>" class="btn btn-success">Activate</a>
    <?php else: ?>
        <a href="/?route=alarm/deactivate&id=<?= $alarm['id'] ?>" class="btn btn-warning">Deactivate</a>
    <?php endif; ?>

    <a href="/?route=alarm" class="btn btn-secondary">Back</a>
</div>
