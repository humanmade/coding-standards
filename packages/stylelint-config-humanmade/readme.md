# stylelint-config-humanmade

Human Made coding standards for CSS and SCSS.

We highly recommend using this [via the `humanmade/coding-standards` Composer package.](https://github.com/humanmade/coding-standards).

## Installation

This package is a Stylelint shareable configuration, and requires `stylelint`.

To install this config and dependencies:

You can then use it directly on the command line:

```
stylelint --config ./vendor/humanmade/coding-standards/stylelint.json content/**/*.scss
```

Alternatively, you can create your own configuration and extend these rules:

```yaml
extends:
- humanmade
```