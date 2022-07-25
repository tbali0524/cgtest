// https://www.codingame.com/training/medium/rubik%C2%AE

#include <stdio.h>
int main()
{
    int n;
    scanf("%d",&n);
    printf("%d\n",n>1?6*n*(n-2)+8:1);
    return 0;
}
// Write an action using printf(). DON'T FORGET THE TRAILING \n
// To debug: fprintf(stderr, "Debug messages...\n");

// main(){system("python3 -c\"n=int(input());print(6*n*(n-2)+8 if n>1 else 1)\"");}
