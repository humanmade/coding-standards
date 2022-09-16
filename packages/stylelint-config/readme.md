# @humanmade/stylelint-config

Human Made coding standards for CSS and SCSS.

## Installation

This package is a stylelint shareable configuration, and requires the `stylelint` library.

To install this config and dependencies:

```bash
npm install --save-dev stylelint @humanmade/stylelint-config
```

Then, add a `.stylelintrc` file and extend these rules. You can also add your own rules and overrides for further customization.

```json
{
  "extends": "@humanmade/stylelint-config",
  "rules": {
    ...
  }
}
```

## Integration with Altis build script.

We require the use of Node v16+ and npm v7+, however the Altis build container ships with Node 12.18 and npm 6.14 so it will not work out of the box.

As per the Altis documentation, [you can install other versions of Node using nvm](https://docs.altis-dxp.com/cloud/build-scripts/#included-build-tools), so we recommend that you add the following to your build script.

```
nvm install 16
nvm use 16
```
