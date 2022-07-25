-- https://www.codingame.com/training/medium/rubik%C2%AE

N = tonumber(io.read())
if (N > 1) then
    ans = 6 * N * (N - 2) + 8;
else
    ans = 1;
end
print(ans)
-- Write an action using print()
-- To debug: io.stderr:write("Debug message\n")

-- os.execute("python3 -c\"n=int(input());print(6*n*(n-2)+8 if n>1 else 1)\"")
