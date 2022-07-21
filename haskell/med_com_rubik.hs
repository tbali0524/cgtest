-- https://www.codingame.com/training/medium/rubik%C2%AE

import System.IO
main=do
    hSetBuffering stdout NoBuffering -- DO NOT REMOVE
    inp <- getLine
    let n = read inp
    print (if (n>1) then 6*n*(n-2)+8 else 1)
    return ()
-- hPutStrLn stderr "Debug messages..."
-- Write answer to stdout
