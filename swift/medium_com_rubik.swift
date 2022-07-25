// https://www.codingame.com/training/medium/rubik%C2%AE

import Glibc
import Foundation

public struct StderrOutputStream: TextOutputStream {
    public mutating func write(_ string: String) { fputs(string, stderr) }
}
public var errStream = StderrOutputStream()

let N = Int(readLine()!)!
var ans = 0;
if (N > 1) {
    ans = 6 * N * (N - 2) + 8;
} else {
    ans = 1;
}
print(ans);
// Write an action using print("message...")
// To debug: print("Debug messages...", to: &errStream)
