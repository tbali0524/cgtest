<?php

/**
 * CGTest v1.0.0 - configuration ile
 *
 * A multi-language offline batch test runner for CodinGame (or other) solo I/O puzzles.
 * (c) 2022, by TBali
 */

declare(strict_types=1);

namespace TBali\CGTest;

return [
    // == The following default config options can be overriden:
    // 'dry-run' => false,
    // 'ansi' => true,
    // 'verbose' => false,
    // 'inputPath' => '.tests/input/',
    // 'inputPattern' => '%p_i%t.txt',
    // 'expectedPath' => '.tests/expected/',
    // 'expectedPattern' => '%p_e%t.txt',
    // 'outputPath' => '.tests/output/',
    // 'outputPattern' => '%p_o%t_%l.txt',
    // 'debugLog' => '.tests/output/_debug_log.txt',
    // 'buildLog' => '.tests/output/_build_log.txt',
    // == Patterns available in 'inputPattern', 'expectedPattern', 'outputPattern'
    //     %l languageName
    //     %p puzzleName
    //     %t testIndex [01..99]
    // == Tests will be run in the following languages:
    'languages' => [
        // 'bash',
        'c',
        // 'c#',
        'c++',
        // 'clojure',
        'd',
        'dart',
        // 'f#',
        'go',
        'groovy',
        'haskell',
        'java',
        // 'javascript',
        'kotlin',
        'lua',
        // 'objective-c',
        // 'ocaml',
        'pascal',
        'perl',
        'php',
        'python',
        'ruby',
        'rust',
        'scala',
        // 'swift',
        // 'typescript',
        // 'vb.net',
    // Additional languages, unsupported on CodinGame:
        // 'cobol',
        // 'fortran',
        // 'r',
    ],
    // == Tests will be run for the following puzzles in all languages (can be overriden in the per-language config):
    // 'puzzles' => [
    //     'path/' => [
    //         'puzzleName',
    //     ],
    // ],
    'puzzles' => [
        './' => [
            'med_com_rubik',
        ],
    ],
    // == The following per-language config options are available:
    // 'languageName' => [
    //     // IMPORTANT NOTE: if 'sourcePath' is given, it will OVERRIDE the 'path/' keys
    //     //   in the 'puzzles' and 'includePuzzles' lists.
    //     'sourcePath' => 'language/',
    //     'sourceExtension' => '.lang',
    //     'codinGameVersion' => '',
    //     'versionCommand' => 'lang --version',
    //     'buildCommand' => '',
    //     'runCommand' => 'lang %s',
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
    // ],
    // == Patterns available in 'buildCommand', 'runCommand', 'cleanPatterns':
    //     %l languageName
    //     %p puzzleName
    //     %o outputPath
    // == Patterns available in 'buildCommand', 'runCommand':
    //     %s sourceFileName (with path and extension)
    // == Example (not really needed here, as these are the default settings for rust):
    'rust' => [
        'sourcePath' => 'rust/',
        'sourceExtension' => '.rs',
        'versionCommand' => 'rustc --version',
        'buildCommand' => 'rustc %s -o%o%p_%l.exe',
        'runCommand' => '%o%p_%l.exe',
        'cleanPatterns' => [
            '%o%p_%l.exe',
            '%o%p_%l.pdb',
        ],
        'excludePuzzles' => [
        ],
        'includePuzzles' => [
        ],
    ],
];
