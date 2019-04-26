# eslint-config-humanmade

Human Made coding standards for JavaScript.

## Installation

This package is an ESLint shareable configuration, and requires `babel-eslint`, `eslint`, `eslint-config-react-app`, `eslint-plugin-flowtype`, `eslint-plugin-import`, `eslint-plugin-jsx-a11y`, `eslint-plugin-react`.

To install this config and the peerDependencies:

```
npm info "eslint-config-humanmade@latest" peerDependencies --json | command sed 's/[\{\},]//g ; s/: /@/g' | xargs npm install --save-dev "eslint-config-humanmade@latest"
```

(Thanks to [Airbnb's package](https://www.npmjs.com/package/eslint-config-airbnb) for the command.)

You can then use it directly on the command line:

```
eslint -c humanmade MyFile.js
```

Alternatively, you can create your own configuration and extend these rules:
```yaml
extends:
- humanmade
```

## Global Installation

When installing globally, you need to ensure the peer dependencies are also installed globally.

Run the same command as above, but with `-g` added:

```
npm info "eslint-config-humanmade@latest" peerDependencies --json | command sed 's/[\{\},]//g ; s/: /@/g' | xargs npm install --save-dev "eslint-config-humanmade@latest"
```

This allows you to use `eslint -c humanmade MyFile.js` anywhere on your filesystem.
