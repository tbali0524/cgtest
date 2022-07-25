// https://www.codingame.com/training/medium/rubik%C2%AE

import 'dart:io';

void main() {
    int n=int.parse(stdin.readLineSync()??'');
    print(n>1?6*n*(n-2)+8:1);
}
// Write an action using print()
// To debug: stderr.writeln('Debug messages...');
