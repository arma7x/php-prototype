#!/bin/bash

> report
start=`date +%s.%N`
echo "Start: $start" >> report

exec_test() {
  sleep 1
  ERROR=$(php "./test.php" 2>&1 > /dev/null)
  if [ ! -z "$ERROR" ]; then
    echo $ERROR
  fi
}

for i in {1..100}
do
  exec_test &
done

wait
end=`date +%s.%N`
echo "End  : $end" >> report
runtime=$( echo "$end - $start" | bc -l )
echo "Execution time: $runtime" >> report
cat ./report
