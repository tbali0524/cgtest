# https://www.codingame.com/training/medium/rubik%C2%AE

use strict;
use warnings;
use 5.20.1;

select(STDOUT); $| = 1; # DO NOT REMOVE
chomp(my $n = <STDIN>);
print $n>1?6*$n*($n-2)+8:1;
print "\n";
# Write an action using print
# To debug: print STDERR "Debug messages...\n";

# system('python3 -c"n=int(input());print(6*n*(n-2)+8 if n>1 else 1)"');
