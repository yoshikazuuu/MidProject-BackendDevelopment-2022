<?php
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
  header("location: welcome.php");
  exit;
}

require_once "config.php";

$username = $password = "";
$username_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (empty(trim($_POST["username"]))) {
    $username_err = "Please enter username.";
  } else {
    $username = trim($_POST["username"]);
  }

  if (empty(trim($_POST["password"]))) {
    $password_err = "Please enter your password.";
  } else {
    $password = trim($_POST["password"]);
  }

  if (empty($username_err) && empty($password_err)) {
    $sql = "SELECT id, username, password FROM users WHERE username = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
      mysqli_stmt_bind_param($stmt, "s", $param_username);

      $param_username = $username;

      if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 1) {
          mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
          if (mysqli_stmt_fetch($stmt)) {
            if (password_verify($password, $hashed_password)) {
              session_start();

              $_SESSION["loggedin"] = true;
              $_SESSION["id"] = $id;
              $_SESSION["username"] = $username;

              header("location: welcome.php");
            } else {
              $login_err = "Invalid username or password.";
            }
          }
        } else {
          $login_err = "Invalid username or password.";
        }
      } else {
        echo "Oops! Something went wrong. Please try again later.";
      }

      mysqli_stmt_close($stmt);
    }
  }

  mysqli_close($link);
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Login Page</title>
  <meta name="HandheldFriendly" content="true" />
  <meta name="viewport" content="width=device-width, maximum-scale=1.0, user-scalable=yes" />
  <link rel="stylesheet" type="text/css" href="style.css" />
  <link rel="stylesheet" type="text/css" href="productsans.css" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
  <div class="login-page">
    <div class="form">
      <h2>Login</h2>

      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="text" name="username" placeholder="Username"
          class="form-control mt-3 <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>"
          value="<?php echo $username; ?>" style="font-family: 'Product Sans';">
        <span class="invalid-feedback mb-1" style="text-align: left">
          <?php echo $username_err; ?>
        </span>

        <input type="password" name="password" placeholder="Password"
          class="form-control mt-3 <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"
          style="font-family: 'Product Sans';">
        <span class="invalid-feedback mb-1" style="text-align: left">
          <?php echo $password_err; ?>
        </span>

        <button type="submit">Log in</button>
      </form>

      <?php if (!empty($login_err)): ?>
        <p class="alert alert-danger" id="wrong-credential">
          <?php echo $login_err; ?>
        </p>
      <?php endif; ?>

      <p class="message">Belum terdaftar? <a href="register.php">Buat akun disini</a></p>
    </div>
  </div>
</body>

</html>