# Human Made Coding Standards

This is a codified version of [the Human Made style guide](http://engineering.hmn.md/how-we-work/style/). We include phpcs and ESLint rules.

## Usage

### phpcs

1. `composer require humanmade/coding-standards squizlabs/php_codesniffer`
2. Add style checking to your Travis configuration with the following command:

```
vendor/bin/phpcs --standard=vendor/humanmade/coding-standards .
```
