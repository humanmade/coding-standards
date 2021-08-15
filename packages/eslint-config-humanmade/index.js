module.exports = {
	'root': true,
	'env': {
		'browser': true,
		'es6': true,
	},
	'extends': [
		'eslint:recommended',
		'react-app',
		'plugin:import/errors',
		'plugin:jsdoc/recommended',
		'plugin:react-hooks/recommended',
	],
	'parserOptions': {
		'ecmaVersion': 2018,
		'ecmaFeatures': {
			'jsx': true,
		},
		'sourceType': 'module',
	},
	'rules': {
		'array-bracket-spacing': [ 'error', 'always' ],
		'arrow-parens': [ 'error', 'as-needed' ],
		'arrow-spacing': [ 'error', {
			'before': true,
			'after': true,
		} ],
		'block-spacing': [ 'error' ],
		'brace-style': [ 'error', '1tbs' ],
		'comma-dangle': [ 'error', {
			'arrays': 'always-multiline',
			'objects': 'always-multiline',
			'imports': 'always-multiline',
			'exports': 'always-multiline',
			'functions': 'never',
		} ],
		'comma-spacing': [ 'error', {
			'before': false,
			'after': true,
		} ],
		'eol-last': [ 'error', 'unix' ],
		'eqeqeq': [ 'error' ],
		'func-call-spacing': [ 'error' ],
		'import/no-unresolved': [ 'off' ],
		'import/order': [ 'error', {
			'alphabetize': {
				'order': 'asc',
				'caseInsensitive': true
			},
			'groups': [ 'builtin', 'external', 'parent', 'sibling', 'index' ],
			'newlines-between': 'always',
			'pathGroups': [
				{
					'pattern': '@wordpress/**',
					'group': 'external',
					'position': 'after'
				}
			],
			'pathGroupsExcludedImportTypes': [ 'builtin' ]
		} ],
		'indent': [ 'error', 'tab', {
			'SwitchCase': 1,
		} ],
		'key-spacing': [ 'error', {
			'beforeColon': false,
			'afterColon': true,
		} ],
		'keyword-spacing': [ 'error', {
			'after': true,
			'before': true,
		} ],
		'linebreak-style': [ 'error', 'unix' ],
		'no-console': [ 'warn' ],
		'no-mixed-spaces-and-tabs': [ 'error', 'smart-tabs' ],
		'no-multiple-empty-lines': [ 'error', {
			'max': 1,
		} ],
		'no-trailing-spaces': [ 'error' ],
		'no-var': [ 'warn' ],
		'object-curly-newline': [ 'error', {
			'ObjectExpression': {
				'consistent': true,
				'minProperties': 2,
				'multiline': true,
			},
			'ObjectPattern': {
				'consistent': true,
				'multiline': true,
			},
			'ImportDeclaration': {
				'consistent': true,
				'multiline': true,
			},
			'ExportDeclaration': {
				'consistent': true,
				'minProperties': 2,
				'multiline': true,
			},
		} ],
		'object-curly-spacing': [ 'error', 'always' ],
		'object-property-newline': [ 'error' ],
		'quotes': [ 'error', 'single' ],
		'semi': [ 'error', 'always' ],
		'semi-spacing': [ 'error', {
			'before': false,
			'after': true,
		} ],
		'space-before-function-paren': [ 'error', {
			'anonymous': 'always',
			'asyncArrow': 'always',
			'named': 'never',
		} ],
		'space-in-parens': [ 'warn', 'always', {
			'exceptions': [ 'empty' ],
		} ],
		'space-unary-ops': [ 'error', {
			'words': true,
			'nonwords': false,
			'overrides': {
				'!': true,
			},
		} ],
		'template-curly-spacing': [ 'error', 'always' ],
		'yoda': [ 'error', 'never' ],
		'jsdoc/require-jsdoc': [ 'error', {
			'require': {
				'FunctionDeclaration': true,
				'ClassDeclaration': true,
				'ArrowFunctionExpression': true,
				'FunctionExpression': true,
			},
		} ],
		'react/jsx-curly-spacing': [ 'error', {
			'when': 'always',
			'children': true,
		} ],
		'react/jsx-wrap-multilines': [ 'error' ],
		'react/jsx-curly-newline': [ 'warn', {
			'multiline': 'consistent',
			'singleline': 'consistent',
		} ],
		'react/jsx-boolean-value': [ 'error', 'never' ],
		'react/jsx-sort-props': [ 'warn', {
			'reservedFirst': [ 'key', 'ref' ],
			'callbacksLast': true,
			'ignoreCase': true,
		} ],
		'jsx-a11y/anchor-is-valid': [ 'error' ],
	},
};
