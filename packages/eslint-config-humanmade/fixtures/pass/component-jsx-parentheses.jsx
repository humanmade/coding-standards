import React from 'react';

/**
 * @returns {React.ReactNode} Rendered <World> component.
 */
const World = () => <span>World</span>;

/**
 * @returns {React.ReactNode} Rendered <Hello> component.
 */
const Hello = () => (
	<div>
		<p>Hello <World /></p>
	</div>
);

/**
 * Test class.
 */
export default class Test extends React.Component {
	render() {
		return (
			<div>
				<Hello />
			</div>
		);
	}
}
