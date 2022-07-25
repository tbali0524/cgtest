<?php

// https://www.codingame.com/training/medium/rubik%C2%AE

declare(strict_types=1);

namespace TBali\CodinGame\Rubik;

fscanf(STDIN, '%d', $n);
echo $n > 1 ? 6 * $n * ($n - 2) + 8 : 1, PHP_EOL;

// system('python3 -c"n=int(input());print(6*n*(n-2)+8 if n>1 else 1)"');
