/* eslint-disable no-unused-vars */

import React from 'react';

const foo = 'foo';
const bar = 'bar';

const A = () => (
	<div>
		{ foo }
	</div>
);

const B = () => (
	<div>
		{
			foo
		}
	</div>
);

const C = () => (
	<div>
		{ foo && (
			foo.bar
		) }

		{ foo ? (
			foo.bar
		) : null }

		{ foo ? (
			foo.bar
		) : bar }

		{ foo ? (
			foo.bar
		) : (
			bar
		) }
	</div>
);
