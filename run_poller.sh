#!/bin/bash
x=0
while [ $x -ge 0 ]; do
  ((x++))
  echo "Runcounter: $x"
  python3 poller.py
  sleep 5
done
