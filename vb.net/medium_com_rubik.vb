' https://www.codingame.com/training/medium/rubik%C2%AE

Module Solution
    Sub Main ()
        Dim N as Integer
        N = Console.ReadLine()
        Dim ans as Integer
        If (N > 1) Then
            ans = 6 * N * (N - 2) + 8
        Else
            ans = 1
        End If
        Console.WriteLine(ans)
    End Sub
End Module
' Write an action using Console.WriteLine()
' To debug: Console.Error.WriteLine("Debug messages...")
