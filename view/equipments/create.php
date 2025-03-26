<?php
$baseUrl = '/teste-tecnico-php-alarmes-luizassano/public';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Equipment</title>
    <link rel="stylesheet" href="/teste-tecnico-php-alarmes-luizassano/public/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
    <div class="container">
        <h2 class="mb-4">Register New Equipment</h2>

        <?php if (isset($error)) : ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="/teste-tecnico-php-alarmes-luizassano/public/?route=equipment/create">
            <div class="mb-3">
                <label for="name" class="form-label">Equipment Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>

            <div class="mb-3">
                <label for="serial_number" class="form-label">Serial Number</label>
                <input type="text" class="form-control" name="serial_number" required>
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select name="type" class="form-select" required>
                    <option value="">-- Select Type --</option>
                    <option value="Voltage">Voltage</option>
                    <option value="Current">Current</option>
                    <option value="Oil">Oil</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Create Equipment</button>
            <a href="/teste-tecnico-php-alarmes-luizassano/public/?route=equipment" class="btn btn-secondary">Back to List</a>
        </form>
    </div>
</body>
</html>