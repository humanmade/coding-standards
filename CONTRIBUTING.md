# Contributing

The HM coding standards represent the best practices for enabling our engineering teams to work together. As the way we work evolves over time, our coding standards likewise need to evolve.


## Guidelines for Rule Changes

Bugfixes are always welcomed and can be released in minor or patch versions.

New rules or major changes to rules need to be carefully considered and balanced against the churn they may cause. Generally, code that exists right now should continue to pass in the future unless we are **intentionally** ratcheting up rules to be stricter. These cases need to be carefully considered, as breaking production code should be avoided in most cases.

Relaxing rules can be done in minor releases, but generally should be done in major releases if it's a major change (for example, allowing different file names). Use your best judgement to decide what is a major and what is a minor change, and if in doubt, run it past @joehoyle or @rmccue.

Generally, so long as changes to rules have consensus, they are fine to be published. Any controversial rules should be widely discussed, and if a tie-breaker is needed, @joehoyle can make a final call. If you're not sure, ask @rmccue. Non-controversial changes or bugfixes do not need input from @joehoyle or @rmccue provided versioning and release processes are all followed.


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


## Releasing

Any changes which cause existing, working production code to fail should trigger a new major release. Only bugfixes or making rules more lenient should be in minor releases.

When publishing major releases, these need to be published in a two-step process. First, publish the standards, then bump the defaults after some time. This gives projects time to assess the changes and migrate at their own pace. The time between the publish and the default bump depends on the size and scope of the major changes, but generally should be 2-4 sprints worth of time for major changes.

The process for releasing is:

* Ensure your working directory is clean and up-to-date on `master`
* Run `lerna publish` and add the new version number.
	* This will prompt you for a new version number and create & push new release commits and tags which will trigger Packagist to release a new version of the Composer package.
* Run `./publish.sh` to push the standards for hm-linter
	* If you do not already have an AWS profile with access to the Linter Bot S3 bucket, make a request to the servers team for it before beginning this process.
	* If you use a non-default AWS profile for the Linter Bot, you can use `AWS_DEFAULT_PROFILE={name of AWS profile} ./publish.sh` instead.
	* This will ask if you want to bump the latest version to the new version. Only do this for patch releases.
	* To verify that the changes pushed correctly, you can run `aws s3 ls s3://hm-linter/standards/ --profile {name of AWS profile}`
* For major and minor releases, publish a changelog to the Dev H2 (significant bugfixes may also warrant a post)
* Publish a new release in the GitHub Release UI with the changes from CHANGELOG.md and update CHANGELOG.md with the release version and date.
* After a cool-off period (typically one month), bump the latest version of the standards for Linter bot.
	* Checkout the Git tag for the release.
	* Verify that your local is clean of changes.
	* Run `./publish.sh` and bump `latest`.

If you're releasing a major version, you should also create a branch for the major version so that bugfix releases can be created. This branch should be a humanised name of the version; e.g. 0.4 would be `oh-dot-four`, 1.6 would be `one-dot-six`.
