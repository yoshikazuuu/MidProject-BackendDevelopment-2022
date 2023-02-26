<?php
require_once "config.php";

$name = $username = $password = $confirm_password = "";
$name_err = $username_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (empty(trim($_POST["name"]))) {
    $name_err = "Masukkan nama.";
  } elseif (!(strlen(trim($_POST["name"])) <= 20)) {
    $name_err = "Nama berisi 1-20 karakter.";
  } elseif (!preg_match('/^[a-zA-Z ]+$/', trim($_POST["name"]))) {
    $name_err = "Nama hanya memuat huruf dan spasi.";
  } else {
    $name = trim($_POST["name"]);
  }

  if (empty(trim($_POST["username"]))) {
    $username_err = "Tolong masukkan username.";
  } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
    $username_err = "Username hanya memuat huruf, angka, dan underscores.";
  } else {
    $sql = "SELECT id FROM users WHERE username = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
      mysqli_stmt_bind_param($stmt, "s", $param_username);

      $param_username = trim($_POST["username"]);

      if (mysqli_stmt_execute($stmt)) {
        /* store result */
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 1) {
          $username_err = "Username telah diambil.";
        } else {
          $username = trim($_POST["username"]);
        }
      } else {
        echo "Oops! Error terjadi. Tolong ulang kembali dalam beberapa saat.";
      }

      mysqli_stmt_close($stmt);
    }
  }

  if (empty(trim($_POST["password"]))) {
    $password_err = "Masukkan password.";
  } elseif (!(strlen(trim($_POST["password"])) >= 8 and strlen(trim($_POST["password"])) <= 20)) {
    $password_err = "Password berisi 8-20 karakter.";
  } else {
    $password = trim($_POST["password"]);
  }

  if (empty(trim($_POST["confirm_password"]))) {
    $confirm_password_err = "Tolong confirm password.";
  } else {
    $confirm_password = trim($_POST["confirm_password"]);
    if (empty($password_err) and ($password != $confirm_password)) {
      $confirm_password_err = "Password tidak sesuai.";
    }
  }

  if (empty($username_err) and empty($password_err) and empty($confirm_password_err)) {

    $sql = "INSERT INTO users (name, username, password) VALUES (?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
      mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_username, $param_password);

      $param_name = $name;
      $param_username = $username;

      if (mysqli_stmt_execute($stmt)) {
        header("location: login.php");
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
      <h2>Sign Up</h2>

      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

        <input type="text" name="name" placeholder="Name"
          class="form-control mt-3 <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>"
          style="font-family: 'Product Sans';">
        <span class="invalid-feedback mb-1" style="text-align: left">
          <?php echo $name_err; ?>
        </span>

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

        <input type="password" name="confirm_password" placeholder="Confirm Password"
          class="form-control mt-3 <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>"
          style="font-family: 'Product Sans';">
        <span class="invalid-feedback mb-1" style="text-align: left">
          <?php echo $confirm_password_err; ?>
        </span>


        <div class="form-group mt-3">
          <button type="submit" value="Submit" style="width: 40%">Submit</button>
          <button type="reset" value="Reset" style="width: 40%; background-color: grey" class="ml-2">Reset</button>
        </div>

        <p class="message">Sudah punya akun? <a href="login.php">Login disini</a></p>
      </form>
    </div>
</body>

</html>