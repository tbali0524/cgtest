// https://www.codingame.com/training/medium/rubik%C2%AE

package main

import "fmt"

func main() {
    var n int
    fmt.Scan(&n)
    var a int=1
    if (n>1) {
        a=6*n*(n-2)+8
    }
    fmt.Println(a)
}
// fmt.Fprintln(os.Stderr, "Debug messages...")

/*
package main
import "os/exec"
func main() {
    exec.Command("python3", "-c \"n=int(input());print(6*n*(n-2)+8 if n>1 else 1)\"")
}
*/
