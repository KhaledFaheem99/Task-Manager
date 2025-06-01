<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <div class="container mt-5" style="max-width: 400px;">
    <h2 class="mb-4 text-center">Register</h2>
    <form method="post" action="<?= base_url('index.php/register-post') ?>">
			<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
      <div class="mb-3">
        <label for="username" class="form-label">User Name</label>
        <input type="text" name="username" class="form-control" id="username" required />
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" class="form-control" id="password" required />
      </div>
      <div class="mb-3">
        <label for="passwordConfirmation" class="form-label">Confirm Password</label>
        <input type="password" name="passwordConfirmation" class="form-control" id="passwordConfirmation" required />
      </div>
      <div class="text-end">
        <button type="submit" class="btn btn-primary">Register</button>
      </div>
    </form>
		<?php if (isset($errors)): ?>
			<div class="alert alert-danger">
				<ul>
					<?php foreach ($errors as $error): ?>
						<li><?= $error ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

				<?php if (isset($success)) : ?>
				<div class="alert alert-success mt-3">
						<?= $success ?>
				</div>

				<script>
						setTimeout(function () {
								window.location.href = "<?= base_url('index.php/login') ?>";
						}, 5000);
				</script>
		<?php endif; ?>
  </div>
</body>
</html>
