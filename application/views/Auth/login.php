<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - Task Manager</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
      <div class="card-body">
        <h3 class="card-title text-center mb-4">User Login</h3>
        <form action="<?= base_url('index.php/login-post') ?>" method="post">
			<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input
              type="text"
              name="username"
              class="form-control"
              id="username"
              placeholder="Enter your username"
              required
              autofocus
            />
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input
              type="password"
              name="password"
              class="form-control"
              id="password"
              placeholder="Enter your password"
              required
            />
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Login</button>
          </div>
        </form>
      </div>
			<?php if(isset($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
      <div class="card-footer text-center">
        <small>Don't have an account? <a href="<?= base_url('index.php/register') ?>">Register here</a></small>
      </div>
    </div>
  </div>
</body>
</html>
