#!/bin/bash
# This script just runs the poller.py program at a regular interval. This could
# and probably should be done in some other manner, eitehr with cron or even
# better as a daemon.
x=0
while [ $x -ge 0 ]; do
  ((x++))
  clear
  echo "Runcounter: $x"
  python3 poller.py
  sleep 10
done
