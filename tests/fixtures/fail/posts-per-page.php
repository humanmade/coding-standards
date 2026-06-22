<?php

new WP_Query( [
	'posts_per_page' => 1001,
] );

get_posts( [
	'numberposts' => 1001,
] );
