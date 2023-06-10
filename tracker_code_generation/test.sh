#!/bin/bash

start=`date +%s`

exec_php() {
  sleep 1
  ERROR=$(php "./test.php" 2>&1 >/dev/null)
  if [ ! -z "$ERROR" ]; then
    echo $ERROR
  fi
}

for i in {1..100}
do
  exec_php &
done

wait
end=`date +%s.%N`
runtime=$( echo "$end - $start" | bc -l )
echo "Execution time: $runtime"
