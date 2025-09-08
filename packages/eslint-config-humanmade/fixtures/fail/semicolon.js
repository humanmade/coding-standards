/* eslint-disable jsdoc/require-jsdoc */
/* eslint-disable no-unused-vars */

// This should trigger no-var rule
var testVarUsage = 'foo';

// This should pass
const testConstUsage = 'bar';

const testFunc = function () {
	// This should also trigger no-var
	var localVar = 'baz';
};
