/* eslint-disable no-console */
process.on( 'unhandledRejection', err => {
	throw err;
} );

const chalk = require( 'chalk' );
const join = require( 'path' ).join;
const { CLIEngine } = require( 'eslint' );

const cli = new CLIEngine( { useEslintrc: true } );
const formatter = CLIEngine.getFormatter();

const verbose = process.argv.indexOf( '--verbose' ) > -1;

/** Utility to count errors, warnings & processed file count. */
const count = results => results.reduce( ( counts, file ) => ( {
	errors: counts.errors + file.errorCount,
	warnings: counts.warnings + file.warningCount,
	files: counts.files + 1,
} ), {
	errors: 0,
	warnings: 0,
	files: 0,
} );

console.log( 'Running ESLint on fixture directories. Use --verbose for a detailed report.' );
console.log( '\nLinting `fixtures/antipatterns/**`...' );

const antipatternReport = cli.executeOnFiles( [ join( __dirname, 'antipatterns/**' ) ] );
const antipatternCounts = count( antipatternReport.results );

if ( antipatternCounts.errors ) {
	console.log( chalk.green( 'ESLint logs errors as expected.\n' ) );
} else {
	console.log( chalk.bold.red( 'Errors expected, but none encountered!\n' ) );
	process.exitCode = 1;
}

// Log full report when --verbose, or when no errors are reported.
if ( verbose || ! antipatternCounts.errors ) {
	console.log( formatter( antipatternReport.results ) );
}

console.log( 'Linting `fixtures/examples/**`...' );

const exampleReport = cli.executeOnFiles( [ join( __dirname, 'examples/**' ) ] );
const exampleCounts = count( exampleReport.results );

// Log full report when --verbose, or whenever errors are unexpectedly reported.
if ( verbose || exampleCounts.errors || exampleCounts.warnings ) {
	console.log( formatter( exampleReport.results ) );
}

if ( exampleCounts.errors ) {
	const { errors } = exampleCounts;
	console.log( chalk.bold.red( `${ errors } unexpected error${ errors !== 1 ? 's' : '' }!\n` ) );
	process.exitCode = 1;
} else {
	const { files } = exampleCounts;
	console.log( chalk.green( `${ files } files pass lint.` ) );
}
