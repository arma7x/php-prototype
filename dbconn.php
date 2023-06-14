<?php

  // RESTRICT ERROR REPORT
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

  $dbName = "test";

  $mysqli = new mysqli('localhost', 'root', 'rootroot', $dbName);
  if ($mysqli->connect_errno) {
    echo "Failed to connect to MYSQL: " . $mysqli->connect_error;
  }

  $string = "1";

  $i=1;
  $result = $mysqli->query(sprintf("SELECT * from persons WHERE id = %s", $mysqli->real_escape_string($string)));
  if (!$result) {
    echo("Error description 1: " . $mysqli->error . PHP_EOL);
  } else {
    while ($row = $result->fetch_assoc()) {
      //var_dump($i, $row);
      $i++;
    }
  }

  $i=1;
  $result_func = mysqli_query($mysqli, sprintf("SELECT * from persons WHERE id = %s", mysqli_real_escape_string($mysqli, $string)));
  if (!$result_func) {
    echo("Error description 2: " . mysqli_error($mysqli) . PHP_EOL);
  } else {
    while ($row = $result_func->fetch_assoc()) {
      // var_dump($i, $row);
      $i++;
    }
  }

  $stmt = $mysqli->prepare("INSERT into persons (name, age) VALUES (?, ?)");
  if (!$stmt) {
    echo("Invalid query");
  } else {
    $name = "2";
    $age = 2;
    $stmt->bind_param("si", $name, $age);
    $execute_bool = $stmt->execute();
    echo 'execute_bool: ' . ($execute_bool ? 'TRUE' : 'FALSE') . PHP_EOL;
    echo 'affected_rows: ' . $stmt->affected_rows . PHP_EOL;
    if (COUNT($stmt->error_list) > 0) {
      foreach ($stmt->error_list as $value) {
        var_dump($value);
      }
    }
    $stmt->close();
  }

  $mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

  try {
      $id = 1;
      $name = 'Unknown';
      $age = 1;
      $stmt = $mysqli->prepare("INSERT into persons (id ,name, age) VALUES (?, ?, ?)");
      $stmt->bind_param('isi', $id, $name, $age);
      $stmt->execute();
      $mysqli->commit();
      $stmt->close();
  } catch (mysqli_sql_exception $exception) {
      $mysqli->rollback();
      var_dump($exception->getMessage());
  }

  function abc(...$abc) {
    var_dump($abc);
  }

  $params = [1, 2, 3];

  abc(...$params);
  abc(1, 2, 3);
