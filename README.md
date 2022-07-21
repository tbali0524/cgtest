# CGTest v1.0.0

A multi-language offline batch test runner for `CodinGame` solo I/O puzzles.

(c) 2022, by [TBali](https://www.codingame.com/profile/08e6e13d9f7cad047d86ec4d10c777500155033)

## Intro

[CodinGame solo puzzles](https://www.codingame.com/training) provide a fun way to practice and improve your coding skills in any of its [27 supported programming languages](https://www.codingame.com/playgrounds/40701/help-center/languages-versions).

At CG you don't have to bother about setting up any local development environment: just start to write your code directly on the CG website using your browser, run there the provided test cases (for solo puzzles), and submit your solution when you feel ready.

However, sometimes you might want to setup and use your own local dev environment. Also, having to copy (even with autosync tools) your code to the CG online IDE just to run some tests can be tedious.

__CGTest__ is a simple tool to run tests in batch mode using your local dev and runtime environments. With a single command, you can run hundreds of tests for your code, even if written in __multiple languages__, for __multiple puzzles__, and for __multiple test cases__ per puzzle.

## Command line usage

```txt
Usage:   php pgtest.php [options] [puzzles]

Options:
   --version          Display CGTest application version
   --help             Display this help message
   --dry-run          Do not run the tests; only show what tests would run
   --ansi             Use color output [default]
   --no-ansi          Disable color output
   --verbose          Increase the verbosity of messages: also show each passed tests
   --lang-versions    Show versions for all configured programming languages
   --clean            Delete temporary and output files of previous test run
   --config=FILENAME  Use configfile [default: .cgtest.php]
   --lang=LANGUAGES   Run tests in these languages (comma separated list) [default: php]

Puzzles:              Space separated list of source filenames (without extension)
                        if given, it overrides the list in the config file
                        path can be given, but no wildcards allowed
```

CGTest is a single-file _php_ script, so you need `php` (v7.4 or newer) installed on your machine to use it.

Of course, your code does not need to be in php. Any languages can be used, for which you have a local dev environment, you might just need to configure how the test runner should invoke your compiler and/or interpreter (but there is a high chance that default settings will work for you.)

## Test cases

The package already includes some test cases for several CodinGame puzzles. Most of these puzzles are rather short and simple, especially well-suited if you go to solve puzzles in __all the CG languages__.

* The input data for these test cases are in `.tests/input` directory.
* The expected test output data for these test cases are in `.tests/expected` directory.
* Running the tests generates output files in the `.tests/output` directory.
* If your code also write to the error console, you can check these (by default) in `.tests/output/_error_log.txt`.
* Running CGTest with the `--clean` option deletes all temporary and test output files from the previous run.
* You can easily add test cases for other puzzles (even outside CodinGame).
* You can change the directory structure and the naming conventions `CGTest` is using out of the box. However you will need to tweak the config file a bit.

## Configuration file

CGTest includes some sensible defaults for both its per-language, and its global settings.

You can override these by using a configuration file. The default configuration file is `.cgtest.php`, or you can provide a different name with the `--config=...` command-line option.

Note: while the configuration file os a valid `php` source code, you don't need to know php to use it. It only defines an associative array, very similar to any JSON data. All the options are documented with comments in the sample config file.

Some settings (but not all) can be also overriden via command-line arguments. If an option is set both in the config file and via the command-line, then the command line takes precedence.

## Restrictions

_CGTest_ supports only solo I/O puzzles. For any test case the input must be a fixed file (so a given line of input cannot depend on the output previously provided by the code. This means some solo and optim puzzles cannot be tested. Bot programming is also out of question.
