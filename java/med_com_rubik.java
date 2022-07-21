// https://www.codingame.com/training/medium/rubik%C2%AE

import java.util.*;

class Solution {

    public static void main(String args[]) {
        Scanner in = new Scanner(System.in);
        int n = in.nextInt();
        System.out.println(n>1?6*n*(n-2)+8:1);
        in.close();
    }
}
// To debug: System.err.println("Debug messages...");

/*
class Solution {

    public static void main(String args[]) {
        try {
            //ProcessBuilder pb = new ProcessBuilder("python3", "-c", "\"n=int(input());print(n**3 if n<3 else 2*n*n+2*n*(n-2)+2*(n-2)*(n-2))\"");
            //Process p = pb.start();
            Runtime.getRuntime().exec("python3 -c \"n=int(input());print(n**3 if n<3 else 2*n*n+2*n*(n-2)+2*(n-2)*(n-2))\"");
        } catch (Exception e) {}
    }
}
*/
