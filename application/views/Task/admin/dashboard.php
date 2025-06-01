<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<?php
    $username = $this->session->userdata('username');
?>
<nav class="navbar bg-light p-3 mb-4">
    <div class="container-fluid justify-content-end">
        <?php if ($username): ?>
            <span class="me-3 fw-semibold">👋 Welcome, <?= htmlspecialchars($username) ?></span>
            <a href="<?= base_url('index.php/auth/logout') ?>" class="btn btn-outline-danger btn-sm">Logout</a>
        <?php endif; ?>
    </div>
</nav>

<div class="container my-5">
    <h1 class="mb-4">Admin Dashboard</h1>
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Tasks</h5>
                    <p class="card-text display-4"><?= $total_tasks ?? 0 ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Completed Tasks</h5>
                    <p class="card-text display-4"><?= $completed_tasks ?? 0 ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Overdue Tasks</h5>
                    <p class="card-text display-4"><?= $overdue_tasks ?? 0 ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <a href="<?= base_url('index.php/tasks/create') ?>" class="btn btn-primary">Add New Task</a>
    </div>
    <form method="get" class="row g-3 mb-4">
        <div class="col-md-4">
            <label class="form-label">Filter by User</label>
            <select name="user_id" class="form-select">
                <option value="">-- All Users --</option>
                <?php foreach($users as $user): ?>
                    <option value="<?= $user->id ?>" <?= (isset($_GET['user_id']) && $_GET['user_id'] == $user->id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($user->username) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
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
            <a href="<?= base_url('index.php/dashboard') ?>" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>

    <h2>Tasks List</h2>
    <table class="table table-bordered mb-5">
        <thead>
            <tr>
                <th>Task</th>
                <th>Assigned To</th>
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
                        <td><?= htmlspecialchars($task->assigned_user) ?></td>
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
								<form method="post" action="<?= base_url('index.php/tasks/status/'.$task->id) ?>">
									<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
									<select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
										<option value="pending"     <?= $task->status == 'pending'     ? 'selected' : '' ?>>Pending</option>
										<option value="completed"   <?= $task->status == 'completed'   ? 'selected' : '' ?>>Completed</option>
										<option value="in_progress" <?= $task->status == 'in_progress' ? 'selected' : '' ?>>in_progress</option>
									</select>
								</form>
								<a href="<?= base_url('index.php/tasks/show/'.$task->id) ?>" class="btn btn-sm btn-info text-white">Show</a>
								<a href="<?= base_url('index.php/tasks/edit/'.$task->id) ?>" class="btn btn-sm btn-warning text-white">Edit</a>
								<form method="post" action="<?= base_url('index.php/tasks/delete/'.$task->id) ?>" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                        <input  type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
									<button type="submit" class="btn btn-sm btn-danger">Delete</button>
								</form>
							</div>
						</td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center">No tasks found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
		<nav>
		<ul class="pagination">
			<?php for ($i = 1; $i <= $total_pages; $i++): ?>
			<li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
				<a class="page-link" href="?page=<?= $i ?>
				<?php if(!empty($filters['user_id'])) echo '&user_id='.$filters['user_id']; ?>
				<?php if(!empty($filters['status'])) echo '&status='.$filters['status']; ?> 
				">
				<?= $i ?>
				</a>
			</li>
			<?php endfor; ?>
		</ul>
		</nav>
    <h2>Users List</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Username</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($users)): ?>
                <?php foreach($users as $index => $user): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($user->username) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center">No users found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

</body>
</html>



