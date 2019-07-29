# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.3.0]

### Changed

- The `SynchronousEventPublisher` will now invoke listeners when the published event is a subtype of the event they're
interested in.

## [0.2.0]

### Added

- Coding standard.
- Change log.
- Event hydration system.

### Fixed

- Event stream is partial check.

### Changed

- Event storage exceptions.
- Bumped PHP min version requirement to `7.2`.
- Updated dependencies.
- Event type to a static method.

