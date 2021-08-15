<table width="100%">
	<tr>
		<td align="left" width="70%">
			<strong>Human Made Coding Standards</strong><br />
			WordPress coding standards, enhanced for modern development.
		</td>
		<td align="center" width="30%">
			<a href="https://packagist.org/packages/humanmade/coding-standards"><img src="https://img.shields.io/packagist/v/humanmade/coding-standards.svg" /></a>
			<a href="https://www.npmjs.com/package/@humanmade/eslint-config"><img src="https://img.shields.io/npm/v/@humanmade/eslint-config?label=%40humanmade%2Feslint-config" /></a>
			<a href="https://www.npmjs.com/package/@humanmade/stylelint-config"><img src="https://img.shields.io/npm/v/@humanmade/stylelint-config?label=%40humanmade%2Fstylelint-config" /></a>
			<img src="https://travis-ci.com/humanmade/coding-standards.svg?branch=master" alt="Build Status" />
		</td>
	</tr>
	<tr>
		<td>
			A <strong><a href="https://hmn.md/">Human Made</a></strong> project. Maintained by @rmccue and @mikeselander.
		</td>
		<td align="center" width="30%">
			<img src="https://hmn.md/content/themes/hmnmd/assets/images/hm-logo.svg" width="100" />
		</td>
	</tr>
</table>

This is a codified version of [the Human Made style guide](http://engineering.hmn.md/how-we-work/style/). We include phpcs, ESLint, and stylelint rules.

## Contributing

We welcome contributions to these standards and want to make the experience as seamless as possible. To learn more about contributing, please reference the [CONTRIBUTING.md](CONTRIBUTING.md) file.

## Setup

Each ruleset is available individually via Composer or NPM. To install the needed ruleset, use one of the following commands:

 - PHPCS: `composer require --dev humanmade/coding-standards`
 - ESLint: `npx install-peerdeps --dev @humanmade/eslint-config@latest`
 - stylelint: `npm install --save-dev stylelint @humanmade/stylelint-config`

## Using PHPCS

Run the following command to run the standards checks:

```
vendor/bin/phpcs --standard=vendor/humanmade/coding-standards .
```

We use the [DealerDirect phpcodesniffer-composer-installer](https://github.com/Dealerdirect/phpcodesniffer-composer-installer) package to handle `installed_paths` for PHPCS when first installing the HM ruleset. If you an error such as `ERROR: Referenced sniff "WordPress-Core" does not exist`, delete the `composer.lock` file and `vendor` directories and re-install Composer dependencies.   

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

If you want to add further rules (such as WordPress.com VIP-specific rules) or customize PHPCS defaults, you can create your own custom standard file (e.g. `phpcs.ruleset.xml`):

```xml
<?xml version="1.0"?>
<ruleset>
	<!-- Files or directories to check -->
	<file>.</file>

	<!-- Path to strip from the front of file paths inside reports (displays shorter paths) -->
	<arg name="basepath" value="." />

	<!-- Set a minimum PHP version for PHPCompatibility -->
	<config name="testVersion" value="7.2-" />

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

## Using ESLint

The ESLint package contains an [ESLint](https://eslint.org/) configuration which you can use to validate your JavaScript code style. While it is possible to run ESLint via phpcs, we recommend you install and use eslint via npm directly or use [linter-bot](https://github.com/humanmade/linter-bot). See [the `@humanmade/eslint-config` package README](packages/eslint-config-humanmade/readme.md) for more information on configuring ESLint to use the Human Made coding standards.

Once you have installed the [`@humanmade/eslint-config` npm package](https://www.npmjs.com/package/@humanmade/eslint-config), you may simply specify that your own project-level ESLint file extends the `humanmade` configuration. If you install this globally (`npm install -g @humanmade/eslint-config`) you can also reference the configuration directly from the command line via `eslint -c humanmade .`

Alternatively, you can create your own configuration and extend these rules:

`.eslintrc`
```json
{
  "extends": "@humanmade"
}
```

## Using stylelint

The stylelint package contains a [stylelint](https://stylelint.io/) configuration which you can use to validate your CSS and SCSS code style. We recommend you install and use stylelint via npm directly or use [linter-bot](https://github.com/humanmade/linter-bot). See [the `@humanmade/stylelint` package README](packages/stylelint-config/readme.md) for more information on configuring stylelint to use the Human Made coding standards.

To integrate the Human Made rules into your project, add a `.stylelintrc` file and extend these rules. You can also add your own rules and overrides for further customization.

```json
{
  "extends": "@humanmade/stylelint-config",
  "rules": {
    ...
  }
}
```
