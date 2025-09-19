# @humanmade/eslint-config

Human Made coding standards for JavaScript, for ESLint v9+ using flat config format.

## Usage

**Required**: Create an `eslint.config.js` file in your project root (ESLint 9+ flat config format):

### Basic Usage
```js
import humanmadeConfig from '@humanmade/eslint-config';

export default humanmadeConfig;
```

### With Custom Overrides
```js
import humanmadeConfig from '@humanmade/eslint-config';

export default [
	...humanmadeConfig,
	// Your custom config overrides
	{
		rules: {
			'no-console': 'off', // Example override
		},
	},
];
```

## Requirements

- ESLint v9.0.0 or higher
- Node.js 20.0.0 or higher (Node.js 22 recommended)

## Installation

This package is an ESLint shareable configuration, and requires: `babel-eslint`, `eslint`, `eslint-config-react-app`, `eslint-plugin-flowtype`, `eslint-plugin-import`, `eslint-plugin-jsx-a11y`, `eslint-plugin-jsdoc`, `eslint-plugin-react`, `eslint-plugin-react-hooks`, `eslint-plugin-sort-destructure-keys`.

To install this config and the peerDependencies when using **npm 5+**:

```bash
npx install-peerdeps --dev @humanmade/eslint-config@latest
```

(Thanks to [Airbnb's package](https://www.npmjs.com/package/eslint-config-airbnb) for the command.)

You can then run ESLint on your files:

```shell
npx eslint .
```

### Working with TypeScript

This config already supports TypeScript files (.ts, .tsx) out of the box via the WordPress ESLint plugin. No additional setup is required.

## Global Installation

When installing globally, you need to ensure the peer dependencies are also installed globally.

Run the same command as above, but instead with `--global`:

```bash
npx install-peerdeps --global @humanmade/eslint-config@latest
```

This allows you to use `eslint -c humanmade MyFile.js` anywhere on your filesystem.

## Integration with Altis build script.

We require the use of Node v20+ and npm v10.8.2+ (Node v22+ recommended), however the Altis build container may ship with older versions so it may not work out of the box.

As per the Altis documentation, [you can install other versions of Node using nvm](https://docs.altis-dxp.com/cloud/build-scripts/#included-build-tools), so we recommend that you add the following to your build script.

```bash
nvm install 22
nvm use 22
```
