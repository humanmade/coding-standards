import wordpress from '@wordpress/eslint-plugin';
import importPlugin from 'eslint-plugin-import';
import jsdocPlugin from 'eslint-plugin-jsdoc';
import jsxA11yPlugin from 'eslint-plugin-jsx-a11y';
import prettierPlugin from 'eslint-plugin-prettier';
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
			prettier: prettierPlugin,
			react: reactPlugin,
		},
		rules: {
			// WordPress recommended rules
			...wordpress.configs.recommended.rules,

			// Code Quality Rules (non-formatting)
			'eqeqeq': ['error'],
			'no-console': ['warn'],
			'no-unused-vars': ['error', { 
				vars: 'all',
				args: 'after-used',
				ignoreRestSiblings: true
			}],
			'no-var': ['warn'],
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

			// Formatting handled by Prettier
			'prettier/prettier': ['error'],
			'jsdoc/check-line-alignment': ['warn'],
			'jsdoc/require-jsdoc': [
				'warn',
				{
					require: {
						FunctionDeclaration: true,
						ClassDeclaration: true,
						ArrowFunctionExpression: true,
						FunctionExpression: true,
					},
				},
			],
			// JSX rules - important for Human Made standards
			'react/jsx-curly-spacing': ['error', {
				when: 'always',
				children: true,
			}],
			'react/jsx-wrap-multilines': ['error'],
			'react/jsx-curly-newline': ['warn', {
				multiline: 'consistent',
				singleline: 'consistent',
			}],
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
