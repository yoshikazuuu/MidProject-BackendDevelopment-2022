<?php

session_start();

$tasks = $_SESSION["TASK"];
$completed_task = $_SESSION["COMPLETED_TASK"];

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
  <form action="request.php" method="POST">
    <label for="">Add New Task : </label>
    <input type="text" name="task-name" placeholder="Task Name">
    <input type="submit" value="Submit">
  </form>
  <h1>Tasks</h1>
  <ol>
    <?php foreach ($tasks as $key => $task): ?>
      <li>
        <?= $task ?>
        <form action="request.php" method="POST" style="display: inline-block;">
          <input type="hidden" value="<?= $key; ?>" name="completed-index">
          <button>Done</button>
        </form>
      </li>
    <?php endforeach ?>
  </ol>
  <h1>Completed Task</h1>
  <ul>
    <?php foreach ($completed_task as $task): ?>
      <li>
        <s>
          <?= $task ?>
        </s>
      </li>
    <?php endforeach ?>
  </ul>
</body>

</html>