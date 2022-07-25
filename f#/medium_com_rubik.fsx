(* https://www.codingame.com/training/medium/rubik%C2%AE *)

open System

let N = int(Console.In.ReadLine())
let mutable ans = 0
if (N = 1) then
    ans <- 1
else
    ans <- 6 * N * (N - 2) + 8
printfn "%i" ans
(* Write an action using printfn *)
(* To debug: eprintfn "Debug message" *)
