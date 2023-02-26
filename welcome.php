<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Dashboard</title>
  <meta name="HandheldFriendly" content="true" />
  <meta name="viewport" content="width=device-width, maximum-scale=1.0, user-scalable=yes" />
  <link rel="stylesheet" type="text/css" href="style.css" />
  <link rel="stylesheet" type="text/css" href="productsans.css" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
  <div class="login-page">
    <div class="form">

      <h2 class="mb-5">Selamat datang <b>
          <?php echo htmlspecialchars($_SESSION["username"]); ?>
        </b>!</h2>
      <p>

        <a href="todo.php" class="btn btn-primary btn-block">To-Do-List</a>
        <a href="reset-password.php" class="btn btn-warning btn-block">Reset Password</a>
        <a href="logout.php" class="btn btn-danger btn-block">Log Out</a>

      </p>
    </div>
  </div>
</body>

</html>