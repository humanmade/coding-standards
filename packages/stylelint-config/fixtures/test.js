const stylelint = require( 'stylelint' );
const chalk = require( 'chalk' );
const path = require( 'path' );

const lintFail = stylelint.lint( {
	files: path.join( __dirname, 'fail/**' ),
} );

const lintPass = stylelint.lint( {
	files: path.join( __dirname, 'pass/**' ),
} );

Promise.all( [ lintFail, lintPass ] )
	.then( ( [ lintFailResults, lintPassResults ] ) => {
		// Are any files passing that we expect to fail?
		const failFilePassing = lintFailResults.results.find( result => ! result.errored );

		if ( failFilePassing ) {
			const file = path.relative( __dirname, failFilePassing.source );
			console.log( chalk.bold.red( `Errors expected in ${ file }, but none encountered!\n` ) );
			process.exitCode = 1;
		} else if ( lintFailResults.errored ) {
			console.log( chalk.green( `Errors found in files expected to fail.` ) );
		}

		// Handle results for files expected to pass.
		if ( lintPassResults.errored ) {
			// console.log( chalk.bold.red( `${ errors } unexpected error${ errors !== 1 ? 's' : '' }!\n` ) );
			console.log( chalk.bold.red( `Unexpected errors!\n` ) );
			process.exitCode = 1;
		} else if ( ! lintPassResults.errored ) {
			console.log( chalk.green( `No errors in files expected to pass.` ) );
		}
	} )
	.catch( () => {
		console.log( chalk.bold.red( `Stylelint failed to run.\n` ) );
		process.exitCode = 1;
	} )
