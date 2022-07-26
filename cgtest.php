#!/usr/bin/env php
<?php

/**
 * CGTest v1.6.0
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
$version = 'v1.6.0';
$zeroLanguageStat = [
    'countLanguages' => 0,
    'countSkippedLanguages' => 0,
    'countDirectories' => 0,
    'countFiles' => 0,
    'countPassedFiles' => 0,
    'countRunOnlyFiles' => 0,
    'countFailedFiles' => 0,
    'countSkippedFiles' => 0,
    'countTests' => 0,
    'countRunOnlyTests' => 0,
    'countPassedTests' => 0,
    'countFailedTests' => 0,
    'countSkippedTests' => 0,
    'countDeletedFiles' => 0,
    'startTime' => 0,
    'spentTime' => 0,
];
$languageStats = [];
$languageStats['totals'] = $zeroLanguageStat;
$languageStats['totals']['startTime'] = hrtime(true);
$languageStats['unique'] = $zeroLanguageStat;
$zeroPuzzleStat = [
    'countFailedFiles' => 0,
    'countRunOnlyFiles' => 0,
    'countSkippedFiles' => 0,
    'countTests' => 0,
    'countRunOnlyTests' => 0,
    'countSkippedTests' => 0,
    'failedTests' => 0,     //bitmap of failed tests (bit #0 = 1, if test #01 failed, etc.)
    'runOnlyTests' => 0,    //bitmap of runOnly tests
    'skippedTests' => 0,    //bitmap of skipped tests
];
$puzzleStats = [];
$directoryStats = [];
$slowTests = [];
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
$csprojExtension = '.csproj';
$vbprojExtension = '.vbproj';
$vbProjectName = 'vb_project';
$defaultConfig = [
    'dry-run' => false,
    'run-only' => false,
    'ansi' => true,
    'verbose' => false,
    'stats' => false,
    'lang-versions' => false,
    'clean' => false,
    'test-case' => 'all',
    'slowThreshold' => 5, // in seconds
    'inputPath' => '.tests/input/',
    'inputPattern' => '%p_i%t.txt',
    'expectedPath' => '.tests/expected/',
    'expectedPattern' => '%p_e%t.txt',
    'outputPath' => '.tests/output/',
    'outputPattern' => '%p_o%t_%l.txt',
    'buildPath' => '.tests/temp/',
    'debugLog' => '.tests/output/_debug_log.txt',
    'buildLog' => '.tests/temp/_build_log.txt',
    'languages' => ['php'],
    'puzzles' => [],
    'runOnlyPuzzles' => [],
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
        // note: omitting -ldl -lcrypt from CG settings
        'buildCommand' => 'gcc -lm -lpthread -o %b%p_%l.exe %s',
        'runCommand' => '%b%p_%l.exe',
        'cleanPatterns' => ['%b%p_%l.exe'],
    ],
    'c#' => [
        'sourcePath' => 'c#/',
        'sourceExtension' => '.cs',
        'codinGameVersion' => '.NET 6.0.401',
        'versionCommand' => 'dotnet --version',
        'buildCommand' => 'dotnet publish %b%p' . $csprojExtension
            . ' -o %b --nologo --use-current-runtime --sc -v:q',
        'runCommand' => '%b%p' . (PHP_OS_FAMILY == 'Windows' ? '.exe' : ''),
        'cleanPatterns' => [
            '%b%p' . (PHP_OS_FAMILY == 'Windows' ? '.exe' : ''),
            '%b%p.pdb',
        ],
    ],
    'c++' => [
        'sourcePath' => 'c++/',
        'sourceExtension' => '.cpp',
        'codinGameVersion' => 'g++ 11.2.0-20',
        'versionCommand' => 'g++ --version',
        // note: omitting -ldl -lcrypt from CG settings
        'buildCommand' => 'g++ -lm -lpthread -m64 -std=c++17 -x c++ -o %b%p_%l.exe %s',
        'runCommand' => '%b%p_%l.exe',
        'cleanPatterns' => ['%b%p_%l.exe'],
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
        'buildCommand' => 'dmd -od=%b -of=%b%p_%l.exe %s',
        'runCommand' => '%b%p_%l.exe',
        'cleanPatterns' => [
            '%b%p_%l.exe',
            '%b%p_%l.obj',
            '%b%p_%l.o',
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
        'codinGameVersion' => '.NET 6.0.401',
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
        'buildCommand' => 'ghc -outputdir %b -o %b%p_%l.exe %s',
        'runCommand' => '%b%p_%l.exe',
        'cleanPatterns' => [
            '%b%p_%l.exe',
            '%bMain.o',
            '%bMain.hi',
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
    'javascript' => [
        'sourcePath' => 'javascript/',
        'sourceExtension' => '.js',
        'codinGameVersion' => 'node.js v16.14.2',
        'versionCommand' => 'node --version',
        'buildCommand' => '',
        'runCommand' => 'node -r polyfill.js %s',
        'cleanPatterns' => [],
    ],
    'kotlin' => [
        'sourcePath' => 'kotlin/',
        'sourceExtension' => '.kt',
        'codinGameVersion' => 'kotlinc-jvm 1.5.0 (JRE 11.0.2+9)',
        'versionCommand' => 'kotlinc -version',
        'buildCommand' => 'kotlinc -include-runtime -d %b%p_%l.jar %s',
        'runCommand' => 'java -jar %b%p_%l.jar',
        'cleanPatterns' => ['%b%p_%l.jar'],
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
        'buildCommand' => 'gcc -o %b%p_%l.exe -lobjc -lgnustep-base -F /usr/lib/GNUstep -I /usr/include/GNUstep'
            . ' -fconstant-string-class=NSConstantString %s',
        'runCommand' => '%b%p_%l.exe',
        'cleanPatterns' => ['%b%p_%l.exe'],
    ],
    // todo: try out also in Windows:
    'ocaml' => [
        'sourcePath' => 'ocaml/',
        'sourceExtension' => '.ml',
        'codinGameVersion' => 'The OCaml native-code compiler, version 4.12.0',
        'versionCommand' => 'ocamlopt -v',
        'buildCommand' => 'ocamlopt %s -o %b%p_%l.exe',
        'runCommand' => '%b%p_%l.exe',
        'cleanPatterns' => ['%b%p_%l.exe'],
    ],
    'pascal' => [
        'sourcePath' => 'pascal/',
        'sourceExtension' => '.pas',
        'codinGameVersion' => 'Free Pascal Compiler 3.2.2',
        'versionCommand' => 'fpc -iW',
        'buildCommand' => 'fpc -v0 -FE%b -o%p_%l.exe %s',
        'runCommand' => '%b%p_%l.exe',
        'cleanPatterns' => [
            '%b%p_%l.exe',
            '%b%p.o',
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
        'buildCommand' => 'rustc %s -o%b%p_%l.exe',
        'runCommand' => '%b%p_%l.exe',
        'cleanPatterns' => [
            '%b%p_%l.exe',
            '%b%p_%l.pdb',
        ],
    ],
    'scala' => [
        'sourcePath' => 'scala/',
        'sourceExtension' => '.scala',
        'codinGameVersion' => 'Scala code runner version 2.13.5',
        'versionCommand' => 'scala --version',
        'buildCommand' => '',
        'runCommand' => 'scala -cp %b %s',
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
    'typescript' => [
        'sourcePath' => 'typescript/',
        'sourceExtension' => '.ts',
        'codinGameVersion' => 'node.js v16.14.2; Typescript Compiler Version 4.6.3',
        'versionCommand' => 'node --version',
        // 'versionCommand' => 'tsc --version',
        'buildCommand' => '',
        'runCommand' => 'node -r polyfill.js %s',
        'cleanPatterns' => [],
    ],
    'vb.net' => [
        'sourcePath' => 'vb.net/',
        'sourceExtension' => '.vb',
        'codinGameVersion' => '.NET 6.0.401',
        'versionCommand' => 'dotnet --version',
        'buildCommand' => 'dotnet publish %b' . $vbProjectName . $vbprojExtension
            . ' -o %b --nologo --use-current-runtime --sc -v:q', // -v:q
        'runCommand' => '%b' . $vbProjectName . (PHP_OS_FAMILY == 'Windows' ? '.exe' : ''),
        'cleanPatterns' => [
            '%b' . $vbProjectName . (PHP_OS_FAMILY == 'Windows' ? '.exe' : ''),
            '%b' . $vbProjectName . '.pdb',
        ],
    ],
    // unsupported on CodinGame
    // todo: check
    'cobol' => [
        'sourcePath' => 'cobol/',
        'sourceExtension' => '.cob',
        'codinGameVersion' => 'cobc (GnuCOBOL) 3.1.2.0',
        'versionCommand' => 'cobc --version',
        'buildCommand' => 'cobc -x -o%b%p_%l.exe %s',
        'runCommand' => '%b%p_%l.exe',
        'cleanPatterns' => ['%b%p_%l.exe'],
    ],
    'fortran' => [
        'sourcePath' => 'fortran/',
        'sourceExtension' => '.f90',
        'codinGameVersion' => 'GNU Fortran 11.2.0-20',
        'versionCommand' => 'gfortran --version',
        'buildCommand' => 'gfortran -o%b%p_%l.exe %s',
        'runCommand' => '%b%p_%l.exe',
        'cleanPatterns' => ['%b%p_%l.exe'],
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
    $defaultConfig[$language]['runOnlyPuzzles'] = [];
}
$booleanConfigKeys = ['dry-run', 'run-only', 'ansi', 'verbose', 'stats', 'lang-versions', 'clean', 'show-defaults'];
$nonEmptyStringConfigKeys = ['inputPattern', 'expectedPattern', 'outputPattern'];
$optionalStringConfigKeys = ['inputPath', 'expectedPath', 'outputPath', 'buildPath', 'test-case'];
$arrayConfigKeys = ['languages', 'puzzles', 'runOnlyPuzzles'];
$languageStatsSpecKeys = ['totals', 'unique'];
$reservedConfigKeys = array_merge(
    $booleanConfigKeys,
    $nonEmptyStringConfigKeys,
    $optionalStringConfigKeys,
    $arrayConfigKeys,
    $languageStatsSpecKeys,
);
$languageNonEmptyStringConfigKeys = ['sourceExtension', 'runCommand'];
$languageOptionalStringConfigKeys = ['sourcePath', 'codinGameVersion', 'versionCommand', 'buildCommand'];
$languageArrayConfigKeys = ['excludePuzzles', 'includePuzzles', 'runOnlyPuzzles'];
$csprojTemplate =
"<Project Sdk=\"Microsoft.NET.Sdk\">
  <ItemGroup>
    <Compile Include = \"../../%s\"/>
  </ItemGroup>
  <PropertyGroup>
    <OutputPath>bin/</OutputPath>
    <EnableDefaultCompileItems>false</EnableDefaultCompileItems>
    <OutputType>Exe</OutputType>
    <TargetFramework>net6.0</TargetFramework>
    <PublishTrimmed>true</PublishTrimmed>
    <PublishReadyToRun>true</PublishReadyToRun>
    <PublishSingleFile>true</PublishSingleFile>
  </PropertyGroup>
</Project>
";
$csDirectoryBuildPropsFilename = "Directory.Build.props";
$csDirectoryBuildPropsTemplate =
"<Project>
  <PropertyGroup>
    <BaseIntermediateOutputPath>obj/</BaseIntermediateOutputPath>
  </PropertyGroup>
</Project>
";
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
        echo 'Usage:   php cgtest.php [options] [puzzles]' . PHP_EOL;
        echo PHP_EOL;
        echo 'Options:' . PHP_EOL;
        echo '   --version          Display CGTest application version' . PHP_EOL;
        echo '   --help             Display this help message' . PHP_EOL;
        echo '   --dry-run          Do not run the tests; only show what test cases would run' . PHP_EOL;
        echo '   --run-only         Run the tests, but do not evaluate results' . PHP_EOL;
        echo '   --ansi             Use color output [default]' . PHP_EOL;
        echo '   --no-ansi          Disable color output' . PHP_EOL;
        echo '   --verbose          Increase the verbosity of messages: also show each passed tests' . PHP_EOL;
        echo '   --stats            Show per-language test stats' . PHP_EOL;
        echo '   --lang-versions    Show versions for all configured programming languages' . PHP_EOL;
        echo '   --show-defaults    Show default configuration settings (as json)' . PHP_EOL;
        echo '   --clean            Delete temporary and output files of previous test run' . PHP_EOL;
        echo '   --config=FILENAME  Use configfile [default: ' . $defaultConfigFileName . ']' . PHP_EOL;
        echo '   --test-case=id     Run only a specific test case [default: all]' . PHP_EOL;
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
    if ((substr($arg, 0, 12) == '--test-case=') and (strlen($arg) > 12)) {
        if (isset($argumentConfig['test-case'])) {
            echo $errorTag . 'Invalid arguments: test-case can be given only once.' . PHP_EOL . PHP_EOL;
            exit(2);
        }
        $argumentConfig['test-case'] = substr($arg, 12);
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
    $path = substr($argv[$i], 0, $j + 1);
    if ($path == '') {
        $path = $noPathKey;
    }
    $puzzle = substr($argv[$i], $j + 1);
    if ($puzzle == '') {
        echo $errorTag . 'Invalid puzzle name: ' . $argv[$i] . PHP_EOL . PHP_EOL;
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
        or (in_array($configKey, $arrayConfigKeys, true) !== false)
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
$passTag = $ansiGreen . '[PASS]' . $ansiReset . ' ';
$failTag = $ansiRed . '[FAIL]' . $ansiReset . ' ';
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
if ($config['test-case'] != 'all') {
    $value = intval($config['test-case']);
    if (($value <= 0) or ($value > 99)) {
        echo $errorTag . "Invalid arguments: test-case must be 'all' or between 01 and 99" . PHP_EOL . PHP_EOL;
        exit(2);
    }
}

if ($config['lang-versions'] and $config['clean']) {
    echo $errorTag . 'Invalid arguments: cannot use both --lang-versions and --clean' . PHP_EOL . PHP_EOL;
    exit(2);
}
if ($config['lang-versions'] and $config['dry-run']) {
    echo $errorTag . 'Invalid arguments: cannot use both --lang-versions and --dry-run' . PHP_EOL . PHP_EOL;
    exit(2);
}
if ($config['lang-versions'] and $config['run-only']) {
    echo $errorTag . 'Invalid arguments: cannot use both --lang-versions and --run-only' . PHP_EOL . PHP_EOL;
    exit(2);
}
if ($config['clean'] and $config['dry-run']) {
    echo $errorTag . 'Invalid arguments: cannot use both --clean and --dry-run' . PHP_EOL . PHP_EOL;
    exit(2);
}
if ($config['clean'] and $config['run-only']) {
    echo $errorTag . 'Invalid arguments: cannot use both --clean and --run-only' . PHP_EOL . PHP_EOL;
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
            ++$languageStats['totals']['countDeletedFiles'];
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
            ++$languageStats['totals']['countDeletedFiles'];
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
    echo $infoTag . '<STDERR> output from the test runs redirected to file: ' . $config['debugLog'] . PHP_EOL;
    echo $infoTag . 'Compilers / interpreters\' messages redirected to file: ' . $config['buildLog'] . PHP_EOL;
}
if (!$config['clean'] and !$config['lang-versions'] and ($config['test-case'] != 'all')) {
    echo $infoTag . 'Limited to test case #' . $config['test-case'] . PHP_EOL;
}
// --------------------------------------------------------------------
// main loop: tests run by language
foreach ($config['languages'] as $language) {
    $languageStats[$language] = $zeroLanguageStat;
    $languageStats[$language]['startTime'] = hrtime(true);
    ++$languageStats[$language]['countLanguages'];
    // --------------------------------------------------------------------
    // check for configuration errors in per-language settings
    $isLanguageOk = true;
    if ((trim($language) == '') or (array_search($language, $reservedConfigKeys, true) !== false)) {
        echo $warnTag . 'Invalid language name: ' . $language . PHP_EOL . PHP_EOL;
        ++$languageStats[$language]['countSkippedLanguages'];
        continue;
    }
    if (!isset($config[$language]) or !is_array($config[$language])) {
        echo $warnTag . 'No configuration available for language: ' . $language . PHP_EOL;
        ++$languageStats[$language]['countSkippedLanguages'];
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
        ++$languageStats[$language]['countSkippedLanguages'];
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
        if (($execResult === false) or ($execResultCode != 0)) {
            echo $warnTag . 'Language is unavailable: ' . $config[$language]['versionCommand'] . PHP_EOL;
            ++$languageStats[$language]['countSkippedLanguages'];
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
    // Special case for C# and VB.NET: delete Directory.build.props file
    if ((($language == 'c#') or ($language == 'vb.net')) and $config['clean']) {
        if (file_exists($csDirectoryBuildPropsFilename)) {
            $unlinkResult = unlink($csDirectoryBuildPropsFilename);
            if (!$unlinkResult) {
                ++$totalUnsuccessfulDeleteFiles;
                echo $warnTag . 'Could not delete file: ' . $csDirectoryBuildPropsFilename . PHP_EOL;
            } else {
                ++$languageStats['totals']['countDeletedFiles'];
                if ($config['verbose']) {
                    echo $infoTag . 'Deleted file: ' . $csDirectoryBuildPropsFilename . PHP_EOL;
                }
            }
        }
    }
    // --------------------------------------------------------------------
    // prepare puzzle list for this language
    $puzzles = $config['puzzles'];
    if (($argumentConfig['puzzles'] ?? []) == []) {
        foreach ($config['runOnlyPuzzles'] as $sourcePath => $filesArray) {
            if (!isset($puzzles[$sourcePath])) {
                $puzzles[$sourcePath] = [];
            }
            foreach ($filesArray as $puzzleNameOriginal) {
                $puzzles[$sourcePath][] = $puzzleNameOriginal;
            }
        }
        foreach ($config[$language]['includePuzzles'] as $sourcePath => $filesArray) {
            if (!isset($puzzles[$sourcePath])) {
                $puzzles[$sourcePath] = [];
            }
            foreach ($filesArray as $puzzleNameOriginal) {
                $puzzles[$sourcePath][] = $puzzleNameOriginal;
            }
        }
        foreach ($config[$language]['runOnlyPuzzles'] as $sourcePath => $filesArray) {
            if (!isset($puzzles[$sourcePath])) {
                $puzzles[$sourcePath] = [];
            }
            foreach ($filesArray as $puzzleNameOriginal) {
                $puzzles[$sourcePath][] = $puzzleNameOriginal;
            }
        }
    }
    // --------------------------------------------------------------------
    // loop: for each puzzle to test or clean
    foreach ($puzzles as $sourcePathKey => $filesArray) {
        $sourcePath = ($sourcePathKey == $noPathKey ? '' : $sourcePathKey);
        if (($config[$language]['sourcePath'] == '') or ($languageStats[$language]['countDirectories'] == 0)) {
            ++$languageStats[$language]['countDirectories'];
        }
        foreach ($filesArray as $puzzleName) {
            if (
                (($argumentConfig['puzzles'] ?? []) == [])
                and (in_array($puzzleName, $config[$language]['excludePuzzles'], true))
            ) {
                continue;
            }
            $runOnlyCurrentPuzzle = $config['run-only'];
            if (!$runOnlyCurrentPuzzle) {
                foreach ($config['runOnlyPuzzles'] as $sourcePathRunOnly => $filesArrayRunOnly) {
                    foreach ($filesArrayRunOnly as $puzzleNameRunOnly) {
                        if ($puzzleNameRunOnly == $puzzleName) {
                            $runOnlyCurrentPuzzle = true;
                            break 2;
                        }
                    }
                }
                if (!$runOnlyCurrentPuzzle) {
                    foreach ($config[$language]['runOnlyPuzzles'] as $sourcePathRunOnly => $filesArrayRunOnly) {
                        foreach ($filesArrayRunOnly as $puzzleNameRunOnly) {
                            if ($puzzleNameRunOnly == $puzzleName) {
                                $runOnlyCurrentPuzzle = true;
                                break 2;
                            }
                        }
                    }
                }
            }
            // --------------------------------------------------------------------
            // check source file
            $sourceFileName = $puzzleName . $config[$language]['sourceExtension'];
            if ($config[$language]['sourcePath'] == '') {
                $sourceFullFileName = $sourcePath . $sourceFileName;
                $directoryStats[$sourcePath] = 1;
            } else {
                $sourceFullFileName = $config[$language]['sourcePath'] . $sourceFileName;
                $directoryStats[$config[$language]['sourcePath']] = 1;
            }
            ++$languageStats[$language]['countFiles'];
            if (!file_exists($sourceFullFileName)) {
                if (!$config['clean']) {
                    echo $warnTag . 'Cannot find sourcefile: ' . $sourceFullFileName . PHP_EOL;
                }
                ++$languageStats[$language]['countSkippedFiles'];
                if (!isset($puzzleStats[$puzzleName])) {
                    $puzzleStats[$puzzleName] = $zeroPuzzleStat;
                }
                $puzzleStats[$puzzleName]['countSkippedFiles'] = 1;
                continue;
            }
            if ($runOnlyCurrentPuzzle) {
                ++$languageStats[$language]['countRunOnlyFiles'];
                if (!isset($puzzleStats[$puzzleName])) {
                    $puzzleStats[$puzzleName] = $zeroPuzzleStat;
                }
                $puzzleStats[$puzzleName]['countRunOnlyFiles'] = 1;
            }
            // --------------------------------------------------------------------
            // clean temporary and output files from previous test run
            if ($config['clean']) {
                foreach ($config[$language]['cleanPatterns'] as $patternToClean) {
                    $tempFileName = str_replace(
                        ['%l', '%p', '%o', '%b'],
                        [$language, $puzzleName, $config['outputPath'], $config['buildPath']],
                        $patternToClean
                    );
                    if (file_exists($tempFileName)) {
                        $unlinkResult = unlink($tempFileName);
                        if (!$unlinkResult) {
                            ++$totalUnsuccessfulDeleteFiles;
                            echo $warnTag . 'Could not delete file: ' . $tempFileName . PHP_EOL;
                        } else {
                            ++$languageStats[$language]['countDeletedFiles'];
                            if ($config['verbose']) {
                                echo $infoTag . 'Deleted file: ' . $tempFileName . PHP_EOL;
                            }
                        }
                    }
                }
            }
            // --------------------------------------------------------------------
            // Special case for Haskell: Main.o must be deleted before each build
            if ($language == 'haskell') {
                $tempFileName = $config['buildPath'] . 'Main.o';
                if (file_exists($tempFileName)) {
                    unlink($tempFileName);
                }
                $tempFileName = $config['buildPath'] . 'Main.hi';
                if (file_exists($tempFileName)) {
                    unlink($tempFileName);
                }
            }
            // --------------------------------------------------------------------
            // Special case for C# and VB.NET
            if ((($language == 'c#') or ($language == 'vb.net')) and !$config['dry-run']) {
                if ($language == 'c#') {
                    $csprojFilename = $config['buildPath'] . $puzzleName . $csprojExtension;
                } else {
                    $csprojFilename = $config['buildPath'] . $vbProjectName . $vbprojExtension;
                }
                if (file_exists($csprojFilename)) {
                    $unlinkResult = unlink($csprojFilename);
                    if ($config['clean']) {
                        if (!$unlinkResult) {
                            ++$totalUnsuccessfulDeleteFiles;
                            echo $warnTag . 'Could not delete file: ' . $csprojFilename . PHP_EOL;
                        } else {
                            ++$languageStats[$language]['countDeletedFiles'];
                            if ($config['verbose']) {
                                echo $infoTag . 'Deleted file: ' . $csprojFilename . PHP_EOL;
                            }
                        }
                    }
                }
                if (file_exists($csDirectoryBuildPropsFilename)) {
                    unlink($csDirectoryBuildPropsFilename);
                }
                if (!$config['clean']) {
                    $csprojFileContents = str_replace(
                        ['%l', '%p', '%o', '%b', '%s'],
                        [$language, $puzzleName, $config['outputPath'], $config['buildPath'], $sourceFullFileName],
                        $csprojTemplate
                    );
                    $csprojFile = fopen($csprojFilename, 'w');
                    if ($csprojFile === false) {
                        echo $errorTag . 'Cannot create project file for C#: ' . $csprojFilename . PHP_EOL . PHP_EOL;
                        exit(2);
                    }
                    fwrite($csprojFile, $csprojFileContents);
                    fclose($csprojFile);
                    $csDirectoryBuildPropsFileContents = str_replace(
                        ['%l', '%p', '%o', '%b', '%s'],
                        [$language, $puzzleName, $config['outputPath'], $config['buildPath'], $sourceFullFileName],
                        $csDirectoryBuildPropsTemplate
                    );
                    $csDirectoryBuildPropsFile = fopen($csDirectoryBuildPropsFilename, 'w');
                    if ($csDirectoryBuildPropsFile === false) {
                        echo $errorTag . 'Cannot create project file for ' . $language . ': '
                            . $csDirectoryBuildPropsFilename . PHP_EOL . PHP_EOL;
                        exit(2);
                    }
                    fwrite($csDirectoryBuildPropsFile, $csDirectoryBuildPropsFileContents);
                    fclose($csDirectoryBuildPropsFile);
                }
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
                    ['%l', '%p', '%o', '%b', '%s'],
                    [$language, $puzzleName, $config['outputPath'], $config['buildPath'], $sourceFullFileName],
                    $config[$language]['buildCommand']
                );
                if (PHP_OS_FAMILY == 'Windows') {
                    $baseBuildCommand = str_replace('/', '\\', $baseBuildCommand);
                }
                $buildCommand = $baseBuildCommand . ' >> ' . $config['debugLog'] . ' 2>> ' . $config['buildLog'];
                $execOutput = [];
                $execResultCode = 0;
                $execResult = exec($buildCommand, $execOutput, $execResultCode);
                if (($execResult === false) or ($execResultCode != 0)) {
                    $buildSuccessful = false;
                    echo $warnTag . 'Build unsuccessful for source: ' . $sourceFullFileName . PHP_EOL;
                }
            }
            // --------------------------------------------------------------------
            // preparation common for all tests
            $baseRunCommand = str_replace(
                ['%l', '%p', '%o', '%b', '%s'],
                [$language, $puzzleName, $config['outputPath'], $config['buildPath'], $sourceFullFileName],
                $config[$language]['runCommand']
            );
            if (PHP_OS_FAMILY == 'Windows') {
                $baseRunCommand = str_replace('/', '\\', $baseRunCommand);
            }
            $testsFailed = [];
            $countTestsForFile = 0;
            $countSkippedTestsForFile = 0;
            $idxTest = 0;
            $spentTimePuzzle = 0;
            // --------------------------------------------------------------------
            // loop: for each test case for the puzzle
            while (true) {
                if ($config['test-case'] != 'all') {
                    if ($idxTest != 0) {
                        break;
                    }
                    $idxTest = intval($config['test-case']) - 1;
                }
                ++$idxTest;
                if ($idxTest > 99) {
                    echo $warnTag . 'Maximum number of test cases per puzzle is 99. Exceeded at: ' . $sourceFullFileName
                        . PHP_EOL;
                    break;
                }
                // --------------------------------------------------------------------
                // check input and expected output
                $stringIdxTest = str_pad(strval($idxTest), $testIdxWidth, '0', STR_PAD_LEFT);
                $inputFullFileName = str_replace(
                    ['%l', '%p', '%t'],
                    [$language, $puzzleName, $stringIdxTest],
                    $config['inputPath'] . $config['inputPattern']
                );
                if (!file_exists($inputFullFileName)) {
                    if (($countTestsForFile == 0) and !$config['clean']) {
                        ++$languageStats[$language]['countSkippedTests'];
                        ++$countSkippedTestsForFile;
                    }
                    break;
                }
                $expectedFullFileName = str_replace(
                    ['%l', '%p', '%t'],
                    [$language, $puzzleName, $stringIdxTest],
                    $config['expectedPath'] . $config['expectedPattern']
                );
                if (!$runOnlyCurrentPuzzle and !$config['clean']) {
                    if (!file_exists($expectedFullFileName)) {
                        ++$languageStats[$language]['countSkippedTests'];
                        ++$countSkippedTestsForFile;
                        echo $warnTag . 'Cannot read expected test output file: ' . $expectedFullFileName . PHP_EOL;
                        continue;
                    }
                }
                $outputFullFileName = str_replace(
                    ['%l', '%p', '%t'],
                    [$language, $puzzleName, $stringIdxTest],
                    $config['outputPath'] . $config['outputPattern']
                );
                if (!$runOnlyCurrentPuzzle and ($expectedFullFileName === $outputFullFileName)) {
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
                            ++$languageStats[$language]['countDeletedFiles'];
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
                $expectedFileContents = '';
                if (!$runOnlyCurrentPuzzle) {
                    $expectedFileContents = file_get_contents($expectedFullFileName);
                    if ($expectedFileContents === false) {
                        ++$languageStats[$language]['countSkippedTests'];
                        ++$countSkippedTestsForFile;
                        echo $warnTag . 'Cannot read expected test output file: ' . $expectedFullFileName . PHP_EOL;
                        continue;
                    }
                }
                ++$countTestsForFile;
                ++$languageStats[$language]['countTests'];
                if (!isset($puzzleStats[$puzzleName])) {
                    $puzzleStats[$puzzleName] = $zeroPuzzleStat;
                }
                if ($runOnlyCurrentPuzzle) {
                    ++$languageStats[$language]['countRunOnlyTests'];
                    $puzzleStats[$puzzleName]['runOnlyTests'] |= (1 << $idxTest);
                }
                if ($config['dry-run']) {
                    continue;
                }
                if (!$buildSuccessful) {
                    $testsFailed[] = $stringIdxTest;
                    ++$languageStats[$language]['countFailedTests'];
                    $puzzleStats[$puzzleName]['failedTests'] |= (1 << $idxTest);
                    continue;
                }
                if (!$runOnlyCurrentPuzzle) {
                    $expectedFileContents = str_replace("\r\n", "\n", $expectedFileContents);
                    $i = strlen($expectedFileContents) - 1;
                    while (($i > 0) and ($expectedFileContents[$i] == "\n")) {
                        --$i;
                    }
                    $expectedFileContents = substr($expectedFileContents, 0, $i + 1);
                }
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
                $testStartTime = hrtime(true);
                $execResult = exec($runCommand, $execOutput, $execResultCode);
                if (($execResult === false) or (!is_null($execResultCode) and ($execResultCode != 0))) {
                    echo $errorTag . 'Execution unsuccessful for source: ' . $sourceFullFileName . PHP_EOL;
                    $testsFailed[] = $stringIdxTest;
                    ++$languageStats[$language]['countFailedTests'];
                    $puzzleStats[$puzzleName]['failedTests'] |= (1 << $idxTest);
                    continue;
                }
                $spentTimeTestCase = (hrtime(true) - $testStartTime) / 1000000000;
                if (
                    (($config['slowThreshold'] ?? 0) > 0)
                    and ($spentTimeTestCase >= ($config['slowThreshold'] ?? 0))
                ) {
                    $slowTests[] = [
                        'language' => $language,
                        'puzzle' => $puzzleName,
                        'test-case' => $idxTest,
                        'time' => $spentTimeTestCase,
                    ];
                }
                $spentTimePuzzle += $spentTimeTestCase;
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
                if (!$runOnlyCurrentPuzzle and ($expectedFileContents !== $outputFileContents)) {
                    $testsFailed[] = $stringIdxTest;
                    ++$languageStats[$language]['countFailedTests'];
                    $puzzleStats[$puzzleName]['failedTests'] |= (1 << $idxTest);
                    continue;
                }
                ++$languageStats[$language]['countPassedTests'];
            }
            // --------------------------------------------------------------------
            // print test results for the puzzle
            if ($config['clean']) {
                continue;
            }
            if ($countTestsForFile == 0) {
                echo $warnTag . 'No test input found for source: ' . $sourceFullFileName . PHP_EOL;
            }
            if (isset($puzzleStats[$puzzleName])) {
                $puzzleStats[$puzzleName]['countTests']
                    = max($puzzleStats[$puzzleName]['countTests'], $countTestsForFile);
                $puzzleStats[$puzzleName]['countSkippedTests']
                    = max($puzzleStats[$puzzleName]['countSkippedTests'], $countSkippedTestsForFile);
                if ($runOnlyCurrentPuzzle) {
                    $puzzleStats[$puzzleName]['countRunOnlyTests']
                    = max($puzzleStats[$puzzleName]['countRunOnlyTests'], $countTestsForFile);
                }
            }
            if ($config['dry-run']) {
                if ($config['verbose']) {
                    echo $infoTag . str_pad(strval($countTestsForFile), $testIdxWidth, ' ', STR_PAD_LEFT) . ' test'
                        . ($countTestsForFile > 1 ? 's' : ' ') . ' : ' . $sourceFullFileName . PHP_EOL;
                }
                continue;
            }
            if (count($testsFailed) > 0) {
                ++$languageStats[$language]['countFailedFiles'];
                $puzzleStats[$puzzleName]['countFailedFiles'] = 1;
                if (count($testsFailed) == $countTestsForFile) {
                    echo $failTag
                        . ' -- all ' . str_pad(strval($countTestsForFile), $testIdxWidth, ' ', STR_PAD_LEFT) . ' test'
                        . ($countTestsForFile > 1 ? 's' : ' ') . ' failed : ' . $sourceFullFileName . PHP_EOL;
                    continue;
                }
                echo $failTag
                    . ' -- ' . str_pad(strval(count($testsFailed)), $testIdxWidth, ' ', STR_PAD_LEFT) . ' of '
                    . str_pad(strval($countTestsForFile), $testIdxWidth, ' ', STR_PAD_LEFT) . ' test'
                    . ($countTestsForFile > 1 ? 's' : ' ') . ' failed : ' . $sourceFullFileName
                    . ' : [ #' . implode(' #', $testsFailed) . ' ]' . PHP_EOL;
                continue;
            }
            if ($countTestsForFile > 0) {
                ++$languageStats[$language]['countPassedFiles'];
                if ($config['verbose']) {
                    echo $runOnlyCurrentPuzzle ? $infoTag : $passTag;
                    echo str_pad(strval($countTestsForFile), $testIdxWidth, ' ', STR_PAD_LEFT) . ' test'
                        . ($countTestsForFile > 1 ? 's' : ' ') . ' OK : ' . $sourceFullFileName . PHP_EOL;
                }
            }
        }
    }
    $languageStats[$language]['spentTime'] = hrtime(true) - $languageStats[$language]['startTime'];
}
// --------------------------------------------------------------------
// process stats per unique puzzle
foreach ($languageStats as $language => $stat) {
    if (in_array($language, $languageStatsSpecKeys, true)) {
        continue;
    }
    foreach ($stat as $name => $count) {
        if ($name == 'startTime') {
            continue;
        }
        $languageStats['totals'][$name] += $count;
    }
}
$languageStats['unique']['countDirectories'] = count($directoryStats);
$languageStats['unique']['countFiles'] = count($puzzleStats);
foreach ($puzzleStats as $puzzleStat) {
    $languageStats['unique']['countRunOnlyFiles'] += $puzzleStat['countRunOnlyFiles'];
    $languageStats['unique']['countTests'] += $puzzleStat['countTests'];
    $languageStats['unique']['countRunOnlyTests'] += $puzzleStat['countRunOnlyTests'];
    $languageStats['unique']['countSkippedTests'] += $puzzleStat['countSkippedTests'];
    $countFailed = 0;
    $i = $puzzleStat['failedTests'];
    while ($i != 0) {
        $countFailed += ($i & 1);
        $i >>= 1;
    }
    $languageStats['unique']['countFailedTests'] += $countFailed;
    if ($countFailed > 0) {
        ++$languageStats['unique']['countFailedFiles'];
    } elseif ($puzzleStat['countSkippedFiles'] > 0) {
        ++$languageStats['unique']['countSkippedFiles'];
    } elseif ($puzzleStat['countTests'] > 0) {
        ++$languageStats['unique']['countPassedFiles'];
    }
}
$languageStats['totals']['spentTime'] = hrtime(true) - $languageStats['totals']['startTime'];
// --------------------------------------------------------------------
// print slow test runs
if ($config['verbose'] and (($config['slowThreshold'] ?? 0) > 0) and (count($slowTests) > 0)) {
    echo $warnTag . 'The following test case'
        . (count($slowTests) > 1 ? 's' : '')
        . ' took longer than ' . ($config['slowThreshold'] ?? 0) . ' seconds:' . PHP_EOL;
    foreach ($slowTests as $slowTest) {
        $msgLang = str_pad(substr(strval($slowTest['language'] ?? ''), 0, 12), 12);
        $msgTime = str_pad(number_format($slowTest['time'] ?? 0, 1, '.', ''), 6, ' ', STR_PAD_LEFT);
        $testMsg = str_pad(strval($slowTest['test-case']), $testIdxWidth, '0', STR_PAD_LEFT);
        echo '  ' . $msgLang . ':' . $msgTime . 's in test #' . $testMsg . ' of ' . $slowTest['puzzle'] . PHP_EOL;
    }
}
// --------------------------------------------------------------------
// print per language stats
if ($config['stats'] and ($languageStats['totals']['countLanguages'] > 0)) {
    $cleanHeader1 = '';
    $cleanHeader2 = '';
    $cleanHeader3 = '';
    if ($config['clean']) {
        $cleanHeader1 = '-------+';
        $cleanHeader2 = 'Deleted|';
        $cleanHeader3 = ' files |';
    }
    $emptyTag = str_repeat(' ', 7);
    $separator = '-------+------------+------+------+------+------+------+------+------+------+------+-------+'
        . $cleanHeader1;
    echo $separator . PHP_EOL;
    echo '       |            |Direc-|   Puzzles (source files)  |         Test cases        |       |'
        . $cleanHeader2 . PHP_EOL;
    echo '       | Language   |tories| Total|NoEval| Fail | Skip | Total|NoEval| Fail | Skip |  Time |'
        . $cleanHeader3 . PHP_EOL;
    echo $separator . PHP_EOL;
    foreach ($languageStats as $language => $stat) {
        if (($language == 'totals') or ($language == 'unique')) {
            continue;
        }
        if ($config['dry-run'] or $config['clean']) {
            $status = $emptyTag;
        } else {
            $status = $passTag;
        }
        $msgCountDirectories = str_pad(substr(strval($stat['countDirectories']), 0, 6), 6, ' ', STR_PAD_LEFT);
        $msgCountFiles = str_pad(substr(strval($stat['countFiles']), 0, 6), 6, ' ', STR_PAD_LEFT);
        $msgCountRunOnlyFiles = str_pad(substr(strval($stat['countRunOnlyFiles']), 0, 6), 6, ' ', STR_PAD_LEFT);
        $msgCountFailedFiles = str_pad(substr(strval($stat['countFailedFiles']), 0, 6), 6, ' ', STR_PAD_LEFT);
        $msgCountSkippedFiles = str_pad(substr(strval($stat['countSkippedFiles']), 0, 6), 6, ' ', STR_PAD_LEFT);
        $msgCountTests = str_pad(substr(strval($stat['countTests']), 0, 6), 6, ' ', STR_PAD_LEFT);
        $msgCountRunOnlyTests = str_pad(substr(strval($stat['countRunOnlyTests']), 0, 6), 6, ' ', STR_PAD_LEFT);
        $msgCountFailedTests = str_pad(substr(strval($stat['countFailedTests']), 0, 6), 6, ' ', STR_PAD_LEFT);
        $msgCountSkippedTests = str_pad(substr(strval($stat['countSkippedTests']), 0, 6), 6, ' ', STR_PAD_LEFT);
        $msgTime = str_pad(number_format($stat['spentTime'] / 1000000000, 1, '.', ''), 6, ' ', STR_PAD_LEFT);
        $msgCountDeletedFiles = '';
        $msgLanguageSkipped = '';
        if ($stat['countSkippedLanguages'] > 0) {
            $status = $warnTag;
            $msgLanguageSkipped = ' language skipped.';
        }
        if ($config['clean']) {
            $msgCountDeletedFiles = str_pad(strval($stat['countDeletedFiles']), 7, ' ', STR_PAD_LEFT) . '|';
        } else {
            if ($stat['countFiles'] == 0) {
                $msgCountFiles = $ansiYellow . $msgCountFiles . $ansiReset;
                $status = $warnTag;
            }
            if ($stat['countSkippedFiles'] != 0) {
                $msgCountSkippedFiles = $ansiYellow . $msgCountSkippedFiles . $ansiReset;
                $status = $warnTag;
            }
            if ($stat['countTests'] == 0) {
                $msgCountTests = $ansiYellow . $msgCountTests . $ansiReset;
                $status = $warnTag;
            }
            if ($stat['countSkippedTests'] != 0) {
                $msgCountSkippedTests = $ansiYellow . $msgCountSkippedTests . $ansiReset;
                $status = $warnTag;
            }
            if ($stat['countFailedFiles'] != 0) {
                $msgCountFailedFiles = $ansiRed . $msgCountFailedFiles . $ansiReset;
                $status = $failTag;
            }
            if ($stat['countFailedTests'] != 0) {
                $msgCountFailedTests = $ansiRed . $msgCountFailedTests . $ansiReset;
                $status = $failTag;
            }
        }
        echo $status
            . '|' . str_pad(substr(strval($language), 0, 12), 12)
            . '|' . $msgCountDirectories
            . '|' . $msgCountFiles
            . '|' . $msgCountRunOnlyFiles
            . '|' . $msgCountFailedFiles
            . '|' . $msgCountSkippedFiles
            . '|' . $msgCountTests
            . '|' . $msgCountRunOnlyTests
            . '|' . $msgCountFailedTests
            . '|' . $msgCountSkippedTests
            . '|' . $msgTime
            . 's|' . $msgCountDeletedFiles . $msgLanguageSkipped . PHP_EOL;
    }
    // --------------------------------------------------------------------
    // total stats
    echo $separator . PHP_EOL;
    if ($languageStats['totals']['countLanguages'] > 1) {
        $stat = $languageStats['totals'];
        $msgCountLanguages = str_pad(strval($stat['countLanguages']), 2, ' ', STR_PAD_LEFT) . ' language'
            . ($stat['countLanguages'] > 1 ? 's' : ' ');
        $msgCountDirectories = str_pad(substr(strval($stat['countDirectories']), 0, 6), 6, ' ', STR_PAD_LEFT);
        $msgCountFiles = str_pad(substr(strval($stat['countFiles']), 0, 6), 6, ' ', STR_PAD_LEFT);
        $msgCountRunOnlyFiles = str_pad(substr(strval($stat['countRunOnlyFiles']), 0, 6), 6, ' ', STR_PAD_LEFT);
        $msgCountFailedFiles = str_pad(substr(strval($stat['countFailedFiles']), 0, 6), 6, ' ', STR_PAD_LEFT);
        $msgCountSkippedFiles = str_pad(substr(strval($stat['countSkippedFiles']), 0, 6), 6, ' ', STR_PAD_LEFT);
        $msgCountTests = str_pad(substr(strval($stat['countTests']), 0, 6), 6, ' ', STR_PAD_LEFT);
        $msgCountRunOnlyTests = str_pad(substr(strval($stat['countRunOnlyTests']), 0, 6), 6, ' ', STR_PAD_LEFT);
        $msgCountFailedTests = str_pad(substr(strval($stat['countFailedTests']), 0, 6), 6, ' ', STR_PAD_LEFT);
        $msgCountSkippedTests = str_pad(substr(strval($stat['countSkippedTests']), 0, 6), 6, ' ', STR_PAD_LEFT);
        $msgTime = str_pad(number_format($stat['spentTime'] / 1000000000, 1, '.', ''), 6, ' ', STR_PAD_LEFT);
        $msgCountDeletedFiles = '';
        if ($config['clean']) {
            $msgCountDeletedFiles = str_pad(strval($stat['countDeletedFiles']), 7, ' ', STR_PAD_LEFT) . '|';
        }
        $msgLanguageSkipped = '';
        echo 'Total: '
            . '|' . $msgCountLanguages
            . '|' . $msgCountDirectories
            . '|' . $msgCountFiles
            . '|' . $msgCountRunOnlyFiles
            . '|' . $msgCountFailedFiles
            . '|' . $msgCountSkippedFiles
            . '|' . $msgCountTests
            . '|' . $msgCountRunOnlyTests
            . '|' . $msgCountFailedTests
            . '|' . $msgCountSkippedTests
            . '|' . $msgTime
            . 's|' . $msgCountDeletedFiles . $msgLanguageSkipped . PHP_EOL;
        if (!$config['clean']) {
            // --------------------------------------------------------------------
            // unique puzzle stats
            $stat = $languageStats['unique'];
            $msgCountDirectories = str_pad(substr(strval($stat['countDirectories']), 0, 6), 6, ' ', STR_PAD_LEFT);
            $msgCountFiles = str_pad(substr(strval($stat['countFiles']), 0, 6), 6, ' ', STR_PAD_LEFT);
            $msgCountRunOnlyFiles = str_pad(substr(strval($stat['countRunOnlyFiles']), 0, 6), 6, ' ', STR_PAD_LEFT);
            $msgCountFailedFiles = str_pad(substr(strval($stat['countFailedFiles']), 0, 6), 6, ' ', STR_PAD_LEFT);
            $msgCountSkippedFiles = str_pad(substr(strval($stat['countSkippedFiles']), 0, 6), 6, ' ', STR_PAD_LEFT);
            $msgCountTests = str_pad(substr(strval($stat['countTests']), 0, 6), 6, ' ', STR_PAD_LEFT);
            $msgCountRunOnlyTests = str_pad(substr(strval($stat['countRunOnlyTests']), 0, 6), 6, ' ', STR_PAD_LEFT);
            $msgCountFailedTests = str_pad(substr(strval($stat['countFailedTests']), 0, 6), 6, ' ', STR_PAD_LEFT);
            $msgCountSkippedTests = str_pad(substr(strval($stat['countSkippedTests']), 0, 6), 6, ' ', STR_PAD_LEFT);
            $msgTime = str_repeat(' ', 7);
            $msgCountDeletedFiles = '';
            $msgLanguageSkipped = '';
            echo 'Unique:'
                . '|' . str_repeat(' ', 12)
                . '|' . $msgCountDirectories
                . '|' . $msgCountFiles
                . '|' . $msgCountRunOnlyFiles
                . '|' . $msgCountFailedFiles
                . '|' . $msgCountSkippedFiles
                . '|' . $msgCountTests
                . '|' . $msgCountRunOnlyTests
                . '|' . $msgCountFailedTests
                . '|' . $msgCountSkippedTests
                . '|' . $msgTime
                . '|' . $msgCountDeletedFiles . $msgLanguageSkipped . PHP_EOL;
        }
        echo $separator . PHP_EOL;
    }
}
// --------------------------------------------------------------------
// Special case for C# and VB.NET: delete Directory.build.props file
if (file_exists($csDirectoryBuildPropsFilename)) {
    unlink($csDirectoryBuildPropsFilename);
}
// --------------------------------------------------------------------
// print global results
$statusWidth = 78;
echo PHP_EOL;
$wasLF = true;
if ($languageStats['totals']['countSkippedLanguages'] > 0) {
    echo $warnTag . 'Skipped ' . $languageStats['totals']['countSkippedLanguages'] . ' language'
        . ($languageStats['totals']['countSkippedLanguages'] > 1 ? 's' : '')
        . ' due to missing compiler/interpreter or configuration error.' . PHP_EOL;
    $wasLF = false;
}
if ($config['clean']) {
    if ($languageStats['totals']['countDeletedFiles'] > 0) {
        echo $infoTag . 'Deleted ' . $languageStats['totals']['countDeletedFiles'] . ' temporary and test output file'
            . ($languageStats['totals']['countDeletedFiles'] > 1 ? 's' : '') . '.' . PHP_EOL;
    }
    if ($totalUnsuccessfulDeleteFiles > 0) {
        echo $warnTag . 'Failed to delete ' . $totalUnsuccessfulDeleteFiles . ' file'
            . ($totalUnsuccessfulDeleteFiles > 1 ? 's' : '') . '.' . PHP_EOL . PHP_EOL;
        exit(1);
    }
    if ($languageStats['totals']['countDeletedFiles'] == 0) {
        echo $infoTag . 'There was nothing to clean.' . PHP_EOL;
    }
    echo PHP_EOL;
    exit(0);
}
if ($config['lang-versions']) {
    $countLangs = $languageStats['totals']['countLanguages'] - $languageStats['totals']['countSkippedLanguages'];
    echo $infoTag . 'Total: ' . $countLangs . ' programming language'
       . ($countLangs > 1 ? 's' : '')
       . ' found.' . PHP_EOL . PHP_EOL;
    exit(0);
}
if ($languageStats['totals']['countSkippedFiles'] > 0) {
    $msg = '';
    if ($languageStats['totals']['countSkippedFiles'] != $languageStats['unique']['countSkippedFiles']) {
        $msg = ' for ' . $languageStats['unique']['countSkippedFiles'] . ' unique puzzle'
            . ($languageStats['unique']['countSkippedFiles'] > 1 ? 's' : '');
    }
    echo $warnTag . 'Skipped ' . $languageStats['totals']['countSkippedFiles'] . ' puzzle solution'
        . ($languageStats['totals']['countSkippedFiles'] > 1 ? 's' : '')
        . $msg . ' due to missing source code file.' . PHP_EOL;
    $wasLF = false;
}
if ($languageStats['totals']['countSkippedTests'] > 0) {
    echo $warnTag . 'Skipped ' . $languageStats['totals']['countSkippedTests'] . ' test'
        . ($languageStats['totals']['countSkippedTests'] > 1 ? 's' : '')
        . ' due to missing test case file.' . PHP_EOL;
    $wasLF = false;
}
if ($languageStats['totals']['countTests'] == 0) {
    if (!$wasLF) {
        echo PHP_EOL;
    }
    echo $ansiYellow . str_pad(' [WARN] There was nothing to test.', $statusWidth) . $ansiReset . PHP_EOL . PHP_EOL;
    exit(1);
}
$msg = '';
if ($languageStats['unique']['countFiles'] != $languageStats['totals']['countFiles']) {
    $msg = ' for ' . $languageStats['unique']['countFiles'] . ' unique puzzle'
        . ($languageStats['unique']['countFiles'] > 1 ? 's' : '');
}
echo $infoTag . 'Total: ' . $languageStats['totals']['countPassedTests'] . ' / '
    . $languageStats['totals']['countTests'] . ' test'
    . ($languageStats['totals']['countTests'] > 1 ? 's' : '') . ' passed while testing '
    . $languageStats['totals']['countFiles'] . ' solution'
    . ($languageStats['totals']['countFiles'] > 1 ? 's' : '')
    . $msg . ' in '
    . $languageStats['totals']['countLanguages'] . ' programming language'
    . ($languageStats['totals']['countLanguages'] > 1 ? 's' : '') . '.' . PHP_EOL;
if ((($config['slowThreshold'] ?? 0) > 0) and (count($slowTests) > 0)) {
    echo $infoTag . 'There were ' . count($slowTests) . ' test'
        . (count($slowTests) > 1 ? 's' : '')
        . ' taking longer than ' . ($config['slowThreshold'] ?? 0) . ' seconds.' . PHP_EOL;
}
$timeStr = number_format($languageStats['totals']['spentTime'] / 1000000000, 1, '.', '');
echo $infoTag . "Time spent: $timeStr seconds." . PHP_EOL . PHP_EOL;
if (!$config['dry-run']) {
    if ($languageStats['totals']['countPassedTests'] != $languageStats['totals']['countTests']) {
        echo $ansiRed . str_pad(' [FAIL] Some tests failed.', $statusWidth) . $ansiReset . PHP_EOL . PHP_EOL;
        exit(1);
    }
    echo $ansiGreen . str_pad(' [OK] All tests passed.', $statusWidth) . $ansiReset . PHP_EOL . PHP_EOL;
}
exit(0);
// --------------------------------------------------------------------
