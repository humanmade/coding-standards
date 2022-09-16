# @humanmade/eslint-config

Human Made coding standards for JavaScript.

## Installation

This package is an ESLint shareable configuration, and requires: `babel-eslint`, `eslint`, `eslint-config-react-app`, `eslint-plugin-flowtype`, `eslint-plugin-import`, `eslint-plugin-jsx-a11y`, `eslint-plugin-jsdoc`, `eslint-plugin-react`, `eslint-plugin-react-hooks`, `eslint-plugin-sort-destructure-keys`.

To install this config and the peerDependencies when using **npm 5+**:

```
npx install-peerdeps --dev @humanmade/eslint-config@latest
```

(Thanks to [Airbnb's package](https://www.npmjs.com/package/eslint-config-airbnb) for the command.)

You can then use it directly on the command line:

```shell
./node_modules/.bin/eslint -c @humanmade/eslint-config MyFile.js
```

Alternatively, you can create your own configuration and extend these rules:
```yaml
extends:
- @humanmade/eslint-config
```

### Working with TypeScript

If you desire to use TypeScript for your project, you will need to add another dependency:

```shell
npm install --save-dev @typescript-eslint/parser
```

Once it's installed, update your configuration with the `parser` parameter:

```yml
parser: "@typescript-eslint/parser"
extends:
    - @humanmade/eslint-config
```

## Global Installation

When installing globally, you need to ensure the peer dependencies are also installed globally.

Run the same command as above, but instead with `--global`:

```shell
npx install-peerdeps --global @humanmade/eslint-config@latest
```

This allows you to use `eslint -c humanmade MyFile.js` anywhere on your filesystem.

## Integration with Altis build script.

We require the use of Node v16+ and npm v7+, however the Altis build container ships with Node 12.18 and npm 6.14 so it will not work out of the box.

As per the Altis documentation, [you can install other versions of Node using nvm](https://docs.altis-dxp.com/cloud/build-scripts/#included-build-tools), so we recommend that you add the following to your build script.

```
nvm install 16
nvm use 16
```
