<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: login.php");
  exit;
}

require_once "config.php";

$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (empty(trim($_POST["new_password"]))) {
    $new_password_err = "Masukkan password baru.";
  } elseif (!(strlen(trim($_POST["new_password"])) >= 8 and strlen(trim($_POST["new_password"])) <= 20)) {
    $new_password_err = "Password setidaknya berisi 8-20 karakter.";
  } else {
    $new_password = trim($_POST["new_password"]);
  }

  if (empty(trim($_POST["confirm_password"]))) {
    $confirm_password_err = "Tolong confirm password.";
  } else {
    $confirm_password = trim($_POST["confirm_password"]);
    if (empty($new_password_err) && ($new_password != $confirm_password)) {
      $confirm_password_err = "Password tidak sesuai.";
    }
  }

  if (empty($new_password_err) && empty($confirm_password_err)) {
    $sql = "UPDATE users SET password = ? WHERE id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
      mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);

      $param_password = password_hash($new_password, PASSWORD_DEFAULT);
      $param_id = $_SESSION["id"];

      if (mysqli_stmt_execute($stmt)) {
        session_destroy();
        header("location: login.php");
        exit();
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
<html lang="en">

<head>
  <title>Reset Password</title>
  <meta name="HandheldFriendly" content="true" />
  <meta name="viewport" content="width=device-width, maximum-scale=1.0, user-scalable=yes" />
  <link rel="stylesheet" type="text/css" href="styles/style.css" />
  <link rel="stylesheet" type="text/css" href="styles/productsans.css" />
  <link rel="icon" href="img/moai.png" type="image/x-icon">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
  <div class="login-page">
    <div class="form">
      <h2>Reset Password</h2>

      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

        <input type="password" name="new_password" placeholder="Password"
          class="form-control mt-3 <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>"
          value="<?php echo $new_password; ?>" style="font-family: 'Product Sans';">
        <span class="invalid-feedback mb-1" style="text-align: left">
          <?php echo $new_password_err; ?>
        </span>

        <input type="password" name="confirm_password" placeholder="Confirm Password"
          class="form-control mt-3 <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>"
          value="<?php echo $confirm_password; ?>" style="font-family: 'Product Sans';">
        <span class="invalid-feedback mb-1" style="text-align: left">
          <?php echo $confirm_password_err; ?>
        </span>


        <button type="submit">Submit</button>
      </form>
      <p>
        <a class="btn btn-warning btn-sm" href="welcome.php">Cancel</a>
      </p>

    </div>
</body>

</html>