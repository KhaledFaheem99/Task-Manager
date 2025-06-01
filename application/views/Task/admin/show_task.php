<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Task Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container my-5">
    <h1 class="mb-4">Task Details</h1>

    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?= htmlspecialchars($task->title) ?></h4>
            <p class="card-text"><strong>Description:</strong> <?= nl2br(htmlspecialchars($task->description)) ?></p>
            <p class="card-text"><strong>Assigned To:</strong> <?= htmlspecialchars($task->assigned_user) ?></p>
            <p class="card-text"><strong>Due Date:</strong> <?= date('Y-m-d', strtotime($task->due_date)) ?></p>
            <p class="card-text"><strong>Status:</strong> 
			<?php if ($task->status == 'completed'): ?>
				<span class="badge bg-success">Completed</span>
			<?php elseif ($task->status == 'pending'): ?>
                <span class="badge bg-warning text-dark">Pending</span>
            <?php elseif ($task->status == 'in_progress'): ?>
                <span class="badge bg-info text-dark">In Progress</span>
			<?php endif; ?>
            </p>
        </div>
    </div>

        <a href="<?= base_url('index.php/dashboard') ?>" class="btn btn-secondary mt-4">Back to Dashboard</a>
</div>
</body>
</html>
