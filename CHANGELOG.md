# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Nothing yet.

### Changed

- Nothing yet.

### Fixed

- Nothing yet.

## [v1.15.0](https://github.com/tbali0524/cgtest/releases/v1.15.0) - 2024-03-09

### Added

- Clojure support added with `babashka`.
- More test cases added.

### Fixed

- Using `--create` and `--run-only` at the same time creates only input files but not expected output files.

## [v1.14.0](https://github.com/tbali0524/cgtest/releases/v1.14.0) - 2024-03-05

### Added

- `verbose` configuration option is now the default.
- `--quiet` command-line argument added.

### Fixed

- Default `c` build command fixed.
- Fixed double warning when log file cannot be created.
- Command line option `--no-ansi` and `--ansi` is taken into account with `--help`, `--version`, or if there are errors with the other command-line arguments.

## [v1.13.0](https://github.com/tbali0524/cgtest/releases/v1.13.0) - 2024-03-04

### Added

- `--alt` command-line argument added.
- `alt` global configuration option added.
- `altVersionCommand`, `altBuildCommand`, `altRunCommand`, `altNote` configuration options added to the per language section.
- More test cases added.

### Changed

- More color in warnings and error messages.

## [v1.12.0](https://github.com/tbali0524/cgtest/releases/v1.12.0) - 2024-02-24

### Added

- Source line counting added.
- More test cases added.

### Changed

- Show the language versions used during normal test runs with `--verbose`.
- Github Actions updated.
- Default `c++` build command on Windows include `-static-libgcc -static-libstdc++`
- Color tweaks.

### Fixed

- Nothing yet.

## [v1.11.0](https://github.com/tbali0524/cgtest/releases/v1.11.0) - 2024-01-01

### Added

- More test cases added.

### Changed

- More colorful output.
- Default `rust` compiler settings changed to include `-C opt-level=3`.

### Fixed

- When using option `--dry-run`, it no longer deletes output files from a previous test run.

## [v1.10.0](https://github.com/tbali0524/cgtest/releases/v1.10.0) - 2023-10-03

### Added

- All public test cases for easy puzzles added.
- Statement texts for most easy and medium puzzles added.
- Test cases for Winter & Summer Challenge 2023 added.

### Changed

- Tweak in sample config files: bash enabled if OS is not Windows
- Info updated about CG supported language versions in the `--lang-versions` output.
- Default `rust` compiler settings changed to include `-O --edition 2021`.

## [v1.9.0](https://github.com/tbali0524/cgtest/releases/v1.9.0) - 2023-07-17

### Added

- Github Action workflow added.
- More CG puzzle test cases added. The repo now contains all public test cases for all medium, hard and expert puzzles and at least 1 test case for all easy puzzles. In total there are __4300+__ test cases for __770+__ puzzles.
- Statement texts for hard and expert puzzles added.

## [v1.8.0](https://github.com/tbali0524/cgtest/releases/v1.8.0) - 2023-04-07

### Added

- More CG puzzle test cases added.
- Test cases added for the new codegolf puzzles.

### Changed

- Codegolf puzzle test cases renamed.
- Puzzle test cases renamed where CG changed the original puzzle name, Bender->Blunder.

### Fixed

- In the default configuration `c` compile command line the -lm argument is moved to the end.

## [v1.7.0](https://github.com/tbali0524/cgtest/releases/v1.7.0) - 2023-02-25

### Added

- `--create=COUNT` command-line argument added.
- More CG puzzle test cases added.

## [v1.6.0](https://github.com/tbali0524/cgtest/releases/v1.6.0) - 2022-12-03

### Added

- A global `'slowThreshold'` option added to config file format.
- In `--verbose` mode, the tests taking longer then `'slowThreshold'` are also listed.
- More CG puzzle test cases added.

## [v1.5.0](https://github.com/tbali0524/cgtest/releases/v1.5.0) - 2022-10-22

### Added

- `--test-case=id` command-line argument added.
- A global `'test-case'` option added to config file format.
- More CG puzzle test cases added.

## [v1.4.0](https://github.com/tbali0524/cgtest/releases/v1.4.0) - 2022-08-10

### Added

- `javascript` and `typescript` support added.
- A global `'runOnlyPuzzles'` option added to config file format.
- Partial test cases (input files with _turn #0_ data) for all multi-turn or multi-solution CG puzzles added.

### Changed

- Output for `--stats` option extended with 'run only' puzzles.

### Fixed

- Build errors were not reported as failed tests.

## [v1.3.0](https://github.com/tbali0524/cgtest/releases/v1.3.0) - 2022-08-05

### Added

- `c#` and `vb.net` support added.
- `--run-only` command-line argument added.
- `'run-only'` global option added to config file format.
- A language-specific `'runOnlyPuzzles'` option added to config file format.
- `'buildPath'` global option added to config file format.
- Partial test cases (input files with _turn #0_ data) added for several optimization and multi-turn solo puzzles.

### Changed

- Revamped output for `--stats` option.

## [v1.2.0](https://github.com/tbali0524/cgtest/releases/v1.2.0) - 2022-07-29

### Added

- Test cases for all CG solo I/O puzzles added. Total is now over __750__ test cases for around __600__ puzzles.
- Stats for unique puzzles.

### Fixed

- If the puzzle name is given via command-line and it includes a path, it will override the per-language path settings.

## [v1.1.0](https://github.com/tbali0524/cgtest/releases/v1.1.0) - 2022-07-25

### Added

- `--stats` command-line option added.
- __150+__ CG puzzle test cases added.
- Fortran default config and sample code added.

### Changed

- Report output changed for failed tests.
- All puzzles renamed in `.cgtest.full.php` config file (to better match the slug in the CG URLs).

### Fixed

- Shebang added to the `bash` sample solution.
- Added -lm to default gcc build command.

## [v1.0.0](https://github.com/tbali0524/cgtest/releases/v1.0.0) - 2022-07-23

Initial release.
