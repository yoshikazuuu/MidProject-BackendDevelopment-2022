<?php
// Initialize the session
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: login.php");
  exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate new password
  if (empty(trim($_POST["new_password"]))) {
    $new_password_err = "Masukkan password baru.";
  } elseif (!(strlen(trim($_POST["new_password"])) >= 8 and strlen(trim($_POST["new_password"])) <= 20)) {
    $new_password_err = "Password setidaknya berisi 8-20 karakter.";
  } else {
    $new_password = trim($_POST["new_password"]);
  }

  // Validate confirm password
  if (empty(trim($_POST["confirm_password"]))) {
    $confirm_password_err = "Tolong confirm password.";
  } else {
    $confirm_password = trim($_POST["confirm_password"]);
    if (empty($new_password_err) && ($new_password != $confirm_password)) {
      $confirm_password_err = "Password tidak sesuai.";
    }
  }

  // Check input errors before updating the database
  if (empty($new_password_err) && empty($confirm_password_err)) {
    // Prepare an update statement
    $sql = "UPDATE users SET password = ? WHERE id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);

      // Set parameters
      $param_password = password_hash($new_password, PASSWORD_DEFAULT);
      $param_id = $_SESSION["id"];

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        // Password updated successfully. Destroy the session, and redirect to login page
        session_destroy();
        header("location: login.php");
        exit();
      } else {
        echo "Oops! Something went wrong. Please try again later.";
      }

      // Close statement
      mysqli_stmt_close($stmt);
    }
  }

  // Close connection
  mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Register Page</title>
  <meta name="HandheldFriendly" content="true" />
  <meta name="viewport" content="width=device-width, maximum-scale=1.0, user-scalable=yes" />
  <link rel="stylesheet" type="text/css" href="style.css" />
  <link rel="stylesheet" type="text/css" href="productsans.css" />
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

        <div class="form-group mt-3">
          <button type="submit" value="Submit" style="width: 40%">Submit</button>
        </div>

        <p class="message">Sudah punya akun? <a href="login.php">Login disini</a></p>
      </form>
    </div>
</body>

</html>