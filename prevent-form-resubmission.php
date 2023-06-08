<?php
//Set no caching
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  header("Location: /prevent-form-resubmission.php");
  exit();
}
?>
<form method="POST" href="/test.php">
  <button type="submit">prevent form resubmission</button>
</form>
