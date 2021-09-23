#!/bin/bash
x=0
while [ $x -ge 0 ]; do
  ((x++))
  clear
  echo "Runcounter: $x"
  python3 poller.py
  sleep 10
done
