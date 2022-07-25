#!/usr/bin/env bash

# https://www.codingame.com/training/medium/rubik%C2%AE

read n
echo $((n>1?6*n*(n-2)+8:1))
# Write an answer using echo
# To debug: echo "Debug messages..." >&2

#php -r"\$n=fgets(STDIN);echo\$n>1?6*\$n*(\$n-2)+8:1;"
