<?php

// Key without value is OK (albeit useless).
new WP_Query( [
	'meta_key' => 'foo',
] );

// EXISTS/NOT EXISTS are performant.
new WP_Query( [
	'meta_query' => [
		'key' => 'foo',
		'compare' => 'EXISTS',
	],
] );
new WP_Query( [
	'meta_query' => [
		'key' => 'foo',
		'compare' => 'NOT EXISTS',
	],
] );

// Ignores should work.
new WP_Query( [
	'meta_query' => [
		'key' => 'foo',
		'value' => 'bar',
		// phpcs:ignore HM.Performance.SlowMetaQuery.nonperformant_comparison -- Only a few records, so performant.
		'compare' => '!=',
	],
] );

$meta_query = [
	'key' => 'foo',
	'compare' => 'EXISTS',
];
new WP_Query( [
	// phpcs:ignore HM.Performance.SlowMetaQuery.dynamic_query -- See above, performant.
	'meta_query' => $query,
] );

