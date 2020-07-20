/* eslint-disable jsdoc/require-jsdoc */
/* eslint-disable no-unused-vars */

import React from 'react';

const foo = 'foo';

const A = () => (
	<div>
		{ foo
		}
	</div>
);

const B = () => (
	<div>
		{
			foo }
	</div>
);

const C = () => (
	<div>
		{ foo &&
			foo.bar
		}
	</div>
);
