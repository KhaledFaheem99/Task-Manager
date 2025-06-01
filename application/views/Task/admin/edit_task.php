<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Create Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<div class="container my-5">
    <h1 class="mb-4">Create New Task</h1>

    <form method="post" action="<?= base_url('index.php/tasks/update/' . $task->id) ?>">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
        <div class="mb-3">
            <label for="title" class="form-label">Task Title</label>
            <input 
                type="text" 
                class="form-control" 
                id="title" 
                name="title" 
                required 
				value= <?= $task->title ?>
            />
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Task Description</label>
            <textarea 
                class="form-control" 
                id="description" 
                name="description" 
                rows="4"
                placeholder="Enter task description"
				required
            ><?= $task->description ?></textarea>
        </div>

        <div class="mb-3">
            <label for="assigned_to" class="form-label">Assign To</label>
            <select class="form-select" id="assigned_to" name="assigned_to" required>
                <option value="">Select User</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user->id ?>" <?= ($task->assigned_to == $user->id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($user->username) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="due_date" class="form-label">Due Date</label>
            <input 
                type="date" 
                class="form-control" 
                id="due_date" 
                name="due_date" 
                required 
				value="<?= $task->due_date ?>"
            />
        </div>
		<?php if (isset($errors)): ?>
			<div class="alert alert-danger">
				<ul>
				<?php foreach ($errors as $error): ?>
					<li><?= htmlspecialchars($error) ?></li>
				<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
        <button type="submit" class="btn btn-primary">Update Task</button>
        <a href="<?= base_url('index.php/dashboard') ?>" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>

</body>
</html>
