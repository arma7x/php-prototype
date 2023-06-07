<?php

  // TODO: free result and close

  $dbName = "test";

  $mysqli = new mysqli('localhost', 'root', 'rootroot', $dbName);
  if ($mysqli->connect_errno) {
    echo "Failed to connect to MYSQL: " . $mysqli->connect_error;
  }

  $stmt = $mysqli->prepare("SELECT count(*) as exist FROM information_schema.TABLES WHERE (TABLE_SCHEMA = '$dbName') AND (TABLE_NAME = 'files');");
  if ($stmt->execute()) {
    $result = $stmt->get_result();
    $output = $result->fetch_assoc();
    if ($output['exist'] === 0) {
      $sqlCreateTableTest = "CREATE TABLE `files` (
        `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `fileBlob` longblob NOT NULL,
        `hash` text NULL
      );";
      $stmtCreateTableTest = $mysqli->prepare($sqlCreateTableTest);
      $stmtCreateTableTest->execute();
    }
  }

  if (isset($_POST['operation']) && $_POST['operation'] === 'findDuplicateFile') {
    $data = json_decode($_POST['data'], JSON_PRETTY_PRINT);
    $stmtFindHash = "SELECT id, SHA2(fileBlob, 256) as hash from files
    HAVING hash = ? LIMIT 1;";
    $query = $mysqli->prepare($stmtFindHash);
    $query->bind_param('s', $data['hashHex']);
    header("Content-Type: application/json; charset=utf-8");
    if ($query->execute()) {
      $result = $query->get_result();
      $output = $result->fetch_assoc(); // fetch_all(MYSQLI_ASSOC) === rows
      if ($output == NULL) {
        http_response_code(200);
        $data['result'] = 'NOT EXIST';
      } else {
        http_response_code(400);
        $data['result'] = $output;
      }
    } else {
      http_response_code(500);
      $data['result'] = $stmt_test->error;
    }
    echo json_encode($data);
    die;
  }
