# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- `javascript` and `typescript` support added.

### Changed

- Nothing yet.

### Fixed

- Nothing yet.

## [v1.3.0](https://github.com/tbali0524/cgtest/releases/v1.3.0) - 2022-08-05

### Added

- `c#` and `vb.net` support added.
- `'--run-only'` command-line argument added.
- `'run-only'` global option added to config file format.
- `'runOnlyPuzzles'` language-specific option added to config file format.
- `'buildPath'` global option added to config file format.
- partial test cases (input files with _turn #0_ data) added for several optimization and multi-turn solo puzzles.

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
