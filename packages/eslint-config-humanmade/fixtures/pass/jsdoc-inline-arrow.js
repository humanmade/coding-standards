/* eslint-disable no-unused-vars */

const result = [].map( val => val.subval );

const filteredThing = []
	.filter( item => item.isIncludedInSet )
	.reduce( ( sum, item ) => ( sum + item.immenseValue ), 0 );
