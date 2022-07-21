// https://www.codingame.com/training/medium/rubik%C2%AE

import std.stdio;

void main()
{
    int n;
    readf!"%d"(n);
    writeln(n>1?6*n*(n-2)+8:1);
}
// Write an action using writeln().
// To debug: stderr.writeln("Debug messages...");

// import std;auto p=execute(["python3", "-c\"n=int(input());print(6*n*(n-2)+8 if n>1 else 1)\""]);
