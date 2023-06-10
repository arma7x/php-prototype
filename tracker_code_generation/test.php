<?php
  include_once("." . DIRECTORY_SEPARATOR  . "process.php");

  $bytes = random_bytes(5);
  $uid = bin2hex($bytes);
  $num_of_req = 100;
  echo "@$uid: request $num_of_req tracking code" . PHP_EOL;
  $result = generateTrackerCode($mysqli, $uid, $num_of_req);
  if ($result['error'] != '') {
    throw new \Exception($result['error']);
  }
