# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Nothing yet

### Changed

- Nothing yet

### Fixed

- Nothing yet

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

- Test cases for all CG solo I/O puzzles added. Total is now over 750 test cases for around 600 puzzles.
- Stats for unique puzzles.

### Fixed

- If the puzzle name is given via command-line and it includes a path, it will override the per-language path settings.

## [v1.1.0](https://github.com/tbali0524/cgtest/releases/v1.1.0) - 2022-07-25

### Added

- `--stats` command-line option added.
- 150+ CG puzzle test cases added.
- Fortran default config and sample code added.

### Changed

- Report output changed for failed tests.
- All puzzles renamed in `.cgtest.full.php` config file (to better match the slug in the CG URLs).

### Fixed

- Shebang added to the bash sample solution.
- Added -lm to default gcc build command.

## [v1.0.0](https://github.com/tbali0524/cgtest/releases/v1.0.0) - 2022-07-23

Initial release.
