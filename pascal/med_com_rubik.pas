// https://www.codingame.com/training/medium/rubik%C2%AE

program Answer;
{$H+}
uses sysutils, classes;

// Helper to read a line and split tokens
procedure ParseIn(Inputs: TStrings) ;
var Line : string;
begin
    readln(Line);
    Inputs.Clear;
    Inputs.Delimiter := ' ';
    Inputs.DelimitedText := Line;
end;

var
    N : Int32;
    Inputs: TStringList;
    Ans : Int32;
begin
    Inputs := TStringList.Create;
    ParseIn(Inputs);
    N := StrToInt(Inputs[0]);
    if (N = 1) then
        Ans := 1
    else
        Ans := 6 * N * (N - 2) + 8;
    writeln(Ans);
    flush(StdErr); flush(output); // DO NOT REMOVE
end.
// Write an action using writeln()
// To debug: writeln(StdErr, 'Debug messages...');
