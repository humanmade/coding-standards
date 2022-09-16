const stylelint = require(  'stylelint' );
const chalk = require(  'chalk' );
const path = require(  'path' );

stylelint.lint( {
	files: 'fixtures/pass/**/*.{css,scss}'
} ).then( (resultObject) => {
	if ( resultObject.errored ) {
		console.log( chalk.bold.red( 'Stylelint detected the following errors in the test files that are expected to pass.' ) );
		resultObject.results.forEach( result => {
			if ( ! result.errored ) {
				return;
			}

			console.log( '• ' + path.relative( process.cwd(), result.source ) );
			result.warnings.forEach( result => {
				console.log( `  • ${ result.text }. Line: ${ result.line }.` );
			} );
		} );
		process.exitCode = 1;
	} else {
		console.log( chalk.green( 'No errors detected in files that are expected to pass.' ) );
	}
});

stylelint.lint( {
	files: 'fixtures/fail/**/*.{css,scss}'
} ).then( (resultObject) => {
	if ( ! resultObject.errored ) {
			console.log( chalk.bold.red( 'The following files did not produce errors:' ) );
			resultObject.results.forEach( result => {
				if ( result.errored ) {
					return;
				}

				console.log( '  ' + result.source );
			} );
		process.exitCode = 1;
	} else {
		console.log( chalk.green( 'All files that should fail log errors as expected.' ) );
	}
});
