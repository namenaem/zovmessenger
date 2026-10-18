<?php
  session_start();

  if (!isset($_SESSION['username'])) {
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ZOV - Sign up</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
  <link rel="stylesheet" href="css/style.css">
  <link rel="icon" href="./images/logo.png">
  <style>
    .auth-container {
      max-width: 400px;
      width: 100%;
    }
    .auth-logo {
      width: 80px;
      height: 80px;
    }
    .auth-link {
      text-decoration: none;
      margin-left: 10px;
    }
  </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
  <div class="auth-container p-4 shadow-sm rounded bg-white">
    <form method="post" action="app/http/signup.php" enctype="multipart/form-data">
      <div class="text-center mb-4">
        <img src="./images/logo.png" class="auth-logo mb-3">
        <h2 class="h3 mb-3 font-weight-normal">Sign up for ZOV</h2>
      </div>

      <!-- Error message -->
      <?php if (isset($_GET['error'])) { ?>
        <div class="alert alert-danger" role="alert">
          <?= htmlspecialchars($_GET['error']) ?>
        </div>
      <?php } ?>

      <!-- Success message -->
      <?php if (isset($_GET['success'])) { ?>
        <div class="alert alert-success" role="alert">
          <?= htmlspecialchars($_GET['success']) ?>
        </div>
      <?php } ?>

      <!-- Field: Name -->
      <?php
        $name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '';
        $username = isset($_GET['username']) ? htmlspecialchars($_GET['username']) : '';
      ?>
      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= $name ?>" required>
      </div>

      <!-- Field: Username -->
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" value="<?= $username ?>" required>
      </div>

      <!-- Field: Password -->
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>

      <!-- Field: Profile photo -->
      <div class="mb-3">
        <label for="pp" class="form-label">Profile photo</label>
        <input type="file" class="form-control" id="pp" name="pp">
      </div>

      <!-- Buttons: Sign up and Log in -->
      <div class="d-flex justify-content-between align-items-center">
        <button type="submit" class="btn btn-primary px-4">Sign up</button>
        <a href="index.php" class="auth-link">Log in</a>
      </div>
    </form>
  </div>
</body>
</html>

<?php
  } else {
    // If the user is already logged in - redirect to home.php
    header("Location: home.php");
    exit;
  }
?>