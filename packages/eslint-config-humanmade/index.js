import wordpress from '@wordpress/eslint-plugin';
import importPlugin from 'eslint-plugin-import';
import jsdocPlugin from 'eslint-plugin-jsdoc';
import jsxA11yPlugin from 'eslint-plugin-jsx-a11y';
import reactPlugin from 'eslint-plugin-react';

/**
 * ESLint v9 Flat Config for Human Made Coding Standards
 */
export default [
	// Apply to JavaScript and TypeScript files
	{
		files: ['**/*.{js,mjs,cjs,jsx,ts,tsx}'],
		languageOptions: {
			ecmaVersion: 2024,
			sourceType: 'module',
			parserOptions: {
				ecmaFeatures: {
					jsx: true,
				},
			},
			globals: {
				// Browser environment
				window: 'readonly',
				document: 'readonly',
				console: 'readonly',
				// ES6 globals
				Promise: 'readonly',
				Map: 'readonly',
				Set: 'readonly',
			},
		},
		plugins: {
			'@wordpress': wordpress,
			import: importPlugin,
			jsdoc: jsdocPlugin,
			'jsx-a11y': jsxA11yPlugin,
			react: reactPlugin,
		},
		rules: {
			// All formatting and code quality rules from master branch
			'array-bracket-spacing': ['error', 'always'],
			'arrow-parens': ['error', 'as-needed'],
			'arrow-spacing': [
				'error',
				{
					before: true,
					after: true,
				},
			],
			'block-spacing': ['error'],
			'brace-style': ['error', '1tbs'],
			'comma-dangle': [
				'error',
				{
					arrays: 'always-multiline',
					objects: 'always-multiline',
					imports: 'always-multiline',
					exports: 'always-multiline',
					functions: 'never',
				},
			],
			'comma-spacing': [
				'error',
				{
					before: false,
					after: true,
				},
			],
			'eol-last': ['error', 'unix'],
			'eqeqeq': ['error'],
			'func-call-spacing': ['error'],
			'indent': [
				'error',
				'tab',
				{
					SwitchCase: 1,
				},
			],
			'key-spacing': [
				'error',
				{
					beforeColon: false,
					afterColon: true,
				},
			],
			'keyword-spacing': [
				'error',
				{
					after: true,
					before: true,
				},
			],
			'linebreak-style': ['error', 'unix'],
			'no-console': ['warn'],
			'no-mixed-spaces-and-tabs': ['error', 'smart-tabs'],
			'no-multiple-empty-lines': [
				'error',
				{
					max: 1,
				},
			],
			'no-trailing-spaces': ['error'],
			'no-unused-vars': ['error'],
			'no-var': ['warn'],
			'object-curly-newline': [
				'error',
				{
					ObjectExpression: {
						consistent: true,
						minProperties: 2,
						multiline: true,
					},
					ObjectPattern: {
						consistent: true,
						multiline: true,
					},
					ImportDeclaration: {
						consistent: true,
						multiline: true,
					},
					ExportDeclaration: {
						consistent: true,
						minProperties: 2,
						multiline: true,
					},
				},
			],
			'object-curly-spacing': ['error', 'always'],
			'object-property-newline': ['error'],
			'quotes': ['error', 'single'],
			'semi': ['error', 'always'],
			'semi-spacing': [
				'error',
				{
					before: false,
					after: true,
				},
			],
			'space-before-function-paren': [
				'error',
				{
					anonymous: 'always',
					asyncArrow: 'always',
					named: 'never',
				},
			],
			'space-in-parens': [
				'warn',
				'always',
				{
					exceptions: ['empty'],
				},
			],
			'space-unary-ops': [
				'error',
				{
					words: true,
					nonwords: false,
					overrides: {
						'!': true,
					},
				},
			],
			'template-curly-spacing': ['error', 'always'],
			'yoda': ['error', 'never'],

			// Import/Export Rules
			'import/no-unresolved': ['off'],
			'import/order': [
				'error',
				{
					alphabetize: {
						order: 'asc',
						caseInsensitive: true,
					},
					groups: ['builtin', 'external', 'parent', 'sibling', 'index'],
					'newlines-between': 'always',
					pathGroups: [
						{
							pattern: '@wordpress/**',
							group: 'external',
							position: 'after',
						},
					],
					pathGroupsExcludedImportTypes: ['builtin'],
				},
			],

			// JSDoc Rules
			'jsdoc/require-jsdoc': [
				'error',
				{
					require: {
						FunctionDeclaration: true,
						ClassDeclaration: true,
						ArrowFunctionExpression: true,
						FunctionExpression: true,
					},
				},
			],

			// React/JSX Rules
			'react/jsx-curly-spacing': [
				'error',
				{
					when: 'always',
					children: true,
				},
			],
			'react/jsx-wrap-multilines': ['error'],
			'react/jsx-curly-newline': [
				'warn',
				{
					multiline: 'consistent',
					singleline: 'consistent',
				},
			],
			'react/jsx-boolean-value': ['error', 'never'],
			'react/jsx-sort-props': [
				'warn',
				{
					reservedFirst: ['key', 'ref'],
					callbacksLast: true,
					ignoreCase: true,
				},
			],
			'jsx-a11y/anchor-is-valid': ['error'],
		},
	},
	// Ignore patterns
	{
		ignores: ['node_modules/**', 'dist/**', 'build/**', '*.min.js'],
	},
];
