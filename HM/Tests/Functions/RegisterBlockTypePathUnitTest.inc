<?php

// OK: valid block names.
register_block_type( 'my-plugin/my-block' );
register_block_type( 'my-plugin/my-block', [ 'render_callback' => 'my_render' ] );

// OK: no signal we can confidently act on.
register_block_type( $block );
register_block_type( $path );
register_block_type( $path . '/my-block' );
register_block_type( new WP_Block_Type( 'my-plugin/my-block' ) );
register_block_type( MY_NAMESPACE . '/my-block' );

// Flagged as paths: magic constants.
register_block_type( __DIR__ . '/build/my-block' );
register_block_type( __DIR__ . '/build/my-block/block.json' );
register_block_type( __FILE__ );

// Flagged as paths: path-building functions.
register_block_type( dirname( __FILE__ ) . '/blocks/my-block' );
register_block_type( plugin_dir_path( __FILE__ ) . 'blocks/my-block' );
register_block_type( trailingslashit( $base ) . 'my-block' );

// Flagged as paths: constants named like paths.
register_block_type( MY_PLUGIN_DIR . '/blocks/my-block' );
register_block_type( MY_PLUGIN_PATH . '/my-block' );

// Flagged as paths: path-like string literals.
register_block_type( 'blocks/my-block/block.json' );
register_block_type( '/var/www/html/blocks/my-block' );
register_block_type( './blocks/my-block' );
register_block_type( "{$dir}/build/my-block" );

// Flagged as paths: fully-qualified call.
\register_block_type( __DIR__ . '/build/my-block' );

// Flagged, but not auto-fixable: named argument doesn't exist on replacement.
register_block_type( block_type: __DIR__ . '/build/my-block' );

// OK: second parameter never holds the path.
register_block_type( 'my-plugin/my-block', [ 'style' => 'file:./style.css' ] );
