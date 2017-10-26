# Change Log

## [1.0.0-beta1](https://github.com/silverleague/silverstripe-logviewer/tree/1.0.0-beta1) (2017-10-26)
[Full Changelog](https://github.com/silverleague/silverstripe-logviewer/compare/1.0.0-alpha1...1.0.0-beta1)

**Implemented enhancements:**

- Format JSON part of the error message [\#13](https://github.com/silverleague/silverstripe-logviewer/issues/13)
- Add a ModelAdmin icon other than the default [\#11](https://github.com/silverleague/silverstripe-logviewer/issues/11)

**Fixed bugs:**

- Circular dependency error from a dependency of silverstripe/config [\#29](https://github.com/silverleague/silverstripe-logviewer/issues/29)

**Merged pull requests:**

- Removed unnecessary paragraph tags causing other buttons to become askew [\#36](https://github.com/silverleague/silverstripe-logviewer/pull/36) ([zanderwar](https://github.com/zanderwar))
- Changed type to vendor module [\#34](https://github.com/silverleague/silverstripe-logviewer/pull/34) ([zanderwar](https://github.com/zanderwar))
- NEW Add "list" icon for ModelAdmin [\#33](https://github.com/silverleague/silverstripe-logviewer/pull/33) ([robbieaverill](https://github.com/robbieaverill))
- NEW Format log entries as JSON, replace Compass with webpack, update screenshot [\#32](https://github.com/silverleague/silverstripe-logviewer/pull/32) ([robbieaverill](https://github.com/robbieaverill))
- FIX Update namespace for LoggerInterface in config, move config to statics, fix delete all button [\#31](https://github.com/silverleague/silverstripe-logviewer/pull/31) ([robbieaverill](https://github.com/robbieaverill))
- FIX Update Travis config and update for SS4 beta2 compatibility [\#30](https://github.com/silverleague/silverstripe-logviewer/pull/30) ([robbieaverill](https://github.com/robbieaverill))
- FIX Move tests to non-dev PSR-4 autoloader definition [\#28](https://github.com/silverleague/silverstripe-logviewer/pull/28) ([robbieaverill](https://github.com/robbieaverill))
- FIX Update logger injector alias in example code [\#26](https://github.com/silverleague/silverstripe-logviewer/pull/26) ([robbieaverill](https://github.com/robbieaverill))

## [1.0.0-alpha1](https://github.com/silverleague/silverstripe-logviewer/tree/1.0.0-alpha1) (2017-04-02)
[Full Changelog](https://github.com/silverleague/silverstripe-logviewer/compare/0.3.0...1.0.0-alpha1)

**Fixed bugs:**

- Update config API to match framework from alpha5 [\#24](https://github.com/silverleague/silverstripe-logviewer/issues/24)
- Circular dependency in configuration [\#22](https://github.com/silverleague/silverstripe-logviewer/issues/22)

**Merged pull requests:**

- FIX Update config API. Bind to LogEntry instead of LogViewer. Remove some complexity. [\#25](https://github.com/silverleague/silverstripe-logviewer/pull/25) ([robbieaverill](https://github.com/robbieaverill))

## [0.3.0](https://github.com/silverleague/silverstripe-logviewer/tree/0.3.0) (2017-01-31)
[Full Changelog](https://github.com/silverleague/silverstripe-logviewer/compare/0.2.2...0.3.0)

**Fixed bugs:**

- Downgrade PHPUnit to 4.8 and introduce PHP 5.5 as a minimum [\#20](https://github.com/silverleague/silverstripe-logviewer/issues/20)

**Closed issues:**

- Remove `getGridFieldName` in favour of `ModelAdmin::sanitiseClassName` [\#19](https://github.com/silverleague/silverstripe-logviewer/issues/19)

**Merged pull requests:**

- Support PHP 5.5. Reduce to PHPUnit 4.8. Add roles to readme. Update Travis configuration. [\#21](https://github.com/silverleague/silverstripe-logviewer/pull/21) ([robbieaverill](https://github.com/robbieaverill))
- FIX Remove second paginator. The original is invisible, but will be fixed in the framework. [\#18](https://github.com/silverleague/silverstripe-logviewer/pull/18) ([robbieaverill](https://github.com/robbieaverill))
- MINOR Change array declarations for consistency. Fix line length. Add description to canCreate. [\#17](https://github.com/silverleague/silverstripe-logviewer/pull/17) ([robbieaverill](https://github.com/robbieaverill))
- Provide permissions correctly [\#15](https://github.com/silverleague/silverstripe-logviewer/pull/15) ([Firesphere](https://github.com/Firesphere))

## [0.2.2](https://github.com/silverleague/silverstripe-logviewer/tree/0.2.2) (2017-01-23)
[Full Changelog](https://github.com/silverleague/silverstripe-logviewer/compare/0.2.1...0.2.2)

**Merged pull requests:**

- FIX Bootstrap style for clear all button, and add paginator for LogViewerAdmin [\#12](https://github.com/silverleague/silverstripe-logviewer/pull/12) ([robbieaverill](https://github.com/robbieaverill))

## [0.2.1](https://github.com/silverleague/silverstripe-logviewer/tree/0.2.1) (2017-01-20)
[Full Changelog](https://github.com/silverleague/silverstripe-logviewer/compare/0.2.0...0.2.1)

**Implemented enhancements:**

- Add documentation [\#8](https://github.com/silverleague/silverstripe-logviewer/issues/8)
- DOCS Update readme to include a screenshot, configuration and cleanup examples [\#9](https://github.com/silverleague/silverstripe-logviewer/pull/9) ([robbieaverill](https://github.com/robbieaverill))

**Merged pull requests:**

- Update composer alias for next dev version [\#10](https://github.com/silverleague/silverstripe-logviewer/pull/10) ([robbieaverill](https://github.com/robbieaverill))

## [0.2.0](https://github.com/silverleague/silverstripe-logviewer/tree/0.2.0) (2017-01-09)
[Full Changelog](https://github.com/silverleague/silverstripe-logviewer/compare/0.1.0...0.2.0)

**Implemented enhancements:**

- Add SilverStripe config handling for which log levels should be handled [\#2](https://github.com/silverleague/silverstripe-logviewer/issues/2)

## [0.1.0](https://github.com/silverleague/silverstripe-logviewer/tree/0.1.0) (2017-01-07)
**Implemented enhancements:**

-  Add button to CMS to remove old log entries manually [\#4](https://github.com/silverleague/silverstripe-logviewer/issues/4)
- Add `BuildTask`/`CronTask`/both to remove old log entries automatically [\#3](https://github.com/silverleague/silverstripe-logviewer/issues/3)

**Merged pull requests:**

- API Add configurable minimum log capture level - default 300 \(warning\) [\#7](https://github.com/silverleague/silverstripe-logviewer/pull/7) ([robbieaverill](https://github.com/robbieaverill))
- API Add BuildTask/CronTask to remove old log entries [\#6](https://github.com/silverleague/silverstripe-logviewer/pull/6) ([robbieaverill](https://github.com/robbieaverill))
- API Add "Clear all" GridFieldAction to log viewer [\#5](https://github.com/silverleague/silverstripe-logviewer/pull/5) ([robbieaverill](https://github.com/robbieaverill))
- TEST Remove silverstripe-installer from Travis build process [\#1](https://github.com/silverleague/silverstripe-logviewer/pull/1) ([robbieaverill](https://github.com/robbieaverill))



\* *This Change Log was automatically generated by [github_changelog_generator](https://github.com/skywinder/Github-Changelog-Generator)*