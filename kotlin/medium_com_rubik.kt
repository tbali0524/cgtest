// https://www.codingame.com/training/medium/rubik%C2%AE

import java.util.*

fun main() {
    val input = Scanner(System.`in`)
    val N = input.nextInt()
    var ans = 1
    if (N > 1) {
        ans = 6 * N * (N - 2) + 8;
    }
    println(ans)
}
// Write an action using println()
// To debug: System.err.println("Debug messages...");
