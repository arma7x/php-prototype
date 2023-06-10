<?php

  // RESTRICT ERROR REPORT
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

  $mysqli = new mysqli('localhost', 'root', 'rootroot', 'test');
  if ($mysqli->connect_errno) {
    throw $mysqli->connect_error;
  }

  function generateTrackerCode($mysqli, $uid, $num_of_req): array {
    $exceptionMessage = "";
    $track_codes = [];
    $mysqli->begin_transaction();
    try {
      $column = 'track_code';
      $result = $mysqli->query(sprintf("SELECT * from last_code_keeper WHERE name = '%s' LIMIT 1 FOR UPDATE", $mysqli->real_escape_string($column)));
      $last_track_code = (int) $result->fetch_assoc()['value'];
      echo "@$uid: last_track_code is $last_track_code" . PHP_EOL;
      $track_code = $last_track_code;
      $i=1;
      for (;$i<=$num_of_req;$i++) {
        $track_code = $last_track_code + $i;
        $track_code_string = sprintf("%010d", $track_code);
        $stmt = $mysqli->prepare("INSERT into parcels (id, owner, track_code) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $track_code, $uid, $track_code_string);
        $stmt->execute();
        $track_codes[] = $track_code_string;
      }
      $stmt = $mysqli->prepare("UPDATE last_code_keeper SET value = ? WHERE name = ?");
      $stmt->bind_param('is', $track_code, $column);
      $stmt->execute();
      $mysqli->commit();
    } catch (\Exception $exception) {
      $mysqli->rollback();
      $exceptionMessage = $exception->getMessage();
      $track_codes = [];
    } finally {
      $mysqli->close();
    };
    return ["result" => $track_codes, "error" => $exceptionMessage];
  }
