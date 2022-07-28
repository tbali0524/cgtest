# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- 100+ more CG puzzle test cases added.

### Changed

- Nothing yet.

### Fixed

- If puzzle name is given via command-line with path given, it will override the per-language path settings.

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
