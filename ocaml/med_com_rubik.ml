(* https://www.codingame.com/training/medium/rubik%C2%AE *)

let n = int_of_string (input_line stdin) in
let ans = if (n == 1) then 1 else (6 * n * (n - 2) + 8) in
print_endline (string_of_int ans);
(* Write an action using print_endline *)
(* To debug: prerr_endline "Debug message"; *)
