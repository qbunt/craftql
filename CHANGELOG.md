## 1.0.0-beta.8 - 2017-08-24

### Fixed

- Date fields now use the Timestamp scalar type

## 1.0.0-beta.7 - 2017-08-23

### Added

- A `@date` directive is now used to format `Timestamp` scalars. Use it with the same options Carbon uses, `@date(as:"Y-m-d")`

### Changed

- `dateCreated`, `dateUpdated`, and `expiryDate` now use a new `Timestamp` scalar

## 1.0.0 - 2017-07-11

### New

- Initial release