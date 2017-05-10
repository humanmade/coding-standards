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

1. `composer require humanmade/coding-standards`
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

	<!-- Disable all ESLint checks... -->
	<exclude name="HM.Debug.ESLint" />

	<!-- Or disable just a singular rule -->
	<exclude name="HM.Debug.ESLint.no-unused-vars" />
</rule>
```

Rules can also be disabled inline. [phpcs rules can be disabled](https://github.com/squizlabs/PHP_CodeSniffer/wiki/Advanced-Usage#ignoring-parts-of-a-file) with a `// @codingStandardsIgnoreLine` comment, and [ESLint rules can be disabled](http://eslint.org/docs/user-guide/configuring#disabling-rules-with-inline-comments) with a `/* eslint disable ... */` comment.

To find out what these codes are, specify `-s` when running `phpcs`, and the code will be output as well. You can specify a full code, or a partial one to disable groups of errors.


### Custom ESLint Configuration

This repo comes with a .eslintrc.yml file matching the HM coding standards. While checks can be disabled using the `<exclude />` rules, you can't add additional checks this way. Instead, you can create your own ESLint config file.

ESLint configuration files (`.eslintrc.js`, `.eslintrc.yaml`, `.eslintrc.yml`, `.eslintrc.json`) will be **autodetected** by phpcs and used automatically if they exist. Inside your configuration file, you can extend the HM Coding Standards lint file by referencing it by a path:

```yaml
---
extends:
- vendor/humanmade/coding-standards/.eslintrc.yml
```

You can also use a custom path and reference this in your ruleset:

```xml
<rule ref="HM.Debug.ESLint">
	<properties>
		<property name="configFile" value="your/lint/config.yml"/>
	</properties>
</rule>
```

**Important Note:** This must come *after* the `vendor/humanmade/coding-standards` rule, and be a direct child of `<ruleset />`.

If you're using the ESLint configuration without phpcs, you can simply use `humanmade`, as the configuration is [published on npm](https://www.npmjs.com/package/eslint-config-humanmade). You can also install this globally (`npm install -g eslint-config-humanmade`) and then use directly on the command line via `eslint -c humanmade .`


## Included Checks

The phpcs standard is based upon the `WordPress-VIP` standard from [WordPress Coding Standards](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards), with [customisation and additions](HM/ruleset.xml) to match our style guide.

phpcs also includes ESLint checking based upon the `eslint:recommended` standard (checks from [this page](http://eslint.org/docs/rules/) marked with a check mark), with [customisation and additions](.eslintrc.yml) to match our style guide.

**Note:** ESLint checks are mapped from ESLint codes to phpcs codes by prefixing with `HM.Debug.ESLint`. e.g. the `no-unused-vars` ESLint code becomes `HM.Debug.ESLint.no-unused-vars`. You need to use the phpcs code when excluding specific rules.

## Testing

### Running tests

To run the tests locally you need a checkout of PHP Code Sniffer, any other
type of install that isn't v3 or higher will not have the tests included.

```bash
git clone -b 2.8.1 git@github.com:squizlabs/PHP_CodeSniffer
cd PHP_CodeSniffer
composer install
scripts/phpcs --config-set installed_paths /path/to/this/repo
vendor/bin/phpunit --filter HM
```

### Writing tests

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

/**
 * Class name must follow the directory structure to be autoloaded correctly.
 * 
 * **NO NAMESPACES!!**
 */
class HM_Tests_Layout_OrderUnitTest extends AbstractSniffUnitTest {

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
