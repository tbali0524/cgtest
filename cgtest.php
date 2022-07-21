<?php

/**
 * CGTest v1.0.0 - multi-language offline batch test runner for CodinGame solo I/O puzzles.
 *
 * (c) 2022, by TBali
 */

declare(strict_types=1);

namespace TBali\CGTest;

$startTime = microtime(true);
$title = 'CGTest v1.0.0 - multi-language offline batch test runner for CodinGame solo I/O puzzles, (c) 2022, by TBali';
echo $title . PHP_EOL . PHP_EOL;
$defaultConfigFileName = '.cgtest.php';
$defaultConfig = [
    'dry-run' => false,
    'ansi' => true,
    'verbose' => false,
    'lang-versions' => false,
    'clean' => false,
    'inputPath' => '.tests/input/',
    'inputPattern' => '%p_i%t.txt',
    'expectedPath' => '.tests/expected/',
    'expectedPattern' => '%p_e%t.txt',
    'outputPath' => '.tests/output/',
    'outputPattern' => '%p_o%t_%l.txt',
    'errorLog' => '.tests/output/_error_log.txt',
    'languages' => ['php'],
    'puzzles' => [],
    // todo: buildCommand, runCommand, cleanPatterns
    'c#' => [
        'sourcePath' => 'c#/',
        'sourceExtension' => '.cs',
        'versionCommand' => 'dotnet --version',
        'buildCommand' => '',
        'runCommand' => 'dotnet run --nologo --verbosity quiet --project dotnet.csproj %s',
        'cleanPatterns' => [],
    ],
    'dart' => [
        'sourcePath' => 'dart/',
        'sourceExtension' => '.dart',
        'versionCommand' => 'dart --version',
        'buildCommand' => '',
        'runCommand' => 'dart %s',
        'cleanPatterns' => [],
    ],
    'go' => [
        'sourcePath' => 'go/',
        'sourceExtension' => '.go',
        'versionCommand' => 'go version',
        'buildCommand' => '',
        'runCommand' => 'go run %s',
        'cleanPatterns' => [],
    ],
    'java' => [
        'sourcePath' => 'java/',
        'sourceExtension' => '.java',
        'versionCommand' => 'java --version',
        'buildCommand' => '',
        'runCommand' => 'java %s',
        'cleanPatterns' => [],
    ],
    // todo: readline() is missing
    'javascript' => [
        'sourcePath' => 'javascript/',
        'sourceExtension' => '.js',
        'versionCommand' => 'node --version',
        'buildCommand' => '',
        'runCommand' => 'node %s',
        'cleanPatterns' => [],
    ],
    'lua' => [
        'sourcePath' => 'lua/',
        'sourceExtension' => '.lua',
        'versionCommand' => 'lua --version',
        'buildCommand' => '',
        'runCommand' => 'lua %s',
        'cleanPatterns' => [],
    ],
    'perl' => [
        'sourcePath' => 'perl/',
        'sourceExtension' => '.pl',
        'versionCommand' => 'perl --version',
        'buildCommand' => '',
        'runCommand' => 'perl %s',
        'cleanPatterns' => [],
    ],
    'php' => [
        'sourcePath' => 'php/',
        'sourceExtension' => '.php',
        'versionCommand' => 'php --version',
        'buildCommand' => '',
        'runCommand' => 'php %s',
        'cleanPatterns' => [],
    ],
    'python' => [
        'sourcePath' => 'python/',
        'sourceExtension' => '.py',
        'versionCommand' => 'python --version',
        'buildCommand' => '',
        'runCommand' => 'python %s',
        'cleanPatterns' => [],
    ],
    'ruby' => [
        'sourcePath' => 'ruby/',
        'sourceExtension' => '.rb',
        'versionCommand' => 'ruby --version',
        'buildCommand' => '',
        'runCommand' => 'ruby %s',
        'cleanPatterns' => [],
    ],
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
    ],
    // todo: readline() is missing
    'typescript' => [
        'sourcePath' => 'typescript/',
        'sourceExtension' => '.ts',
        'versionCommand' => 'node --version',
        'buildCommand' => '',
        'runCommand' => 'node %s',
        'cleanPatterns' => [],
    ],
];
foreach ($defaultConfig['languages'] as $language) {
    $defaultConfig[$language]['excludePuzzles'] = [];
    $defaultConfig[$language]['includePuzzles'] = [];
}
$reservedConfigKeys = [
    'dry-run', 'ansi', 'verbose', 'lang-versions', 'clean', 'puzzles',
    'inputPath', 'inputPattern', 'expectedPath', 'expectedPattern', 'outputPath', 'outputPattern',
    'errorLog', 'languages', 'puzzles'
];
$infoTag = '[INFO] ';
$errorTag = '[ERROR] ';
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
        echo '   --lang-versions    Show versions for all configured programming languages' . PHP_EOL;
        echo '   --clean            Delete temporary and output files of previous test run'
            . PHP_EOL;
        echo '   --config=FILENAME  Use configfile [default: ' . $defaultConfigFileName . ']' . PHP_EOL;
        echo '   --lang=LANGUAGES   Run tests in these languages (comma separated list) [default: '
            . implode(',', $defaultConfig['languages'] ?? 'none') . ']' . PHP_EOL;
        echo PHP_EOL;
        echo 'Puzzles:              Space separated list of source filenames (without extension)' . PHP_EOL;
        echo '                        if given, it overrides the list in the config file' . PHP_EOL;
        echo '                        path can be given, but no wildcards allowed' . PHP_EOL;
        echo PHP_EOL;
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
    if (in_array($arg, ['--dry-run', '--ansi', '--verbose', '--lang-versions', '--clean'], true)) {
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
        $path = './';
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
$config = array_merge($defaultConfig, $configFromFile, $argumentConfig);
$ansiGreen = ($config['ansi'] ?? false) ? "\e[1;37;42m" : '';
$ansiRed = ($config['ansi'] ?? false) ? "\e[1;37;41m" : '';
$ansiYellow = ($config['ansi'] ?? false) ? "\e[1;37;43m" : '';
$ansiReset = ($config['ansi'] ?? false) ? "\e[0m" : '';
$errorTag = $ansiRed . '[ERROR]' . $ansiReset . ' ';
$warnTag = $ansiYellow . '[WARN]' . $ansiReset . ' ';
if (($config['lang-versions'] ?? false) and ($config['clean'] ?? false)) {
    echo $errorTag . 'Invalid arguments: cannot use both --clean and --lang-versions' . PHP_EOL . PHP_EOL;
    exit(2);
}
if (($config['lang-versions'] ?? false) and ($config['dry-run'] ?? false)) {
    echo $errorTag . 'Invalid arguments: cannot use both --dry-run and --lang-versions' . PHP_EOL . PHP_EOL;
    exit(2);
}
if (($config['clean'] ?? false) and ($config['dry-run'] ?? false)) {
    echo $errorTag . 'Invalid arguments: cannot use both --clean and --dry-run' . PHP_EOL . PHP_EOL;
    exit(2);
}
if (($config['inputPattern'] ?? '') == '') {
    echo $errorTag . 'Invalid configuration: missing input file pattern' . PHP_EOL;
    exit(2);
}
if (($config['expectedPattern'] ?? '') == '') {
    echo $errorTag . 'Invalid configuration: missing expected test output file pattern' . PHP_EOL;
    exit(2);
}
if (($config['outputPattern'] ?? '') == '') {
    echo $errorTag . 'Invalid configuration: missing test output file pattern' . PHP_EOL;
    exit(2);
}
$totalLanguages = 0;
$totalDirectories = 0;
$totalFiles = 0;
$totalTests = 0;
$totalPassed = 0;
if (file_exists($config['errorLog'] ?? '')) {
    $unlinkResult = unlink($config['errorLog']);
    if ($config['clean'] ?? false) {
        if (!$unlinkResult) {
            echo $warnTag . 'Could not delete file: ' . $config['errorLog'] . PHP_EOL;
        } elseif ($config['verbose'] ?? false) {
            echo $infoTag . 'Deleted file: ' . $config['errorLog'] . PHP_EOL;
        }
    }
}
if (
    !($config['clean'] ?? false)
    and !($config['lang-versions'] ?? false)
    and !($config['dry-run'] ?? false)
) {
    $logFile = fopen($config['errorLog'], 'ab');
    if ($logFile === false) {
        echo $errorTag . 'Cannot open logfile to write: ' . $config['errorLog'] . PHP_EOL . PHP_EOL;
        exit(2);
    }
    fwrite($logFile, $title . PHP_EOL . PHP_EOL);
    fclose($logFile);
    echo $infoTag . 'Error logs during the tests runs redirected to file: ' . $config['errorLog'] . PHP_EOL;
}
foreach ($config['languages'] as $language) {
    if (!isset($config[$language])) {
        echo $warnTag . 'No configuration available for language: ' . $language . PHP_EOL;
        continue;
    }
    if (($config[$language]['sourceExtension'] ?? '') == '') {
        echo $warnTag . 'Invalid configuration: missing source file extension for language: ' . $language . PHP_EOL;
        continue;
    }
    if (($config[$language]['runCommand'] ?? '') == '') {
        echo $warnTag . 'Invalid configuration: missing run command for language: ' . $language . PHP_EOL;
        continue;
    }
    if (($config['lang-versions'] ?? false) and (($config[$language]['versionCommand'] ?? '') == '')) {
        echo $warnTag . 'Invalid configuration: missing version command for language: ' . $language . PHP_EOL;
        continue;
    }
    if (!($config['clean'] ?? false) and (($config[$language]['versionCommand'] ?? '') != '')) {
        if ($config['lang-versions'] ?? false) {
            echo $infoTag . 'Version info for language: ' . $language . PHP_EOL;
            $versionCommand = $config[$language]['versionCommand'];
        } elseif ($config['dry-run'] ?? false) {
            $versionCommand = $config[$language]['versionCommand'];
        } else {
            $versionCommand = $config[$language]['versionCommand'] . ' >> ' . $config['errorLog'] . ' 2>>&1';
        }
        $execOutput = [];
        $execResultCode = 0;
        $execResult = exec($versionCommand, $execOutput, $execResultCode);
        // @phpstan-ignore-next-line
        if ($execResult === false) {
            echo $warnTag . 'Language is unavaliable: ' . $config[$language]['versionCommand'] . PHP_EOL;
            continue;
        }
        if ($config['lang-versions'] ?? false) {
            for ($i = 0; $i < min(3, count($execOutput)); ++$i) {
                echo $execOutput[$i] . PHP_EOL;
            }
            ++$totalLanguages;
            continue;
        }
    }
    ++$totalLanguages;
    $puzzles = $config['puzzles'] ?? [];
    if (($argumentConfig['puzzles'] ?? []) == []) {
        foreach (($config[$language]['includePuzzles'] ?? []) as $sourcePath => $filesArray) {
            if (!isset($puzzles[$sourcePath])) {
                $puzzles[$sourcePath] = [];
            }
            foreach ($filesArray as $puzzleNameOriginal) {
                $puzzles[$sourcePath][] = strtolower($puzzleNameOriginal);
            }
        }
    }
    foreach ($puzzles as $sourcePath => $filesArray) {
        ++$totalDirectories;
        foreach ($filesArray as $puzzleNameOriginal) {
            $puzzleName = strtolower($puzzleNameOriginal);
            if (
                (($argumentConfig['puzzles'] ?? []) == [])
                and (in_array($puzzleName, $config[$language]['excludePuzzles'] ?? [], true))
            ) {
                continue;
            }
            ++$totalFiles;
            $sourceFileName = $puzzleName . ($config[$language]['sourceExtension'] ?? '');
            if (($config[$language]['sourcePath'] ?? '') == '') {
                $sourceFullFileName = $sourcePath . $sourceFileName;
            } else {
                $sourceFullFileName = $config[$language]['sourcePath'] . $sourceFileName;
            }
            if (!file_exists($sourceFullFileName)) {
                echo $warnTag . 'Cannot find sourcefile: ' . $sourceFullFileName . PHP_EOL;
                continue;
            }
            if ($config['clean'] ?? false) {
                foreach (($config[$language]['cleanPatterns'] ?? []) as $patternToClean) {
                    $tempFileName = str_replace(
                        ['%l', '%p', '%o'],
                        [$language, $puzzleName, $config['outputPath'] ?? ''],
                        $patternToClean
                    );
                    if (file_exists($tempFileName)) {
                        $unlinkResult = unlink($tempFileName);
                        if (!$unlinkResult) {
                            echo $warnTag . 'Could not delete file: ' . $tempFileName . PHP_EOL;
                        } elseif ($config['verbose'] ?? false) {
                            echo $infoTag . 'Deleted file: ' . $tempFileName . PHP_EOL;
                        }
                    }
                }
            }
            $buildCommand = '';
            if (
                !($config['clean'] ?? false)
                and !($config['dry-run'] ?? false)
                and (($config[$language]['buildCommand'] ?? '') != '')
            ) {
                $baseBuildCommand = str_replace(
                    ['%l', '%p', '%o', '%s'],
                    [$language, $puzzleName, $config['outputPath'] ?? '', $sourceFullFileName],
                    $config[$language]['buildCommand']
                );
                if (PHP_OS_FAMILY == 'Windows') {
                    $baseBuildCommand = str_replace('/', '\\', $baseBuildCommand);
                }
                $buildCommand = $baseBuildCommand . ' >> ' . $config['errorLog'] . ' 2>>&1';
                $execOutput = [];
                $execResultCode = 0;
                $execResult = exec($buildCommand, $execOutput, $execResultCode);
                // @phpstan-ignore-next-line
                if ($execResult === false) {
                    echo $warnTag . 'Build unsuccessful for source: ' . $sourceFullFileName . PHP_EOL;
                    continue;
                }
            }
            $baseRunCommand = str_replace(
                ['%l', '%p', '%o', '%s'],
                [$language, $puzzleName, $config['outputPath'] ?? '', $sourceFullFileName],
                $config[$language]['runCommand']
            );
            if (PHP_OS_FAMILY == 'Windows') {
                $baseRunCommand = str_replace('/', '\\', $baseRunCommand);
            }
            $testsFailed = [];
            $countTestsForFile = 0;
            $idxTest = 0;
            while (true) {
                ++$idxTest;
                $stringIdxTest = str_pad(strval($idxTest), 2, '0', STR_PAD_LEFT);
                $inputFullFileName = str_replace(
                    ['%l', '%p', '%t'],
                    [$language, $puzzleName, $stringIdxTest],
                    ($config['inputPath'] ?? '') . $config['inputPattern']
                );
                if (!file_exists($inputFullFileName)) {
                    break;
                }
                $expectedFullFileName = str_replace(
                    ['%l', '%p', '%t'],
                    [$language, $puzzleName, $stringIdxTest],
                    ($config['expectedPath'] ?? '') . $config['expectedPattern']
                );
                if (!file_exists($expectedFullFileName)) {
                    echo $warnTag . 'Cannot read expected test output file: ' . $expectedFullFileName . PHP_EOL;
                    continue;
                }
                $outputFullFileName = str_replace(
                    ['%l', '%p', '%t'],
                    [$language, $puzzleName, $stringIdxTest],
                    ($config['outputPath'] ?? '') . $config['outputPattern']
                );
                if ($expectedFullFileName === $outputFullFileName) {
                    echo $errorTag . 'Invalid configuration: expected and real test output filenames are the same: '
                        . $expectedFullFileName . PHP_EOL . PHP_EOL;
                    exit(2);
                }
                if ($config['clean'] ?? false) {
                    if (file_exists($outputFullFileName)) {
                        $unlinkResult = unlink($outputFullFileName);
                        if (!$unlinkResult) {
                            echo $warnTag . 'Could not delete file: ' . $outputFullFileName . PHP_EOL;
                        } elseif ($config['verbose'] ?? false) {
                            echo $infoTag . 'Deleted file: ' . $outputFullFileName . PHP_EOL;
                        }
                    }
                    continue;
                }
                if (file_exists($outputFullFileName)) {
                    unlink($outputFullFileName);
                }
                $expectedFileContents = file_get_contents($expectedFullFileName);
                if ($expectedFileContents === false) {
                    echo $warnTag . 'Cannot read expected test output file: ' . $expectedFullFileName . PHP_EOL;
                    continue;
                }
                ++$countTestsForFile;
                ++$totalTests;
                if (($config['dry-run'] ?? false)) {
                    continue;
                }
                $expectedFileContents = str_replace("\r\n", "\n", $expectedFileContents);
                $i = strlen($expectedFileContents) - 1;
                while (($i > 0) and ($expectedFileContents[$i] == "\n")) {
                    --$i;
                }
                $expectedFileContents = substr($expectedFileContents, 0, $i + 1);
                $logFile = fopen($config['errorLog'], 'ab');
                if ($logFile !== false) {
                    $s = "| $language | $stringIdxTest | $sourceFullFileName |";
                    fwrite($logFile, str_repeat('=', strlen($s)) . PHP_EOL);
                    fwrite($logFile, $s . PHP_EOL);
                    fwrite($logFile, str_repeat('-', strlen($s)) . PHP_EOL);
                    fwrite($logFile, PHP_EOL);
                    fclose($logFile);
                }
                $runCommand = $baseRunCommand . " < $inputFullFileName > $outputFullFileName 2>> "
                    . $config['errorLog'];
                $execOutput = [];
                $execResultCode = 0;
                $execResult = exec($runCommand, $execOutput, $execResultCode);
                // @phpstan-ignore-next-line
                if ($execResult === false) {
                    echo $errorTag . 'Execution unsuccessful for source: ' . $sourceFullFileName . PHP_EOL;
                    continue;
                }
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
                if ($expectedFileContents !== $outputFileContents) {
                    $testsFailed[] = $stringIdxTest;
                    continue;
                }
                ++$totalPassed;
            }
            if ($config['clean'] ?? false) {
                continue;
            }
            if ($countTestsForFile == 0) {
                echo $warnTag . 'No test input found for source: ' . $sourceFullFileName . PHP_EOL;
            }
            if ($config['dry-run'] ?? false) {
                echo $infoTag . str_pad(strval($countTestsForFile), 2, ' ', STR_PAD_LEFT) . ' test'
                    . ($countTestsForFile > 1 ? 's' : ' ') . ' for : ' . $sourceFullFileName . PHP_EOL;
                continue;
            }
            if (count($testsFailed) != 0) {
                echo $ansiRed . '[FAIL]' . $ansiReset . ' ' . $sourceFullFileName . PHP_EOL;
                echo '  at test' . (count($testsFailed) > 1 ? 's' : ' ')
                    . ' : #' . implode(' #', $testsFailed) . ' from a total of ' . $countTestsForFile . ' test'
                    . ($countTestsForFile > 1 ? 's' : '') . '.' . PHP_EOL;
            } elseif ($config['verbose'] ?? false) {
                echo $ansiGreen . '[PASS]' . $ansiReset . ' '
                    . str_pad(strval($countTestsForFile), 2, ' ', STR_PAD_LEFT) . ' test'
                    . ($countTestsForFile > 1 ? 's' : ' ') . ' OK : ' . $sourceFullFileName . PHP_EOL;
            }
        }
    }
}
if ($config['clean'] ?? false) {
    echo $infoTag . 'Temporary and test output files deleted.' . PHP_EOL . PHP_EOL;
}
if ($config['lang-versions'] ?? false) {
    echo $infoTag . "Total: $totalLanguages programming language"
       . ($totalLanguages > 1 ? 's' : '') . ' found.' . PHP_EOL . PHP_EOL;
}
if (!($config['clean'] ?? false) and !($config['lang-versions'] ?? false)) {
    if ($totalTests == 0) {
        echo $warnTag . 'Nothing to test.' . PHP_EOL . PHP_EOL;
        exit(1);
    }
    echo $infoTag . "Total: $totalPassed / $totalTests tests passed while testing $totalFiles source file"
        . ($totalFiles > 1 ? 's' : '') . " in $totalDirectories director"
        . ($totalDirectories > 1 ? 'ies' : 'y') . " in $totalLanguages programming language"
        . ($totalLanguages > 1 ? 's' : '') . '.' . PHP_EOL;
    $timeStr = number_format(microtime(true) - $startTime, 1, '.', '');
    echo $infoTag . "Time: $timeStr seconds" . PHP_EOL . PHP_EOL;
    if (!($config['dry-run'] ?? false)) {
        if ($totalPassed != $totalTests) {
            echo $ansiRed . str_pad(' [FAIL] Some tests failed.', 78) . $ansiReset . PHP_EOL . PHP_EOL;
            exit(1);
        }
        echo $ansiGreen . str_pad(' [OK] All tests passed.', 78) . $ansiReset . PHP_EOL . PHP_EOL;
        exit(0);
    }
}
exit(0);
