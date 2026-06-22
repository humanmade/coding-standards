<?php

new WP_Query( [
	'posts_per_page' => 1000,
] );

get_posts( [
	'numberposts' => 1000,
] );
