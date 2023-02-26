<?php

session_start();

require_once "config.php";

if (isset($_POST['task-name'])) {
  $task_name = mysqli_real_escape_string($link, $_POST['task-name']);
  $query = "INSERT INTO tasks (name) VALUES ('$task_name')";
  mysqli_query($link, $query);
}

$query = "SELECT * FROM tasks WHERE completed = 0";
$result = mysqli_query($link, $query);
$tasks = [];
while ($row = mysqli_fetch_assoc($result)) {
  $tasks[$row['id']] = $row['name'];
}

if (isset($_POST['completed-index'])) {
  $completed_index = mysqli_real_escape_string($link, $_POST['completed-index']);
  $query = "UPDATE tasks SET completed = 1 WHERE id = $completed_index";
  mysqli_query($link, $query);
}

$query = "SELECT * FROM tasks WHERE completed = 1";
$result = mysqli_query($link, $query);
$completed_tasks = [];
while ($row = mysqli_fetch_assoc($result)) {
  $completed_tasks[] = $row['name'];
}

mysqli_close($link);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <form action="todo.php" method="POST">
    <label for="">Add New Task : </label>
    <input type="text" name="task-name" placeholder="Task Name">
    <input type="submit" value="Submit">
  </form>
  <h1>Tasks</h1>
  <ol>
    <?php foreach ($tasks as $key => $task): ?>
      <li>
        <?= $task ?>
        <form action="todo.php" method="POST" style="display: inline-block;">
          <input type="hidden" value="<?= $key; ?>" name="completed-index">
          <button>Done</button>
        </form>
      </li>
    <?php endforeach ?>
  </ol>
  <h1>Completed Task</h1>
  <ul>
    <?php foreach ($completed_tasks as $task): ?>
      <li>
        <s>
          <?= $task ?>
        </s>
      </li>
    <?php endforeach ?>
  </ul>
</body>

</html>
