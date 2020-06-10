<?php

new WP_Query( [
	'orderby' => 'rand',
] );
new WP_Query( [
	'orderby' => 'meta_value',
] );
new WP_Query( [
	'orderby' => 'meta_value_num',
] );
