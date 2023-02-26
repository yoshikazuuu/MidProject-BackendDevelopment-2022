<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["task-name"])) {
    $_SESSION["TASK"][] = $_POST["task-name"];
  }

  if (isset($_POST["completed-index"])) {
    $completed_index = $_POST["completed-index"];
    $task = $_SESSION["TASK"][$completed_index];
    $_SESSION["COMPLETED_TASK"][] = $task;
    unset($_SESSION["TASK"][$completed_index]);
    var_dump($_SESSION);
  }
}

header("Location: todo.php");