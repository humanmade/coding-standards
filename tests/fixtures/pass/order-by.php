<?php

// Regular ordering is fine.
new WP_Query( [
	'orderby' => 'menu_order',
] );

// Manual ignores are OK too.
new WP_Query( [
	// phpcs:ignore HM.Performance.SlowOrderBy.slow_order -- Only a few values.
	'orderby' => 'meta_value',
] );
