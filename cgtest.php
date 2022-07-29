#!/usr/bin/env php
<?php

/**
 * CGTest v1.2.0
 *
 * A multi-language offline batch test runner for CodinGame (or other) solo I/O puzzles.
 * (c) 2022, by Balint Toth [TBali]
 *
 * For usage, see:
 *   php cgtest.php --help
 *
 * Latest version: https://github.com/tbali0524/cgtest
 */

declare(strict_types=1);

namespace TBali\CGTest;

// Yes, this is a spaghetti code, I know... Don't look at it.
// I wanted a zero-dependency, single file script.
// So I skipped using OOP, and - as code repetition is low - even functions.
// --------------------------------------------------------------------
// init counters, start global timer
$version = 'v1.2.0';
$zeroStat = [
    'countDirectories' => 0,
    'countFiles' => 0,
    'countTests' => 0,
    'countPassedTests' => 0,
    'countSkippedLanguages' => 0,
    'countSkippedFiles' => 0,
    'countSkippedTests' => 0,
    'countDeletedFiles' => 0,
    'startTime' => 0,
    'spentTime' => 0,
];
$stats = [];
$stats['totals'] = $zeroStat;
$stats['totals']['countLanguages'] = 0;
$stats['totals']['uniquePuzzles'] = 0;
$stats['totals']['uniqueTests'] = 0;
$stats['totals']['uniquePassedTests'] = 0;
$stats['totals']['startTime'] = hrtime(true);
$uniquePuzzleTests = []; // puzzleName => # of test
$uniquePuzzlePassed = []; // puzzleName => bitmap of passed tests
$infoTag = '[INFO] ';
$errorTag = '[ERROR] ';
// --------------------------------------------------------------------
// print application title, check php version
$title = 'CGTest ' . $version . PHP_EOL
    . 'A multi-language offline batch test runner for CodinGame (or other) solo I/O puzzles' . PHP_EOL
    . '(c) 2022, by Balint Toth [TBali]' . PHP_EOL;
echo $title . PHP_EOL;
const MIN_PHP_VERSION = '7.3.0';
if (version_compare(phpversion(), MIN_PHP_VERSION, '<')) {
    echo $errorTag . 'Minimum required PHP version is ' . MIN_PHP_VERSION . '; you are on ' . phpversion()
        . PHP_EOL . PHP_EOL;
    // OS exit codes: 0 = OK, 1 = some tests failed, 2 = error (wrong CLI arguments, etc)
    exit(2);
}
// --------------------------------------------------------------------
// default configuration
$defaultConfigFileName = '.cgtest.php';
$defaultConfig = [
    'dry-run' => false,
    'ansi' => true,
    'verbose' => false,
    'stats' => false,
    'lang-versions' => false,
    'clean' => false,
    'inputPath' => '.tests/input/',
    'inputPattern' => '%p_i%t.txt',
    'expectedPath' => '.tests/expected/',
    'expectedPattern' => '%p_e%t.txt',
    'outputPath' => '.tests/output/',
    'outputPattern' => '%p_o%t_%l.txt',
    'debugLog' => '.tests/output/_debug_log.txt',
    'buildLog' => '.tests/output/_build_log.txt',
    'languages' => ['php'],
    'puzzles' => [],
    // todo: fix to work also under Windows
    'bash' => [
        'sourcePath' => 'bash/',
        'sourceExtension' => '.sh',
        'codinGameVersion' => 'GNU bash, version 5.1.16(1)',
        'versionCommand' => 'bash --version',
        'buildCommand' => 'chmod +x %s',
        'runCommand' => '%s',
        'cleanPatterns' => [],
    ],
    'c' => [
        'sourcePath' => 'c/',
        'sourceExtension' => '.c',
        'codinGameVersion' => 'gcc 11.2.0-20',
        'versionCommand' => 'gcc --version',
        'buildCommand' => 'gcc %s -o %o%p_%l.exe -lm',
        'runCommand' => '%o%p_%l.exe',
        'cleanPatterns' => ['%o%p_%l.exe'],
    ],
    // todo: fix buildCommand, runCommand, cleanPatterns
    'c#' => [
        'sourcePath' => 'c#/',
        'sourceExtension' => '.cs',
        'codinGameVersion' => '.NET Core 3.1.201',
        'versionCommand' => 'dotnet --version',
        'buildCommand' => '',
        'runCommand' => 'dotnet run --nologo --verbosity quiet --project dotnet.csproj %s',
        'cleanPatterns' => [],
    ],
    'c++' => [
        'sourcePath' => 'c++/',
        'sourceExtension' => '.cpp',
        'codinGameVersion' => 'g++ 11.2.0-20',
        'versionCommand' => 'g++ --version',
        'buildCommand' => 'g++ -o %o%p_%l.exe -x c++ %s',
        'runCommand' => '%o%p_%l.exe',
        'cleanPatterns' => ['%o%p_%l.exe'],
    ],
    // todo: fix buildCommand, runCommand, cleanPatterns
    'clojure' => [
        'sourcePath' => 'clojure/',
        'sourceExtension' => '.clj',
        'codinGameVersion' => 'Clojure 1.11.1',
        'versionCommand' => 'clj --version',
        'buildCommand' => '',
        'runCommand' => 'clj -X Solution/main %s',
        'cleanPatterns' => [],
        // 'versionCommand' => 'pwsh -Command clj --version',
        // 'buildCommand' => '',
        // 'runCommand' => 'pwsh -Command clj -X Solution/main %s',
        // 'cleanPatterns' => [],
    ],
    'd' => [
        'sourcePath' => 'd/',
        'sourceExtension' => '.d',
        'codinGameVersion' => 'DMD64 D Compiler v2.099.1',
        'versionCommand' => 'dmd --version',
        'buildCommand' => 'dmd -od=%o -of=%o%p_%l.exe %s',
        'runCommand' => '%o%p_%l.exe',
        'cleanPatterns' => [
            '%o%p_%l.exe',
            '%o%p_%l.obj',
            '%o%p_%l.o',
        ],
    ],
    'dart' => [
        'sourcePath' => 'dart/',
        'sourceExtension' => '.dart',
        'codinGameVersion' => 'Dart SDK version: 2.16.2',
        'versionCommand' => 'dart --version',
        'buildCommand' => '',
        'runCommand' => 'dart %s',
        'cleanPatterns' => [],
    ],
    'f#' => [
        'sourcePath' => 'f#/',
        'sourceExtension' => '.fsx',
        'codinGameVersion' => '.NET Core 3.1.201',
        'versionCommand' => 'dotnet --version',
        'buildCommand' => '',
        'runCommand' => 'dotnet fsi %s',
        'cleanPatterns' => [],
    ],
    'go' => [
        'sourcePath' => 'go/',
        'sourceExtension' => '.go',
        'codinGameVersion' => 'go version go1.18.1',
        'versionCommand' => 'go version',
        'buildCommand' => '',
        'runCommand' => 'go run %s',
        'cleanPatterns' => [],
    ],
    'groovy' => [
        'sourcePath' => 'groovy/',
        'sourceExtension' => '.groovy',
        'codinGameVersion' => 'Groovy Version: 3.0.8 JVM: 11.0.2',
        'versionCommand' => 'groovy --version',
        'buildCommand' => '',
        'runCommand' => 'groovy %s',
        'cleanPatterns' => [],
    ],
    'haskell' => [
        'sourcePath' => 'haskell/',
        'sourceExtension' => '.hs',
        'codinGameVersion' => 'The Glorious Glasgow Haskell Compilation System, version 8.4.3',
        'versionCommand' => 'ghc --version',
        'buildCommand' => 'ghc -outputdir %o -o %o%p_%l.exe %s',
        'runCommand' => '%o%p_%l.exe',
        'cleanPatterns' => [
            '%o%p_%l.exe',
            '%oMain.o',
            '%oMain.hi',
        ],
    ],
    'java' => [
        'sourcePath' => 'java/',
        'sourceExtension' => '.java',
        'codinGameVersion' => 'openjdk 11.0.2',
        'versionCommand' => 'java --version',
        'buildCommand' => '',
        'runCommand' => 'java %s',
        'cleanPatterns' => [],
    ],
    // todo: readline() is missing
    'javascript' => [
        'sourcePath' => 'javascript/',
        'sourceExtension' => '.js',
        'codinGameVersion' => 'node.js v16.14.2',
        'versionCommand' => 'node --version',
        'buildCommand' => '',
        'runCommand' => 'node %s',
        'cleanPatterns' => [],
    ],
    'kotlin' => [
        'sourcePath' => 'kotlin/',
        'sourceExtension' => '.kt',
        'codinGameVersion' => 'kotlinc-jvm 1.5.0 (JRE 11.0.2+9)',
        'versionCommand' => 'kotlinc -version',
        'buildCommand' => 'kotlinc -include-runtime -d %o%p_%l.jar %s',
        'runCommand' => 'java -jar %o%p_%l.jar',
        'cleanPatterns' => ['%o%p_%l.jar'],
        // 'versionCommand' => 'kotlinc-native -version',
        // 'buildCommand' => 'kotlinc-native -o %o%p_%l.exe %s',
        // 'runCommand' => '%o%p_%l.exe',
        // 'cleanPatterns' => ['%o%p_%l.exe'],
    ],
    'lua' => [
        'sourcePath' => 'lua/',
        'sourceExtension' => '.lua',
        'codinGameVersion' => 'Lua 5.4.4',
        'versionCommand' => 'lua -v',
        'buildCommand' => '',
        'runCommand' => 'lua %s',
        'cleanPatterns' => [],
    ],
    // todo: fix buildCommand, runCommand, cleanPatterns
    'objective-c' => [
        'sourcePath' => 'objective-c/',
        'sourceExtension' => '.m',
        'codinGameVersion' => 'clang version 13.0.1-3+b2',
        'versionCommand' => 'gcc --version',
        'buildCommand' => 'gcc -o %o%p_%l.exe -lobjc -lgnustep-base -F /usr/lib/GNUstep -I /usr/include/GNUstep'
            . ' -fconstant-string-class=NSConstantString %s',
        'runCommand' => '%o%p_%l.exe',
        'cleanPatterns' => ['%o%p_%l.exe'],
    ],
    // todo: try out also in Windows:
    'ocaml' => [
        'sourcePath' => 'ocaml/',
        'sourceExtension' => '.ml',
        'codinGameVersion' => 'The OCaml native-code compiler, version 4.12.0',
        'versionCommand' => 'ocamlopt -v',
        'buildCommand' => 'ocamlopt %s -o %o%p_%l.exe',
        'runCommand' => '%o%p_%l.exe',
        'cleanPatterns' => ['%o%p_%l.exe'],
    ],
    'pascal' => [
        'sourcePath' => 'pascal/',
        'sourceExtension' => '.pas',
        'codinGameVersion' => 'Free Pascal Compiler 3.2.2',
        'versionCommand' => 'fpc -iW',
        'buildCommand' => 'fpc -v0 -FE%o -o%p_%l.exe %s',
        'runCommand' => '%o%p_%l.exe',
        'cleanPatterns' => [
            '%o%p_%l.exe',
            '%o%p.o',
        ],
    ],
    'perl' => [
        'sourcePath' => 'perl/',
        'sourceExtension' => '.pl',
        'codinGameVersion' => 'perl 5, version 34, subversion 0 (v5.34.0)',
        'versionCommand' => 'perl --version',
        'buildCommand' => '',
        'runCommand' => 'perl %s',
        'cleanPatterns' => [],
    ],
    'php' => [
        'sourcePath' => 'php/',
        'sourceExtension' => '.php',
        'codinGameVersion' => 'PHP 7.3.9',
        'versionCommand' => 'php --version',
        'buildCommand' => '',
        'runCommand' => 'php %s',
        'cleanPatterns' => [],
    ],
    'python' => [
        'sourcePath' => 'python/',
        'sourceExtension' => '.py',
        'codinGameVersion' => 'Python 3.9.12',
        'versionCommand' => 'python --version',
        'buildCommand' => '',
        'runCommand' => 'python %s',
        'cleanPatterns' => [],
    ],
    'ruby' => [
        'sourcePath' => 'ruby/',
        'sourceExtension' => '.rb',
        'codinGameVersion' => 'ruby 3.1.2p20',
        'versionCommand' => 'ruby --version',
        'buildCommand' => '',
        'runCommand' => 'ruby %s',
        'cleanPatterns' => [],
    ],
    'rust' => [
        'sourcePath' => 'rust/',
        'sourceExtension' => '.rs',
        'codinGameVersion' => 'rustc 1.60.0',
        'versionCommand' => 'rustc --version',
        'buildCommand' => 'rustc %s -o%o%p_%l.exe',
        'runCommand' => '%o%p_%l.exe',
        'cleanPatterns' => [
            '%o%p_%l.exe',
            '%o%p_%l.pdb',
        ],
    ],
    'scala' => [
        'sourcePath' => 'scala/',
        'sourceExtension' => '.scala',
        'codinGameVersion' => 'Scala code runner version 2.13.5',
        'versionCommand' => 'scala --version',
        'buildCommand' => '',
        'runCommand' => 'scala -cp %o %s',
        'cleanPatterns' => [],
    ],
    // todo: fix buildCommand, runCommand, cleanPatterns
    'swift' => [
        'sourcePath' => 'swift/',
        'sourceExtension' => '.swift',
        'codinGameVersion' => 'Swift version 5.3.3',
        'versionCommand' => 'swift --version',
        'buildCommand' => '',
        'runCommand' => 'swift %s',
        'cleanPatterns' => [],
    ],
    // todo: readline() is missing
    'typescript' => [
        'sourcePath' => 'typescript/',
        'sourceExtension' => '.ts',
        'codinGameVersion' => 'node.js v16.14.2; Typescript Compiler Version 4.6.3',
        'versionCommand' => 'tsc --version',
        'buildCommand' => '',
        'runCommand' => 'node %s',
        'cleanPatterns' => [],
    ],
    // todo: fix buildCommand, runCommand, cleanPatterns
    'vb.net' => [
        'sourcePath' => 'vb.net/',
        'sourceExtension' => '.vb',
        'codinGameVersion' => '.NET Core 3.1.201',
        'versionCommand' => 'dotnet --version',
        'buildCommand' => '',
        'runCommand' => 'dotnet run %s',
        'cleanPatterns' => [],
    ],
    // unsupported on CodinGame
    // todo: check
    'cobol' => [
        'sourcePath' => 'cobol/',
        'sourceExtension' => '.cob',
        'codinGameVersion' => 'cobc (GnuCOBOL) 3.1.2.0',
        'versionCommand' => 'cobc --version',
        'buildCommand' => 'cobc -x -o%o%p_%l.exe %s',
        'runCommand' => '%o%p_%l.exe',
        'cleanPatterns' => ['%o%p_%l.exe'],
    ],
    'fortran' => [
        'sourcePath' => 'fortran/',
        'sourceExtension' => '.f90',
        'codinGameVersion' => 'GNU Fortran 11.2.0-20',
        'versionCommand' => 'gfortran --version',
        'buildCommand' => 'gfortran -o%o%p_%l.exe %s',
        'runCommand' => '%o%p_%l.exe',
        'cleanPatterns' => ['%o%p_%l.exe'],
    ],
    // todo: check
    'r' => [
        'sourcePath' => 'r/',
        'sourceExtension' => '.R',
        'codinGameVersion' => 'R version 3.6.3',
        'versionCommand' => 'Rscript --version',
        'buildCommand' => '',
        'runCommand' => 'Rscript %s',
        'cleanPatterns' => [],
    ],
];
foreach ($defaultConfig['languages'] as $language) {
    $defaultConfig[$language]['excludePuzzles'] = [];
    $defaultConfig[$language]['includePuzzles'] = [];
}
$booleanConfigKeys = ['dry-run', 'ansi', 'verbose', 'stats', 'lang-versions', 'clean', 'show-defaults'];
$nonEmptyStringConfigKeys = ['inputPattern', 'expectedPattern', 'outputPattern'];
$optionalStringConfigKeys = ['inputPath', 'expectedPath', 'outputPath'];
$arrayConfigKeys = ['languages', 'puzzles'];
$reservedConfigKeys = array_merge(
    $booleanConfigKeys,
    $nonEmptyStringConfigKeys,
    $optionalStringConfigKeys,
    $arrayConfigKeys,
    ['totals'],
);
$languageNonEmptyStringConfigKeys = ['sourceExtension', 'runCommand'];
$languageOptionalStringConfigKeys = ['sourcePath', 'codinGameVersion', 'versionCommand', 'buildCommand'];
$languageArrayConfigKeys = ['excludePuzzles', 'includePuzzles'];
$testIdxWidth = 2;
$noPathKey = '@';
// --------------------------------------------------------------------
// command-line arguments
$argumentConfig = [];
$argConfigFileName = '';
for ($i = 1; $i < $argc; ++$i) {
    $arg = strtolower($argv[$i]);
    if ($arg == '--version') {
        exit(0);
    }
    if ($arg == '--help') {
        echo 'Usage:   php pgtest.php [options] [puzzles]' . PHP_EOL;
        echo PHP_EOL;
        echo 'Options:' . PHP_EOL;
        echo '   --version          Display CGTest application version' . PHP_EOL;
        echo '   --help             Display this help message' . PHP_EOL;
        echo '   --dry-run          Do not run the tests; only show what test cases would run' . PHP_EOL;
        echo '   --ansi             Use color output [default]' . PHP_EOL;
        echo '   --no-ansi          Disable color output' . PHP_EOL;
        echo '   --verbose          Increase the verbosity of messages: also show each passed tests' . PHP_EOL;
        echo '   --stats            Show per-language test stats' . PHP_EOL;
        echo '   --lang-versions    Show versions for all configured programming languages' . PHP_EOL;
        echo '   --show-defaults    Show default configuration settings (as json)' . PHP_EOL;
        echo '   --clean            Delete temporary and output files of previous test run' . PHP_EOL;
        echo '   --config=FILENAME  Use configfile [default: ' . $defaultConfigFileName . ']' . PHP_EOL;
        echo '   --lang=LANGUAGES   Run tests in these languages (comma separated list)' . PHP_EOL
            . '                        - default: ' . implode(',', $defaultConfig['languages'])
            . '; or the languages section in the config file' . PHP_EOL;
        echo PHP_EOL;
        echo 'Puzzles:              Space separated list of source filenames (without extension)' . PHP_EOL;
        echo '                        - if given, it overrides the list in the config file' . PHP_EOL;
        echo '                        - path can be given, but no wildcards allowed' . PHP_EOL;
        echo PHP_EOL;
        exit(0);
    }
    if ($arg == '--show-defaults') {
        echo $infoTag . 'Default configuration settings (before applying any config file):' . PHP_EOL;
        echo json_encode($defaultConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL . PHP_EOL;
        exit(0);
    }
    if ((substr($arg, 0, 9) == '--config=') and (strlen($arg) > 9)) {
        if ($argConfigFileName != '') {
            echo $errorTag . 'Invalid arguments: only a single config file can be given.' . PHP_EOL . PHP_EOL;
            exit(2);
        }
        $argConfigFileName = substr($arg, 9);
        continue;
    }
    if ((substr($arg, 0, 7) == '--lang=') and (strlen($arg) > 7)) {
        $langs = explode(',', substr($arg, 7));
        if (!isset($argumentConfig['languages'])) {
            $argumentConfig['languages'] = [];
        }
        foreach ($langs as $lang) {
            if (array_search($lang, $reservedConfigKeys, true) !== false) {
                echo $errorTag . 'Invalid language name: ' . $lang . PHP_EOL . PHP_EOL;
                exit(2);
            }
            if (array_search($lang, $argumentConfig['languages'], true) === false) {
                $argumentConfig['languages'][] = $lang;
            }
        }
        continue;
    }
    if ($arg == '--no-ansi') {
        $argumentConfig['ansi'] = false;
        continue;
    }
    if ((substr($arg, 0, 2) == '--') and in_array(substr($arg, 2), $booleanConfigKeys, true)) {
        $argumentConfig[substr($arg, 2)] = true;
        continue;
    }
    if ($arg[0] == '-') {
        echo $errorTag . 'Invalid argument: ' . $arg . PHP_EOL . PHP_EOL;
        exit(2);
    }
    if (!isset($argumentConfig['puzzles'])) {
        $argumentConfig['puzzles'] = [];
    }
    $j = strlen($arg) - 1;
    while (($j >= 0) and ($arg[$j] != '/') and ($arg[$j] != '\\')) {
        --$j;
    }
    $path = substr($arg, 0, $j + 1);
    if ($path == '') {
        $path = $noPathKey;
    }
    $puzzle = substr($arg, $j + 1);
    if ($puzzle == '') {
        echo $errorTag . 'Invalid puzzle name: ' . $arg . PHP_EOL . PHP_EOL;
        exit(2);
    }
    if (!isset($argumentConfig['puzzles'][$path])) {
        $argumentConfig['puzzles'][$path] = [];
    }
    $argumentConfig['puzzles'][$path][] = $puzzle;
}
// --------------------------------------------------------------------
// read config file
$configFromFile = [];
if ($argConfigFileName != '') {
    if (!file_exists($argConfigFileName)) {
        echo $errorTag . 'Cannot open config file: ' . $argConfigFileName . PHP_EOL . PHP_EOL;
        exit(2);
    }
    echo $infoTag . 'Using configuration file: ' . $argConfigFileName . PHP_EOL;
    $configFromFile = require_once $argConfigFileName;
} elseif (file_exists($defaultConfigFileName)) {
    echo $infoTag . 'Using configuration file: ' . $defaultConfigFileName . PHP_EOL;
    $configFromFile = require_once $defaultConfigFileName;
}
// --------------------------------------------------------------------
// merge default config, config from config file and command-line arguments
$config = $defaultConfig;
foreach ($configFromFile as $configKey => $configValue) {
    if (
        !is_array($defaultConfig[$configKey])
        or (in_array($configKey, ['languages', 'puzzles'], true) !== false)
    ) {
        $config[$configKey] = $configValue;
        continue;
    }
    if (!is_array($configValue)) {
        continue;
    }
    foreach ($configValue as $languageConfigKey => $languageConfigValue) {
        $config[$configKey][$languageConfigKey] = $languageConfigValue;
    }
}
$config = array_merge($config, $argumentConfig);
if (isset($argumentConfig['puzzles']) and (count($argumentConfig['puzzles']) != 0)) {
    $overrideSourcePath = false;
    foreach ($config['puzzles'] as $path => $puzzleArray) {
        if ($path != $noPathKey) {
            $overrideSourcePath = true;
            break;
        }
    }
    if ($overrideSourcePath) {
        foreach ($config['languages'] as $language) {
            $config[$language]['sourcePath'] = '';
        }
    }
}
// --------------------------------------------------------------------
// setup color mode
if (!isset($config['ansi']) or !is_bool($config['ansi'])) {
    $config['ansi'] = false;
}
$ansiGreen = $config['ansi'] ? "\e[1;37;42m" : '';
$ansiRed = $config['ansi'] ? "\e[1;37;41m" : '';
$ansiYellow = $config['ansi'] ? "\e[1;37;43m" : '';
$ansiReset = $config['ansi'] ? "\e[0m" : '';
$errorTag = $ansiRed . '[ERROR]' . $ansiReset . ' ';
$warnTag = $ansiYellow . '[WARN]' . $ansiReset . ' ';
// --------------------------------------------------------------------
// check for configuration errors in global settings
foreach ($booleanConfigKeys as $configKey) {
    if (!isset($config[$configKey])) {
        $config[$configKey] = false;
        continue;
    }
    if (!is_bool($config[$configKey])) {
        echo $errorTag . 'Invalid configuration: setting must be a boolean value: ' . $configKey . PHP_EOL . PHP_EOL;
        exit(2);
    }
}
foreach ($nonEmptyStringConfigKeys as $configKey) {
    if (!isset($config[$configKey]) or !is_string($config[$configKey]) or ($config[$configKey] == '')) {
        echo $errorTag . 'Invalid configuration: required setting must be a non-empty string value: ' . $configKey
            . PHP_EOL . PHP_EOL;
        exit(2);
    }
}
foreach ($optionalStringConfigKeys as $configKey) {
    if (!isset($config[$configKey])) {
        $config[$configKey] = '';
        continue;
    }
    if (!is_string($config[$configKey])) {
        echo $errorTag . 'Invalid configuration: setting must be a string value: ' . $configKey . PHP_EOL . PHP_EOL;
        exit(2);
    }
}
foreach ($arrayConfigKeys as $configKey) {
    if (!isset($config[$configKey])) {
        $config[$configKey] = [];
        continue;
    }
    if (!is_array($config[$configKey])) {
        echo $errorTag . 'Invalid configuration: setting must be an array: ' . $configKey . PHP_EOL . PHP_EOL;
        exit(2);
    }
}
if ($config['lang-versions'] and $config['clean']) {
    echo $errorTag . 'Invalid arguments: cannot use both --clean and --lang-versions' . PHP_EOL . PHP_EOL;
    exit(2);
}
if ($config['lang-versions'] and $config['dry-run']) {
    echo $errorTag . 'Invalid arguments: cannot use both --dry-run and --lang-versions' . PHP_EOL . PHP_EOL;
    exit(2);
}
if ($config['clean'] and $config['dry-run']) {
    echo $errorTag . 'Invalid arguments: cannot use both --clean and --dry-run' . PHP_EOL . PHP_EOL;
    exit(2);
}
if ($config['lang-versions'] and $config['stats']) {
    echo $errorTag . 'Invalid arguments: cannot use both --lang-versions and --stats' . PHP_EOL . PHP_EOL;
    exit(2);
}
// --------------------------------------------------------------------
// delete / init log files
$totalUnsuccessfulDeleteFiles = 0;
if (file_exists($config['debugLog'])) {
    $unlinkResult = unlink($config['debugLog']);
    if ($config['clean']) {
        if (!$unlinkResult) {
            ++$totalUnsuccessfulDeleteFiles;
            echo $warnTag . 'Could not delete file: ' . $config['debugLog'] . PHP_EOL;
        } else {
            ++$stats['totals']['countDeletedFiles'];
            if ($config['verbose']) {
                echo $infoTag . 'Deleted file: ' . $config['debugLog'] . PHP_EOL;
            }
        }
    }
}
if (file_exists($config['buildLog'])) {
    $unlinkResult = unlink($config['buildLog']);
    if ($config['clean']) {
        if (!$unlinkResult) {
            ++$totalUnsuccessfulDeleteFiles;
            echo $warnTag . 'Could not delete file: ' . $config['buildLog'] . PHP_EOL;
        } else {
            ++$stats['totals']['countDeletedFiles'];
            if ($config['verbose']) {
                echo $infoTag . 'Deleted file: ' . $config['buildLog'] . PHP_EOL;
            }
        }
    }
}
if (!$config['clean'] and !$config['lang-versions'] and !$config['dry-run']) {
    $logFile = fopen($config['debugLog'], 'ab');
    if ($logFile === false) {
        echo $errorTag . 'Cannot open logfile to write: ' . $config['debugLog'] . PHP_EOL . PHP_EOL;
        exit(2);
    }
    fwrite($logFile, $title . PHP_EOL);
    fclose($logFile);
    echo $infoTag . '<stderr> output from the test runs redirected to file: ' . $config['debugLog'] . PHP_EOL;
}
// --------------------------------------------------------------------
// main loop: tests run by language
foreach ($config['languages'] as $language) {
    ++$stats['totals']['countLanguages'];
    $stats[$language] = $zeroStat;
    $stats[$language]['startTime'] = hrtime(true);
    // --------------------------------------------------------------------
    // check for configuration errors in per-language settings
    $isLanguageOk = true;
    if ((trim($language) == '') or (array_search($language, $reservedConfigKeys, true) !== false)) {
        echo $warnTag . 'Invalid language name: ' . $lang . PHP_EOL . PHP_EOL;
        ++$stats['totals']['countSkippedLanguages'];
        ++$stats[$language]['countSkippedLanguages'];
        continue;
    }
    if (!isset($config[$language]) or !is_array($config[$language])) {
        echo $warnTag . 'No configuration available for language: ' . $language . PHP_EOL;
        ++$stats['totals']['countSkippedLanguages'];
        ++$stats[$language]['countSkippedLanguages'];
        continue;
    }
    foreach ($languageNonEmptyStringConfigKeys as $configKey) {
        if (
            !isset($config[$language][$configKey])
            or !is_string($config[$language][$configKey])
            or ($config[$language][$configKey] == '')
        ) {
            echo $warnTag . 'Invalid configuration for language ' . $language
                . ': required setting must be a non-empty string value: ' . $configKey . PHP_EOL;
            $isLanguageOk = false;
        }
    }
    foreach ($languageOptionalStringConfigKeys as $configKey) {
        if (!isset($config[$language][$configKey])) {
            $config[$language][$configKey] = '';
            continue;
        }
        if (!is_string($config[$language][$configKey])) {
            echo $warnTag . 'Invalid configuration for language ' . $language
                . ': setting must be a string value: ' . $configKey . PHP_EOL;
            $isLanguageOk = false;
        }
    }
    foreach ($languageArrayConfigKeys as $configKey) {
        if (!isset($config[$language][$configKey])) {
            $config[$language][$configKey] = [];
            continue;
        }
        if (!is_array($config[$language][$configKey])) {
            echo $warnTag . 'Invalid configuration for language ' . $language
                . ': setting must be an array: ' . $configKey . PHP_EOL;
            $isLanguageOk = false;
        }
    }
    if ($config['lang-versions'] and ($config[$language]['versionCommand'] == '')) {
        echo $warnTag . 'Invalid configuration: missing version command for language: ' . $language . PHP_EOL;
        $isLanguageOk = false;
    }
    if (!$isLanguageOk) {
        ++$stats['totals']['countSkippedLanguages'];
        ++$stats[$language]['countSkippedLanguages'];
        continue;
    }
    // --------------------------------------------------------------------
    // check language availability for --lang-versions, --dry-run, or real tests
    if (!$config['clean'] and ($config[$language]['versionCommand'] != '')) {
        if ($config['lang-versions']) {
            echo $infoTag . 'Version info for language: ' . $language . PHP_EOL;
            if ($config[$language]['codinGameVersion'] != '') {
                echo $infoTag . '-- version supported at CodinGame: ' . $config[$language]['codinGameVersion']
                    . PHP_EOL;
            }
            $versionCommand = $config[$language]['versionCommand'] . ' 2>&1';
        } elseif ($config['dry-run']) {
            $versionCommand = $config[$language]['versionCommand'] . ' >> ' . $config['buildLog'] . ' 2>&1';
        } else {
            $versionCommand = $config[$language]['versionCommand'] . ' >> ' . $config['debugLog'] . ' 2>&1';
        }
        $execOutput = [];
        $execResultCode = 0;
        $execResult = exec($versionCommand, $execOutput, $execResultCode);
        if ($execResult === false) {
            echo $warnTag . 'Language is unavaliable: ' . $config[$language]['versionCommand'] . PHP_EOL;
            ++$stats['totals']['countSkippedLanguages'];
            ++$stats[$language]['countSkippedLanguages'];
            continue;
        }
        if ($config['lang-versions']) {
            for ($i = 0; $i < min(4, count($execOutput)); ++$i) {
                if (($execOutput[$i] ?? '') == '') {
                    continue;
                }
                echo '    ' . $execOutput[$i] . PHP_EOL;
            }
            continue;
        }
    }
    // --------------------------------------------------------------------
    // prepare puzzle list for this language
    $puzzles = $config['puzzles'];
    if (($argumentConfig['puzzles'] ?? []) == []) {
        foreach ($config[$language]['includePuzzles'] as $sourcePath => $filesArray) {
            if (!isset($puzzles[$sourcePath])) {
                $puzzles[$sourcePath] = [];
            }
            foreach ($filesArray as $puzzleNameOriginal) {
                $puzzles[$sourcePath][] = strtolower($puzzleNameOriginal);
            }
        }
    }
    // --------------------------------------------------------------------
    // loop: for each puzzle to test or clean
    foreach ($puzzles as $sourcePathKey => $filesArray) {
        $sourcePath = ($sourcePathKey == $noPathKey ? '' : $sourcePathKey);
        if (($config[$language]['sourcePath'] == '') or ($stats[$language]['countDirectories'] == 0)) {
            ++$stats['totals']['countDirectories'];
            ++$stats[$language]['countDirectories'];
        }
        foreach ($filesArray as $puzzleNameOriginal) {
            $puzzleName = strtolower($puzzleNameOriginal);
            if (
                (($argumentConfig['puzzles'] ?? []) == [])
                and (in_array($puzzleName, $config[$language]['excludePuzzles'], true))
            ) {
                continue;
            }
            // --------------------------------------------------------------------
            // check source file
            $sourceFileName = $puzzleName . $config[$language]['sourceExtension'];
            if ($config[$language]['sourcePath'] == '') {
                $sourceFullFileName = $sourcePath . $sourceFileName;
            } else {
                $sourceFullFileName = $config[$language]['sourcePath'] . $sourceFileName;
            }
            if (!file_exists($sourceFullFileName)) {
                if (!$config['clean']) {
                    echo $warnTag . 'Cannot find sourcefile: ' . $sourceFullFileName . PHP_EOL;
                }
                ++$stats['totals']['countSkippedFiles'];
                ++$stats[$language]['countSkippedFiles'];
                continue;
            }
            ++$stats['totals']['countFiles'];
            ++$stats[$language]['countFiles'];
            // --------------------------------------------------------------------
            // clean temporary and output files from previous test run
            if ($config['clean']) {
                foreach ($config[$language]['cleanPatterns'] as $patternToClean) {
                    $tempFileName = str_replace(
                        ['%l', '%p', '%o'],
                        [$language, $puzzleName, $config['outputPath']],
                        $patternToClean
                    );
                    if (file_exists($tempFileName)) {
                        $unlinkResult = unlink($tempFileName);
                        if (!$unlinkResult) {
                            ++$totalUnsuccessfulDeleteFiles;
                            echo $warnTag . 'Could not delete file: ' . $tempFileName . PHP_EOL;
                        } else {
                            ++$stats['totals']['countDeletedFiles'];
                            ++$stats[$language]['countDeletedFiles'];
                            if ($config['verbose']) {
                                echo $infoTag . 'Deleted file: ' . $tempFileName . PHP_EOL;
                            }
                        }
                    }
                }
            }
            // --------------------------------------------------------------------
            // special case for Haskell: Main.o must be deleted before each build
            $tempFileName = $config['outputPath'] . 'Main.o';
            if (file_exists($tempFileName)) {
                unlink($tempFileName);
            }
            $tempFileName = $config['outputPath'] . 'Main.hi';
            if (file_exists($tempFileName)) {
                unlink($tempFileName);
            }
            // --------------------------------------------------------------------
            // build the source (for compiled languages)
            $buildCommand = '';
            $buildSuccessful = true;
            if (
                !$config['clean']
                and !$config['dry-run']
                and ($config[$language]['buildCommand'] != '')
            ) {
                $baseBuildCommand = str_replace(
                    ['%l', '%p', '%o', '%s'],
                    [$language, $puzzleName, $config['outputPath'], $sourceFullFileName],
                    $config[$language]['buildCommand']
                );
                if (PHP_OS_FAMILY == 'Windows') {
                    $baseBuildCommand = str_replace('/', '\\', $baseBuildCommand);
                }
                $buildCommand = $baseBuildCommand . ' >> ' . $config['debugLog'] . ' 2>> ' . $config['buildLog'];
                $execOutput = [];
                $execResultCode = 0;
                $execResult = exec($buildCommand, $execOutput, $execResultCode);
                if ($execResult === false) {
                    $buildSuccessful = false;
                    echo $warnTag . 'Build unsuccessful for source: ' . $sourceFullFileName . PHP_EOL;
                }
            }
            // --------------------------------------------------------------------
            // preparation common for all tests
            $baseRunCommand = str_replace(
                ['%l', '%p', '%o', '%s'],
                [$language, $puzzleName, $config['outputPath'], $sourceFullFileName],
                $config[$language]['runCommand']
            );
            if (PHP_OS_FAMILY == 'Windows') {
                $baseRunCommand = str_replace('/', '\\', $baseRunCommand);
            }
            // --------------------------------------------------------------------
            // loop: for each test case for the puzzle
            $testsFailed = [];
            $countTestsForFile = 0;
            $idxTest = 0;
            while (true) {
                ++$idxTest;
                // --------------------------------------------------------------------
                // check input and expected output
                $stringIdxTest = str_pad(strval($idxTest), $testIdxWidth, '0', STR_PAD_LEFT);
                $inputFullFileName = str_replace(
                    ['%l', '%p', '%t'],
                    [$language, $puzzleName, $stringIdxTest],
                    $config['inputPath'] . $config['inputPattern']
                );
                if (!file_exists($inputFullFileName)) {
                    if ($countTestsForFile == 0) {
                        ++$stats['totals']['countSkippedTests'];
                        ++$stats[$language]['countSkippedTests'];
                    }
                    break;
                }
                $expectedFullFileName = str_replace(
                    ['%l', '%p', '%t'],
                    [$language, $puzzleName, $stringIdxTest],
                    $config['expectedPath'] . $config['expectedPattern']
                );
                if (!file_exists($expectedFullFileName)) {
                    ++$stats['totals']['countSkippedTests'];
                    ++$stats[$language]['countSkippedTests'];
                    echo $warnTag . 'Cannot read expected test output file: ' . $expectedFullFileName . PHP_EOL;
                    continue;
                }
                $outputFullFileName = str_replace(
                    ['%l', '%p', '%t'],
                    [$language, $puzzleName, $stringIdxTest],
                    $config['outputPath'] . $config['outputPattern']
                );
                if ($expectedFullFileName === $outputFullFileName) {
                    echo $errorTag . 'Invalid configuration: expected and real test output filenames '
                        . 'must not be the same: ' . $expectedFullFileName . PHP_EOL . PHP_EOL;
                    exit(2);
                }
                // --------------------------------------------------------------------
                // clean previous test output
                if ($config['clean']) {
                    if (file_exists($outputFullFileName)) {
                        $unlinkResult = unlink($outputFullFileName);
                        if (!$unlinkResult) {
                            ++$totalUnsuccessfulDeleteFiles;
                            echo $warnTag . 'Could not delete file: ' . $outputFullFileName . PHP_EOL;
                        } else {
                            ++$stats['totals']['countDeletedFiles'];
                            ++$stats[$language]['countDeletedFiles'];
                            if ($config['verbose']) {
                                echo $infoTag . 'Deleted file: ' . $outputFullFileName . PHP_EOL;
                            }
                        }
                    }
                    continue;
                }
                if (file_exists($outputFullFileName)) {
                    unlink($outputFullFileName);
                }
                // --------------------------------------------------------------------
                // read and process expected test output (replace CRLF with LF, remove trailing LF)
                $expectedFileContents = file_get_contents($expectedFullFileName);
                if ($expectedFileContents === false) {
                    ++$stats['totals']['countSkippedTests'];
                    ++$stats[$language]['countSkippedTests'];
                    echo $warnTag . 'Cannot read expected test output file: ' . $expectedFullFileName . PHP_EOL;
                    continue;
                }
                ++$countTestsForFile;
                ++$stats['totals']['countTests'];
                ++$stats[$language]['countTests'];
                if (!isset($uniquePuzzleTests[$puzzleName])) {
                    $uniquePuzzleTests[$puzzleName] = 0;
                    $uniquePuzzlePassed[$puzzleName] = 0;
                }
                if ($config['dry-run'] or !$buildSuccessful) {
                    continue;
                }
                $expectedFileContents = str_replace("\r\n", "\n", $expectedFileContents);
                $i = strlen($expectedFileContents) - 1;
                while (($i > 0) and ($expectedFileContents[$i] == "\n")) {
                    --$i;
                }
                $expectedFileContents = substr($expectedFileContents, 0, $i + 1);
                $logFile = fopen($config['debugLog'], 'ab');
                if ($logFile !== false) {
                    $s = "| $language | $stringIdxTest | $sourceFullFileName |";
                    fwrite($logFile, str_repeat('=', strlen($s)) . PHP_EOL);
                    fwrite($logFile, $s . PHP_EOL);
                    fwrite($logFile, str_repeat('-', strlen($s)) . PHP_EOL);
                    fwrite($logFile, PHP_EOL);
                    fclose($logFile);
                }
                // --------------------------------------------------------------------
                // run the test
                $runCommand = $baseRunCommand . " < $inputFullFileName > $outputFullFileName 2>> "
                    . $config['debugLog'];
                $execOutput = [];
                $execResultCode = 0;
                $execResult = exec($runCommand, $execOutput, $execResultCode);
                if ($execResult === false) {
                    echo $errorTag . 'Execution unsuccessful for source: ' . $sourceFullFileName . PHP_EOL;
                    continue;
                }
                // --------------------------------------------------------------------
                // read and process test output (replace CRLF with LF, remove trailing LF)
                if (!file_exists($outputFullFileName)) {
                    echo $errorTag . 'Cannot read test output file: ' . $outputFullFileName . PHP_EOL;
                    continue;
                }
                $outputFileContents = file_get_contents($outputFullFileName);
                if ($outputFileContents === false) {
                    echo $warnTag . 'Cannot read test output file: ' . $outputFullFileName . PHP_EOL;
                    continue;
                }
                $outputFileContents = str_replace("\r\n", "\n", $outputFileContents);
                $i = strlen($outputFileContents) - 1;
                while (($i > 0) and ($outputFileContents[$i] == "\n")) {
                    --$i;
                }
                $outputFileContents = substr($outputFileContents, 0, $i + 1);
                // --------------------------------------------------------------------
                // evaluate test result
                if ($expectedFileContents !== $outputFileContents) {
                    $testsFailed[] = $stringIdxTest;
                    continue;
                }
                ++$stats['totals']['countPassedTests'];
                ++$stats[$language]['countPassedTests'];
                $uniquePuzzlePassed[$puzzleName] |= (1 << $idxTest);
            }
            // --------------------------------------------------------------------
            // print test results for the puzzle
            if ($config['clean']) {
                continue;
            }
            if ($countTestsForFile == 0) {
                echo $warnTag . 'No test input found for source: ' . $sourceFullFileName . PHP_EOL;
            }
            if (isset($uniquePuzzleTests[$puzzleName])) {
                $uniquePuzzleTests[$puzzleName] = max($uniquePuzzleTests[$puzzleName], $countTestsForFile);
            }
            if ($config['dry-run']) {
                if ($config['verbose']) {
                    echo $infoTag . str_pad(strval($countTestsForFile), $testIdxWidth, ' ', STR_PAD_LEFT) . ' test'
                        . ($countTestsForFile > 1 ? 's' : ' ') . ' : ' . $sourceFullFileName . PHP_EOL;
                }
                continue;
            }
            if ((count($testsFailed) > 0) and (count($testsFailed) == $countTestsForFile)) {
                echo $ansiRed . '[FAIL]' . $ansiReset
                    . ' -- all ' . str_pad(strval($countTestsForFile), $testIdxWidth, ' ', STR_PAD_LEFT) . ' test'
                    . ($countTestsForFile > 1 ? 's' : ' ') . ' failed : ' . $sourceFullFileName . PHP_EOL;
            } elseif (count($testsFailed) > 0) {
                echo $ansiRed . '[FAIL]' . $ansiReset
                    . ' -- ' . str_pad(strval(count($testsFailed)), $testIdxWidth, ' ', STR_PAD_LEFT) . ' of '
                    . str_pad(strval($countTestsForFile), $testIdxWidth, ' ', STR_PAD_LEFT) . ' test'
                    . ($countTestsForFile > 1 ? 's' : ' ') . ' failed : ' . $sourceFullFileName
                    . ' : [ #' . implode(' #', $testsFailed) . ' ]' . PHP_EOL;
            } elseif ($config['verbose'] and ($countTestsForFile > 0)) {
                echo $ansiGreen . '[PASS]' . $ansiReset . ' '
                    . str_pad(strval($countTestsForFile), $testIdxWidth, ' ', STR_PAD_LEFT) . ' test'
                    . ($countTestsForFile > 1 ? 's' : ' ') . ' OK : ' . $sourceFullFileName . PHP_EOL;
            }
        }
    }
    $stats[$language]['spentTime'] = hrtime(true) - $stats[$language]['startTime'];
}
// --------------------------------------------------------------------
// process some stats
$stats['totals']['uniquePuzzles'] = count($uniquePuzzleTests);
$stats['totals']['uniqueTests'] = array_sum($uniquePuzzleTests);
$stats['totals']['uniquePassedTests'] = 0;
foreach ($uniquePuzzlePassed as $bitmap) {
    $i = $bitmap;
    while ($i != 0) {
        $stats['totals']['uniquePassedTests'] += ($i & 1);
        $i >>= 1;
    }
}
$stats['totals']['spentTime'] = hrtime(true) - $stats['totals']['startTime'];
// --------------------------------------------------------------------
// print per language stats
if ($config['stats']) {
    echo '------+------------+------+------+------+------+-------+' . PHP_EOL;
    echo '      |Language    | Dirs | Progs| Tests|Failed|  Time |' . PHP_EOL;
    echo '------+------------+------+------+------+------+-------+' . PHP_EOL;
    foreach ($stats as $language => $stat) {
        if ($language == 'totals') {
            continue;
        }
        $status = $ansiGreen . '[PASS]' . $ansiReset;
        if ($config['dry-run'] or $config['clean']) {
            $status = str_repeat(' ', 6);
        }
        $testsStr = str_pad(substr(strval($stat['countTests']), 0, 6), 6, ' ', STR_PAD_LEFT);
        if (!$config['clean'] and ($stat['countTests'] == 0)) {
            $testsStr = $ansiYellow . $testsStr . $ansiReset;
            $status = $ansiYellow . '[WARN]' . $ansiReset;
        }
        $msg = '';
        if ($stat['countSkippedLanguages'] > 0) {
            $status = $ansiYellow . '[WARN]' . $ansiReset;
            $msg = ' language skipped.';
        } elseif ($config['clean']) {
            $status = str_repeat(' ', 6);
            if ($stat['countDeletedFiles'] > 0) {
                $msg .= ' ' . strval($stat['countDeletedFiles']) . ' files deleted.';
            }
        } else {
            if ($stat['countSkippedFiles'] > 0) {
                $status = $ansiYellow . '[WARN]' . $ansiReset;
                $msg .= ' ' . strval($stat['countSkippedFiles']) . ' puzzles skipped.';
            }
            if ($stat['countSkippedTests'] > 0) {
                $status = $ansiYellow . '[WARN]' . $ansiReset;
                $msg .= ' ' . strval($stat['countSkippedTests']) . ' tests skipped.';
            }
        }
        $countFailed = $stat['countTests'] - $stat['countPassedTests'];
        if ($config['dry-run'] or $config['clean']) {
            $countFailed = 0;
        }
        $failStr = str_pad(substr(strval($countFailed), 0, 6), 6, ' ', STR_PAD_LEFT);
        if ($countFailed > 0) {
            $failStr = $ansiRed . $failStr . $ansiReset;
            $status = $ansiRed . '[FAIL]' . $ansiReset;
        }
        echo $status
            . '|' . str_pad(substr($language, 0, 12), 12)
            . '|' . str_pad(substr(strval($stat['countDirectories']), 0, 6), 6, ' ', STR_PAD_LEFT)
            . '|' . str_pad(substr(strval($stat['countFiles']), 0, 6), 6, ' ', STR_PAD_LEFT)
            . '|' . $testsStr
            . '|' . $failStr
            . '|' . str_pad(number_format($stat['spentTime'] / 1000000000, 1, '.', ''), 6, ' ', STR_PAD_LEFT)
            . 's|' . $msg . PHP_EOL;
    }
    echo '------+------------+------+------+------+------+-------+' . PHP_EOL;
}
// --------------------------------------------------------------------
// print global results
$statusWidth = 78;
$wasLF = false;
if ($stats['totals']['countSkippedLanguages'] > 0) {
    echo PHP_EOL;
    $wasLF = true;
    echo $warnTag . 'Skipped ' . $stats['totals']['countSkippedLanguages'] . ' language'
        . ($stats['totals']['countSkippedLanguages'] > 1 ? 's' : '')
        . ' due to configuration errors.' . PHP_EOL;
}
if ($config['clean']) {
    if ($stats['totals']['countDeletedFiles'] > 0) {
        if (!$wasLF) {
            echo PHP_EOL;
            $wasLF = true;
        }
        echo $infoTag . 'Deleted ' . $stats['totals']['countDeletedFiles'] . ' temporary and test output file'
            . ($stats['totals']['countDeletedFiles'] > 1 ? 's' : '') . '.' . PHP_EOL;
    }
    if ($totalUnsuccessfulDeleteFiles > 0) {
        if (!$wasLF) {
            echo PHP_EOL;
            $wasLF = true;
        }
        echo $warnTag . 'Failed to delete ' . $totalUnsuccessfulDeleteFiles . ' file'
            . ($totalUnsuccessfulDeleteFiles > 1 ? 's' : '') . '.' . PHP_EOL . PHP_EOL;
        exit(1);
    }
    if ($stats['totals']['countDeletedFiles'] == 0) {
        if (!$wasLF) {
            echo PHP_EOL;
            $wasLF = true;
        }
        echo $infoTag . 'There was nothing to clean.' . PHP_EOL;
    }
    echo PHP_EOL;
    exit(0);
}
if ($config['lang-versions']) {
    if (!$wasLF) {
        echo PHP_EOL;
        $wasLF = true;
    }
    $countLangs = $stats['totals']['countLanguages'] - $stats['totals']['countSkippedLanguages'];
    echo $infoTag . 'Total: ' . $countLangs . ' programming language'
       . ($countLangs > 1 ? 's' : '')
       . ' found.' . PHP_EOL . PHP_EOL;
    exit(0);
}
if ($stats['totals']['countSkippedFiles'] > 0) {
    if (!$wasLF) {
        echo PHP_EOL;
        $wasLF = true;
    }
    echo $warnTag . 'Skipped ' . $stats['totals']['countSkippedFiles'] . ' puzzle'
        . ($stats['totals']['countSkippedFiles'] > 1 ? 's' : '')
        . ' due to missing source code file.' . PHP_EOL;
}
if ($stats['totals']['countSkippedTests'] > 0) {
    if (!$wasLF) {
        echo PHP_EOL;
        $wasLF = true;
    }
    echo $warnTag . 'Skipped ' . $stats['totals']['countSkippedTests'] . ' test'
        . ($stats['totals']['countSkippedTests'] > 1 ? 's' : '')
        . ' due to missing test case files.' . PHP_EOL;
}
if ($stats['totals']['countTests'] == 0) {
    if (!$wasLF) {
        echo PHP_EOL;
        $wasLF = true;
    }
    echo $ansiYellow . str_pad(' [WARN] There was nothing to test.', $statusWidth) . $ansiReset . PHP_EOL . PHP_EOL;
    exit(1);
}
if (!$wasLF) {
    echo PHP_EOL;
    $wasLF = true;
}
echo $infoTag . 'Total: ' . $stats['totals']['countPassedTests'] . ' / '
    . $stats['totals']['countTests'] . ' test'
    . ($stats['totals']['countTests'] > 1 ? 's' : '') . ' passed while testing '
    . $stats['totals']['countFiles'] . ' source file'
    . ($stats['totals']['countFiles'] > 1 ? 's' : '') . ' in '
    . $stats['totals']['countDirectories'] . ' director'
    . ($stats['totals']['countDirectories'] > 1 ? 'ies' : 'y') . ' in '
    . $stats['totals']['countLanguages'] . ' programming language'
    . ($stats['totals']['countLanguages'] > 1 ? 's' : '') . '.' . PHP_EOL;
if ($stats['totals']['uniquePuzzles'] != $stats['totals']['countFiles']) {
    echo $infoTag . 'Total unique: ' . $stats['totals']['uniquePassedTests'] . ' / '
    . $stats['totals']['uniqueTests'] . ' tests passed while testing '
    . $stats['totals']['uniquePuzzles'] . ' puzzle'
    . ($stats['totals']['uniquePuzzles'] > 1 ? 's' : '') . ' (in any language). ' . PHP_EOL;
}
$timeStr = number_format($stats['totals']['spentTime'] / 1000000000, 1, '.', '');
echo $infoTag . "Time spent: $timeStr seconds." . PHP_EOL . PHP_EOL;
if (!$config['dry-run']) {
    if ($stats['totals']['countPassedTests'] != $stats['totals']['countTests']) {
        echo $ansiRed . str_pad(' [FAIL] Some tests failed.', $statusWidth) . $ansiReset . PHP_EOL . PHP_EOL;
        exit(1);
    }
    echo $ansiGreen . str_pad(' [OK] All tests passed.', $statusWidth) . $ansiReset . PHP_EOL . PHP_EOL;
}
exit(0);
// --------------------------------------------------------------------
