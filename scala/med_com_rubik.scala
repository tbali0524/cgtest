// https://www.codingame.com/training/medium/rubik%C2%AE

import scala.util._
import scala.io.StdIn._

object Solution extends App {
    val N = readInt
    var ans = 0
    if (N > 1) then {
        ans = 6 * N * (N - 2) + 8;
    } else {
        ans = 1;
    }
    println(ans)
}
// To debug: Console.err.println("Debug messages...")
