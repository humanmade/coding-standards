let obj = [ 'only assigned once' ];

var str = 'default value';
const str2 = `${ str }, but declared with const`;
if ( obj[0] === global.condition ) {
	str2 = 'other value maybe assigned later';
}
