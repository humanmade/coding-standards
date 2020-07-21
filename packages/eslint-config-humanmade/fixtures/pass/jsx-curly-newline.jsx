/* eslint-disable no-unused-vars */

import React from 'react';

const foo = 'foo';
const bar = 'bar';

/**
 * @returns {React.ReactNode} Rendered <A> component.
 */
const A = () => (
	<div>
		{ foo }
	</div>
);

/**
 * @returns {React.ReactNode} Rendered <B> component.
 */
const B = () => (
	<div>
		{
			foo
		}
	</div>
);

/**
 * @returns {React.ReactNode} Rendered <C> component.
 */
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
