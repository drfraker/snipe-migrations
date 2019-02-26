# Changelog
All notable changes to this project will be documented in this file.

## [Unreleased]

## [1.0.2] - 2019-02-23
### Changed
- merged PR to recursively scan package migration folders and app migration folders.
- Added a trait that can be added to TestsCase.php to remove the need to add a block of code to TestCase.php
- Refactored Snipe.php to only scan for migration changes one time to improve performance and clean up file.
- Added feature to use Snipe migrations for DatabaseTransactions ***AND*** RefreshDatabase Traits.

## [1.0.2] - 2019-02-23
### Changed
- merged PR to recursively scan migrations folder for changes.

## [1.0.1] - 2019-02-21
### Changed
- merged PR to remove redundant code in setUpTraits() method.

## [1.0.0] - 2019-02-19
### Added
- initial release
