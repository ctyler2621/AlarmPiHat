#!/bin/bash
x=0
while $x; do
  ((x++))
  echo "Runcounter: $x"
  python3 poller.py
  sleep 5
done
