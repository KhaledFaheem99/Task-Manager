<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>User Dashboard</h1>
        <div>
            <span class="me-3">Welcome, <?= htmlspecialchars($this->session->userdata('username')) ?></span>
            <a href="<?= base_url('index.php/auth/logout') ?>" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>

    <h2>Your Tasks</h2>
    <form method="get" class="row g-3 mb-4">
        <div class="col-md-4">
            <label class="form-label">Filter by Status</label>
            <select name="status" class="form-select">
                <option value="">-- All Statuses --</option>
                <option value="pending"     <?= @$_GET['status'] == 'pending'     ? 'selected' : '' ?>>Pending</option>
                <option value="in_progress" <?= @$_GET['status'] == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                <option value="completed"   <?= @$_GET['status'] == 'completed'   ? 'selected' : '' ?>>Completed</option>
            </select>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-outline-primary me-2">Filter</button>
            <a href="<?= base_url('index.php/user_dashboard') ?>" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>
    <table class="table table-bordered mb-5">
        <thead>
            <tr>
                <th>Task</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tasks)): ?>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?= htmlspecialchars($task->title) ?></td>
                        <td>
                            <?php
                                if ($task->status == 'completed') {
                                    echo '<span class="badge bg-success">Completed</span>';
                                } elseif ($task->status == 'pending') {
                                    echo '<span class="badge bg-warning text-dark">Pending</span>';
                                } elseif ($task->status == 'in_progress') {
                                    echo '<span class="badge bg-info text-dark">In Progress</span>';
                                }
                            ?>
                        </td>
                        <td><?= date('Y-m-d', strtotime($task->due_date)) ?></td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <form method="post" action="<?= base_url('index.php/tasks/status/user/'.$task->id) ?>">
                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="pending"     <?= $task->status == 'pending'     ? 'selected' : '' ?>>Pending</option>
                                        <option value="in_progress" <?= $task->status == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                                        <option value="completed"   <?= $task->status == 'completed'   ? 'selected' : '' ?>>Completed</option>
                                    </select>
                                </form>
                                <a href="<?= base_url('index.php/tasks/show/user/'.$task->id) ?>" class="btn btn-sm btn-info text-white">Show</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center">No tasks found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
		<nav>
		<ul class="pagination">
			<?php for ($i = 1; $i <= $total_pages; $i++): ?>
			<li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
				<a class="page-link" href="?page=<?= $i ?>
				<?php if(!empty($filter['status'])) echo '&status='.$filter['status']; ?> 
				">
				<?= $i ?>
				</a>
			</li>
			<?php endfor; ?>
		</ul>
		</nav>

</div>
</body>
</html>
