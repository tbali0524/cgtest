! https://www.codingame.com/training/medium/rubik%C2%AE
! Fortan is officially not supported on CodinGame, but can be run via bash

program sol
  INTEGER :: n, a
  READ (*,*) n
  IF (n > 1) THEN
    a = 6*n*(n-2)+8
  ELSE
    a = 1
  END IF
  WRITE(*, '(I0)') a
end program sol
