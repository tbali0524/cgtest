// https://www.codingame.com/training/medium/rubik%C2%AE

using System;

class Solution
{
    static void Main(string[] args)
    {
        int n=int.Parse(Console.ReadLine());
        Console.WriteLine(n>1?6*n*(n-2)+8:1);
    }
}
// Write an action using Console.WriteLine()
// To debug: Console.Error.WriteLine("Debug messages...");

/*
using System.Diagnostics;
class S
{
    static void Main()
    {
        Process.Start(new ProcessStartInfo(
            @"python3","-c\"n=int(input());print(6*n*(n-2)+8 if n>1 else 1)\""));
    }
}
*/
