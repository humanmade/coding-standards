# Human Made Coding Standards

This is a codified version of [the Human Made style guide](http://engineering.hmn.md/how-we-work/style/). We include phpcs and ESLint rules.

## phpcs

### Simple Setup

1. `composer require humanmade/coding-standards`
2. Add style checking to your Travis configuration with the following command:

```
vendor/bin/phpcs --standard=vendor/humanmade/coding-standards .
```

### Advanced/Extending

If you want to add further rules (such as WordPress.com VIP-specific rules), you can create your own custom standard file (e.g. `phpcs.ruleset.xml`):

```
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
