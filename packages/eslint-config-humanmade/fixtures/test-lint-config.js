/* eslint-disable no-console */
process.on( 'unhandledRejection', err => {
	throw err;
} );

import chalk from 'chalk';
import { join } from 'path';
import { ESLint } from 'eslint';
import { fileURLToPath } from 'url';

const __dirname = fileURLToPath(new URL('.', import.meta.url));

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

async function runTests() {
	const eslint = new ESLint({
		overrideConfigFile: join(__dirname, 'eslint.config.js')
	});
	const formatterObject = await eslint.loadFormatter('stylish');
	const formatter = formatterObject.format;

	console.log( 'Running ESLint on fixture directories. Use --verbose for a detailed report.' );
	console.log( '\nLinting `fixtures/fail/**`...' );

	const antipatternReport = await eslint.lintFiles( [ join( __dirname, 'fail/**' ) ] );
	const antipatternCounts = count( antipatternReport );
	const allFail = antipatternReport.reduce( ( didFail, file ) => didFail = didFail && ( file.errorCount > 0 || file.warningCount > 0 ), true );

	if ( allFail ) {
		console.log( chalk.green( 'ESLint logs errors as expected.\n' ) );
	} else if ( antipatternCounts.errors ) {
		console.log( chalk.bold.red( 'The following files did not produce errors:' ) );
		antipatternReport.forEach( file => {
			if ( file.errorCount > 0 || file.warningCount > 0 ) {
				return;
			}

			console.log( '  ' + file.filePath );
		} );
		console.log( '' );

		process.exitCode = 1;
	} else {
		console.log( chalk.bold.red( 'Errors expected, but none encountered!\n' ) );
		process.exitCode = 1;
	}

	// Log full report when --verbose, or when no errors are reported.
	if ( verbose || ! antipatternCounts.errors ) {
		console.log( formatter( antipatternReport ) );
	}

	console.log( 'Linting `fixtures/pass/**`...' );

	const exampleReport = await eslint.lintFiles( [ join( __dirname, 'pass/**' ) ] );
	const exampleCounts = count( exampleReport );

	// Log full report when --verbose, or whenever errors are unexpectedly reported.
	if ( verbose || exampleCounts.errors || exampleCounts.warnings ) {
		console.log( formatter( exampleReport ) );
	}

	if ( exampleCounts.errors ) {
		const { errors } = exampleCounts;
		console.log( chalk.bold.red( `${ errors } unexpected error${ errors !== 1 ? 's' : '' }!\n` ) );
		process.exitCode = 1;
	} else {
		const { files } = exampleCounts;
		console.log( chalk.green( `${ files } files pass lint.` ) );
	}
}

runTests().catch( console.error );
