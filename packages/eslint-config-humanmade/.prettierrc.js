/**
 * Prettier Configuration for Human Made Coding Standards
 * 
 * This handles basic formatting while leaving JSX spacing rules to ESLint.
 */
export default {
	// Basic formatting
	singleQuote: true,
	semi: true,
	useTabs: true,
	tabWidth: 4,
	endOfLine: 'lf',
	trailingComma: 'es5',
	bracketSpacing: true,
	arrowParens: 'avoid',
	printWidth: 100,

	// JSX settings - minimal to avoid conflicts with ESLint JSX rules
	jsxSingleQuote: true,
	bracketSameLine: false,

	// Override for specific file types
	overrides: [
		{
			files: '*.{js,jsx,ts,tsx}',
			options: {
				// Let ESLint handle JSX spacing
				jsxBracketSameLine: false,
			},
		},
	],
};
