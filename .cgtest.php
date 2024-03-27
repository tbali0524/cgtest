<?php

/**
 * CGTest v1.16.0 by Balint Toth [TBali]
 * A multi-language offline batch test runner for CodinGame (or other) solo I/O puzzles.
 *
 * configuration file
 */

declare(strict_types=1);

namespace TBali\CGTest;

return [
    // == The following default config options can be overriden:
    // 'dry-run' => false,
    // 'run-only' => false,
    // 'alt' => false,
    // 'ansi' => true,
    // 'verbose' => true,
    // 'stats' => false,
    // 'lang-versions' => false,
    // 'clean' => false,
    // 'create' => '',
    // 'test-case' => 'all',
    // 'slowThreshold' => 5, // in seconds
    // 'inputPath' => '.tests/input/',
    // 'inputPattern' => '%p_i%t.txt',
    // 'expectedPath' => '.tests/expected/',
    // 'expectedPattern' => '%p_e%t.txt',
    // 'outputPath' => '.tests/output/',
    // 'outputPattern' => '%p_o%t_%l.txt',
    // 'buildPath' => '.tests/temp/',
    // 'debugLog' => '.tests/output/_debug_log.txt',
    // 'buildLog' => '.tests/temp/_build_log.txt',
    // == Patterns available in 'inputPattern', 'expectedPattern', 'outputPattern':
    //     %l languageName
    //     %p puzzleName
    //     %t testIndex [01..99]
    // == Tests will be run in the following languages:
    //      Note: can be overriden via the '--lang=...' command-line option
    'languages' => array_merge(PHP_OS_FAMILY != 'Windows' ? [
        'bash',             // tested on Linux only
        // 'objective-c',   // untested
        'ocaml',            // tested on Linux only
        // 'swift',         // untested
        ] : [], [
        'c',
        'c++',
        'c#',
        'clojure',
        'd',
        'dart',
        'f#',
        'go',
        'groovy',
        'haskell',
        'java',
        'javascript',
        'kotlin',
        'lua',
        'pascal',
        'perl',
        'php',
        'python',
        'ruby',
        'rust',
        'scala',
        'typescript',
        'vb.net',
    // Additional languages, unsupported on CodinGame:
        // 'cobol',         // untested
        'fortran',
        // 'r',             // untested
    ]),
    // == Tests will be run for the following puzzles in ALL languages
    //      Note: can be overriden via command-line or in the per-language 'excludePuzzles' sections
    // 'puzzles' => [
    //     'path/' => [
    //         'puzzleName',
    //     ],
    // ],
    'puzzles' => [
        './' => [
            'medium_com_rubik',
        ],
    ],
    // == Tests will be run without test result evaluation for the following puzzles in ALL languages
    //      Note: can be overriden in the per-language 'excludePuzzles' sections
    //      Note: the puzzle solution code must exit after reading turn #0 data to avoid infinite loop!
    // 'runOnlyPuzzles' => [
    //     'path/' => [
    //         'puzzleName',
    //     ],
    // ],
    'runOnlyPuzzles' => [
    ],
    // == The following per-language config options are available:
    // 'languageName' => [
    //     // IMPORTANT NOTE: if 'sourcePath' is given, it will OVERRIDE the 'path/' keys
    //     //   in the 'puzzles', 'runOnlyPuzzles' and 'includePuzzles' lists.
    //     'sourcePath' => 'language/',
    //     'sourceExtension' => '.lang',
    //     'codinGameVersion' => '',
    //     'versionCommand' => 'lang --version',
    //     'buildCommand' => '',
    //     'runCommand' => 'lang %s',
    //     'note' => '',
    //     'altVersionCommand' => '',
    //     'altBuildCommand' => '',
    //     'altRunCommand' => '',
    //     'altNote' => '',
    //     'cleanPatterns' => [
    //         'fileNameWithPathOrPattern',
    //     ],
    //     'excludePuzzles' => [
    //         'puzzleName',
    //     ],
    //     'includePuzzles' => [
    //         'path/' => [
    //             'puzzleName',
    //         ],
    //     ],
    //     'runOnlyPuzzles' => [
    //         'path/' => [
    //             'puzzleName',
    //         ],
    //     ],
    // ],
    // == Patterns available in 'buildCommand', 'runCommand', 'cleanPatterns':
    //     %l languageName
    //     %p puzzleName
    //     %o outputPath
    //     %b buildPath
    // == Patterns available in 'buildCommand', 'runCommand':
    //     %s sourceFileName (with path and extension)
    // == Example (not really needed here, as these are the default settings for c):
    // 'c' => [
    //     'sourcePath' => 'c/',
    //     'sourceExtension' => '.c',
    //     'codinGameVersion' => 'gcc 11.2.0-20',
    //     'versionCommand' => 'gcc --version',
    //     'buildCommand' => 'gcc -std=c17 -o %b%p_%l.exe %s -lm -lpthread',
    //     'runCommand' => '%b%p_%l.exe',
    //     'note' => '',
    //     'altVersionCommand' => 'clang --version',
    //     'altBuildCommand' => 'clang -std=c17 -o %b%p_%l.exe %s',
    //     'altRunCommand' => '%b%p_%l.exe',
    //     'altNote' => '',
    //     'cleanPatterns' => [
    //         '%b%p_%l.exe',
    //     ],
    //     'excludePuzzles' => [
    //     ],
    //     'includePuzzles' => [
    //     ],
    //     'runOnlyPuzzles' => [
    //     ],
    // ],
];
