<?php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: login.php");
  exit;
}

$username = $_SESSION["username"];
$date_err = "";

require_once "config.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty(trim($_POST['task-date']))) {
    $date_err = "Masukkan deadline.";
  }

  if (isset($_POST['task-name']) and (empty($date_err))) {
    $task_name = mysqli_real_escape_string($link, $_POST['task-name']);
    $task_date = mysqli_real_escape_string($link, $_POST['task-date']);
    $query = "INSERT INTO tasks (username, name, deadline) VALUES ('$username', '$task_name', '$task_date')";
    mysqli_query($link, $query);
  }

  if (isset($_POST['delete-index'])) {
    $delete_index = mysqli_real_escape_string($link, $_POST['delete-index']);
    $query = "DELETE FROM tasks WHERE id = $delete_index AND username = '$username'";
    mysqli_query($link, $query);

  }

  if (isset($_POST['completed-index'])) {
    $completed_index = mysqli_real_escape_string($link, $_POST['completed-index']);
    $query = "UPDATE tasks SET completed = 1 WHERE id = $completed_index AND username = '$username'";
    mysqli_query($link, $query);

  }

  if (isset($_POST['restore-index'])) {
    $restore_index = mysqli_real_escape_string($link, $_POST['restore-index']);
    $query = "UPDATE tasks SET completed = 0 WHERE id = $restore_index AND username = '$username'";
    mysqli_query($link, $query);
  }

  if (isset($_POST['delete-completed'])) {
    $query = "DELETE FROM tasks WHERE completed = 1 AND username = '$username'";
    mysqli_query($link, $query);
    header("Location: todo.php");
  }

  header("Location: todo.php");
  exit;
}

$query = "SELECT * FROM tasks WHERE completed = 0 AND username = '$username' ORDER BY deadline ASC";
$result = mysqli_query($link, $query);
$tasks = [];
while ($row = mysqli_fetch_assoc($result)) {
  $tasks[$row['id']] = $row['name'] . ' - ' . $row['deadline'];
}

$query = "SELECT * FROM tasks WHERE completed = 1 AND username = '$username' ORDER BY deadline ASC";
$result = mysqli_query($link, $query);
$completed_tasks = [];
while ($row = mysqli_fetch_assoc($result)) {
  $completed_tasks[$row['id']] = $row['name'] . ' - ' . $row['deadline'];
}

mysqli_close($link);

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <title>To Do List</title>
  <meta name="HandheldFriendly" content="true" />
  <meta name="viewport" content="width=device-width, maximum-scale=1.0, user-scalable=yes" />
  <link rel="stylesheet" type="text/css" href="styles/todo.css" />
  <link rel="stylesheet" type="text/css" href="styles/productsans.css" />
  <link rel="icon" href="img/moai.png" type="image/x-icon">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
  <div class="container">
    <div class="child mt-4">
      <div class="flex-item mb-3">
        <h2>
          <?php echo ($username) ?>'s To Do Lists
        </h2>
        <a href="welcome.php" class="btn btn-primary">Dashboard</a>
        <a href="logout.php" class="btn btn-danger">Log Out</a>
      </div>
      <div class="flex-item mb-2">
        <form action="todo.php" method="POST">
          <div class="form-group" style="text-align: left">
            <label for="task-name">Add New Task:</label>
            <input type="text" class="form-control" id="task-name" name="task-name" placeholder="Add Task Name">
          </div>
          <div class="form-group" style="text-align: left">
            <label for="deadline">Deadline:</label>
            <input type="date" class="form-control" id="deadline" name="task-date">
          </div>
          <button type="submit" class="btn btn-success mb-2">Submit</button>
          <?php
          if (!empty($date_err)) {
            echo '<div class="alert alert-danger text-center alert-center">' . $date_err . '</div>';
          }
          ?>
        </form>
        <form action="todo.php" method="POST" style="display: inline-block;">
          <input type="hidden" name="delete-completed">
          <button class="btn btn-info mb-3">Delete Completed</button>
        </form>
      </div>
      <div class="flex-item">
        <h1>Tasks</h1>
        <?php if (empty($tasks)): ?>
          <p>There's no task to do.</p>
        <?php else: ?>
          <ol>
            <?php foreach ($tasks as $key => $task): ?>
              <li class="mb-2">
                <?= $task ?>
                <form action="todo.php" method="POST" style="display: inline-block;">
                  <input type="hidden" value="<?= $key; ?>" name="completed-index">
                  <button class="btn btn-outline-dark btn-sm">Done</button>
                </form>
                <form action="todo.php" method="POST" style="display: inline-block;">
                  <input type="hidden" value="<?= $key; ?>" name="delete-index">
                  <button class="btn btn-outline-danger btn-sm">Delete</button>
                </form>
              </li>
            <?php endforeach ?>
          </ol>
        <?php endif ?>
      </div>
      <div class="flex-item">
        <h1>Completed Task</h1>
        <?php if (empty($completed_tasks)): ?>
          <p>There's no task completed.</p>
        <?php else: ?>
          <ul>
            <?php foreach ($completed_tasks as $key => $task): ?>
              <li>
                <s>
                  <?= $task ?>
                </s>
                <form action="todo.php" method="POST" style="display: inline-block;">
                  <input type="hidden" value="<?= $key; ?>" name="restore-index">
                  <button class="btn btn-outline-warning btn-sm">Restore</button>
                </form>
              </li>
            <?php endforeach ?>
          </ul>
        <?php endif ?>
      </div>
    </div>
</body>

</html>