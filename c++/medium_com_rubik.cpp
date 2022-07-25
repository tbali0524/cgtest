// https://www.codingame.com/training/medium/rubik%C2%AE

#include <iostream>
using namespace std;

int main()
{
    int n;
    std::cin>>n;
    std::cout<<(n>1?6*n*(n-2)+8:1)<<"\n";
}
// Write an action using cout. DON'T FORGET THE "<< endl"
// To debug: cerr << "Debug messages..." << endl;

// #include <stdlib.h>
// main(){system("python3 -c\"n=int(input());print(6*n*(n-2)+8 if n>1 else 1)\"");}
