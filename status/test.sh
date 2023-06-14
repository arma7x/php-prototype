#! /usr/bin/bash

result=$(php "./success.php")
if (( $? == 0 )); then
  echo "Success Result: ${result}"
fi

result=$(php "./error.php" 2>&1 /dev/null)
if (( $? == 255 )); then
  echo "Error Result: ${result}"
  exit
fi

echo "Never Executed"
