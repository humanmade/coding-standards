<?php

// Basic meta query.
new WP_Query( [
	'meta_key' => 'foo',
	'meta_value' => 'bar',
] );

// Advanced meta query.
new WP_Query( [
	'meta_query' => [
		'key' => 'foo',
		'value' => 'bar',
	],
] );

// Custom compares.
new WP_Query( [
	'meta_query' => [
		'key' => 'foo',
		'value' => 'bar',
		'compare' => '!=',
	],
] );

new WP_Query( [
	'meta_query' => [
		'key' => 'foo',
		'value' => 'bar',
		'compare' => '>',
	],
] );

new WP_Query( [
	'meta_query' => [
		'key' => 'foo',
		'value' => 'bar',
		'compare' => 'LIKE',
	],
] );

// Variables.
$compare = 'LIKE';
new WP_Query( [
	'meta_query' => [
		'key' => 'foo',
		'value' => 'bar',
		'compare' => $compare,
	],
] );
$meta_query = [
	'key' => 'foo',
	'value' => 'bar',
];
new WP_Query( [
	'meta_query' => $meta_query,
] );
