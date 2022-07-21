// https://www.codingame.com/training/medium/rubik%C2%AE

use std::io;

macro_rules! parse_input {
    ($x:expr, $t:ident) => ($x.trim().parse::<$t>().unwrap())
}

fn main() {
    let mut input_line = String::new();
    io::stdin().read_line(&mut input_line).unwrap();
    let n = parse_input!(input_line, i32);
    let mut ans = 1;
    if n > 1 {
        ans = 6 * n * (n - 2) + 8;
    }
    println!("{}", ans);
}
// To debug: eprintln!("Debug message...");

/*
use std::process::Command;
fn main() {
    let p = Command::new("python3")
        .arg("-c\"n=int(input());print(6*n*(n-2)+8 if n>1 else 1)\"")
        .output()
        .expect("");
}
*/
