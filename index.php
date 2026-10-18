<?php
  session_start();

  if (!isset($_SESSION['username'])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ZOV - Log in</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
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
    <form method="post" action="app/http/auth.php">
      <div class="text-center mb-4">
        <img src="./images/logo.png" class="auth-logo mb-3">
        <h2 class="h3 mb-3 font-weight-normal">Log in to ZOV</h2>
      </div>
      
      <?php if (isset($_GET['error'])) { ?>
        <div class="alert alert-danger" role="alert">
          <?php echo htmlspecialchars($_GET['error']);?>
        </div>
      <?php } ?>
      
      <?php if (isset($_GET['success'])) { ?>
        <div class="alert alert-success" role="alert">
          <?php echo htmlspecialchars($_GET['success']);?>
        </div>
      <?php } ?>
      
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      
      <div class="d-flex justify-content-between align-items-center">
        <button type="submit" class="btn btn-primary px-4">Log in</button>
        <a href="signup.php" class="auth-link">Sign up</a>
      </div>
    </form>
  </div>
</body>
</html>
<?php
  } else {
    header("Location: home.php");
    exit;
  }
?>