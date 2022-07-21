// https://www.codingame.com/training/medium/rubik%C2%AE

#include <Foundation/Foundation.h>

int main(int argc, const char * argv[]) {
    int n;
    scanf("%d", &n);
    printf([@"%d\n" UTF8String], n>1?6*n*(n-2)+8:1);
}
// Write an action using printf(). DON'T FORGET THE TRAILING NEWLINE \n
// To debug: fprintf(stderr, [@"Debug messages\n" UTF8String]);

// #include <Foundation/Foundation.h>
// int main(){system([@"python3 -c\"n=int(input());print(6*n*(n-2)+8 if n>1 else 1)\"" UTF8String]);}
