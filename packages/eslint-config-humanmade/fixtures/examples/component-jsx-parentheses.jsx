import React from 'react';

const World = () => <span>World</span>;

const Hello = () => (
	<div>
		<p>Hello <World /></p>
	</div>
);

export default class Test extends React.Component {
	render() {
		return (
			<div>
				<Hello />
			</div>
		);
	}
}
