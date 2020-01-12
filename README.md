<table width="100%">
	<tr>
		<td align="left" width="70%">
			<strong>Human Made Coding Standards</strong><br />
			WordPress coding standards, enhanced for modern development.
		</td>
		<td align="center" width="30%">
			<a href="https://packagist.org/packages/humanmade/coding-standards"><img src="https://img.shields.io/packagist/v/humanmade/coding-standards.svg" /></a>
			<a href="https://www.npmjs.com/package/eslint-config-humanmade"><img src="https://img.shields.io/npm/v/eslint-config-humanmade.svg" /></a>
			<img src="https://travis-ci.org/humanmade/coding-standards.svg?branch=master" alt="Build Status" />
		</td>
	</tr>
	<tr>
		<td>
			A <strong><a href="https://hmn.md/">Human Made</a></strong> project. Maintained by @rmccue.
		</td>
		<td align="center" width="30%">
			<img src="https://hmn.md/content/themes/hmnmd/assets/images/hm-logo.svg" width="100" />
		</td>
	</tr>
</table>

This is a codified version of [the Human Made style guide](http://engineering.hmn.md/how-we-work/style/). We include phpcs and ESLint rules.

## Setup

1. `composer require --dev humanmade/coding-standards`
2. Run the following command to run the standards checks:

```
vendor/bin/phpcs --standard=vendor/humanmade/coding-standards .
```

The final `.` here specifies the files you want to test; this is typically the current directory (`.`), but you can also selectively check files or directories by specifying them instead.

You can add this to your Travis YAML file as a test:

```yaml
script:
  - phpunit
  - vendor/bin/phpcs --standard=vendor/humanmade/coding-standards .
```

### Excluding Files

This standard includes special support for a `.phpcsignore` file (in the future, this should be [built into phpcs itself](https://github.com/squizlabs/PHP_CodeSniffer/issues/1884)). Simply place a `.phpcsignore` file in your root directory (wherever you're going to run `phpcs` from).

The format of this file is similar to `.gitignore` and similar files: one pattern per line, comment lines should start with a `#`, and whitespace-only lines are ignored:

```
# Exclude our tests directory.
tests/

# Exclude any file ending with ".inc"
*\.inc
```

Note that the patterns should match [the PHP_CodeSniffer style](https://github.com/squizlabs/PHP_CodeSniffer/wiki/Advanced-Usage#ignoring-files-and-folders): `*` is translated to `.*` for convenience, but all other characters work like a regular expression.

Patterns are relative to the directory that the `.phpcsignore` file lives in. On load, they are translated to absolute patterns: e.g. `*/tests/*` in `/your/dir/.phpcsignore` will become `/your/dir/.*/tests/.*` as a regular expression. **This differs from the regular PHP_CodeSniffer practice.**


### Advanced/Extending

If you want to add further rules (such as WordPress.com VIP-specific rules), you can create your own custom standard file (e.g. `phpcs.ruleset.xml`):

```xml
<?xml version="1.0"?>
<ruleset>
	<!-- Use HM Coding Standards -->
	<rule ref="vendor/humanmade/coding-standards" />

	<!-- Add VIP-specific rules -->
	<rule ref="WordPress-VIP" />
</ruleset>
```

You can then reference this file when running phpcs:

```
vendor/bin/phpcs --standard=phpcs.ruleset.xml .
```


#### Excluding/Disabling Checks

You can also customise the rule to exclude elements if they aren't applicable to the project:

```xml
<rule ref="vendor/humanmade/coding-standards">
	<!-- Disable short array syntax -->
	<exclude name="HM.Debug.ForceShortArray" />
</rule>
```

Rules can also be disabled inline. [phpcs rules can be disabled](https://github.com/squizlabs/PHP_CodeSniffer/wiki/Advanced-Usage#ignoring-parts-of-a-file) with a `// @codingStandardsIgnoreLine` comment, and [ESLint rules can be disabled](http://eslint.org/docs/user-guide/configuring#disabling-rules-with-inline-comments) with a `/* eslint disable ... */` comment.

To find out what these codes are, specify `-s` when running `phpcs`, and the code will be output as well. You can specify a full code, or a partial one to disable groups of errors.


## Included Checks

The phpcs standard is based upon the `WordPress-VIP` standard from [WordPress Coding Standards](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards), with [customisation and additions](HM/ruleset.xml) to match our style guide.


## Testing

### Running tests

To run the tests locally, you'll need the source version of PHP CodeSniffer.

If you haven't already installed your Composer dependencies:

```bash
composer install --prefer-source --dev
```

If you already have, and need to convert the phpcs directory to a source version:

```bash
rm -r vendor/squizlabs/php_codesniffer
composer install --prefer-source --dev
composer dump-autoload
```

### Writing sniff tests

To add tests you should mirror the directory structure of the sniffs. For example a test
for `HM/Sniffs/Layout/OrderSniff.php` would require the following files:

```
HM/Tests/Layout/OrderUnitTest.php # Unit test code
HM/Tests/Layout/OrderUnitTest.inc # Code to be tested
```

Effectively you are replacing the suffix `Sniff.php` with `UnitTest.php`.

A basic unit test class looks like the following:

```php
<?php

namespace HM\Tests\Layout;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Class name must follow the directory structure to be autoloaded correctly.
 */
class OrderUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return [
			1  => 1, // line 1 expects 1 error
		];
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList() {
		return [];
	}

}
```


### Fixture Tests

Rather than testing sniffs individually, `FixtureTests.php` also tests the files in the `tests/fixtures` directory and ensures that whole files pass.

To add an expected-pass file, simply add it into `tests/fixtures/pass` in the appropriate subdirectory/file.

To add an expected-fail file, add it into `tests/fixtures/fail` in the appropriate subdirectory/file. You then need to add the expected errors to the JSON file accompanying the tested file (i.e. the filename with `.json` appended). This file should contain a valid JSON object keyed by line number, with each item being a list of error objects:

```json
{
	"1": [
		{
			"source": "HM.Files.FunctionFileName.WrongFile",
			"type": "error"
		}
	]
}
```

An error object contains:

* `source`: Internal phpcs error code; use the `-s` flag to `phpcs` to get the code.
* `type`: One of `error` or `warning`, depending on the check's severity.


## Using ESLint

This package contains an [ESLint](https://eslint.org/) configuration which you can use to validate your JavaScript code style. While it is possible to run ESLint via phpcs, we recommend you install and use eslint via npm directly or use [linter-bot](https://github.com/humanmade/linter-bot). See [the `eslint-config-humanmade` package README](packages/eslint-config-humanmade/readme.md) for more information on configuring ESLint to use the Human Made coding standards.

Once you have installed the [`eslint-config-humanmade` npm package](https://www.npmjs.com/package/eslint-config-humanmade), you may simply specify that your own project-level ESLint file extends the `humanmade` configuration. If you install this globally (`npm install -g eslint-config-humanmade`) you can also reference the configuration directly from the command line via `eslint -c humanmade .`

While you will still have to manually install package peer dependencies, if you have installed this package using Composer it is possible to reference the `.eslintrc` file directly from the composer package in your own ESLint configuration file:

`.eslintrc`
```json
{
	"extends": "vendor/humanmade/coding-standards/packages/eslint-config-humanmade/.eslintrc"
}
```
`.eslintrc.yml`
```yaml
---
extends:
- vendor/humanmade/coding-standards/packages/eslint-config-humanmade/.eslintrc
```
