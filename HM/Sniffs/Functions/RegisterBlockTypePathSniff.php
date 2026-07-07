<?php

namespace HM\Sniffs\Functions;

use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\PassedParameters;
use PHPCSUtils\Utils\TextStrings;
use WordPressCS\WordPress\AbstractFunctionParameterSniff;

/**
 * Recommend register_block_type_from_metadata() when a path is passed to register_block_type().
 *
 * register_block_type() only delegates to register_block_type_from_metadata() if the
 * path it is given exists at call time. A missing path (unbuilt assets, a typo) falls
 * through to WP_Block_Type_Registry::register(), which tries to parse the path as a
 * "namespace/block-name" string and triggers a confusing _doing_it_wrong() notice.
 * Calling register_block_type_from_metadata() directly states the intent and fails
 * predictably when the path is wrong.
 */
class RegisterBlockTypePathSniff extends AbstractFunctionParameterSniff {

	/**
	 * Valid block name pattern, per WP_Block_Type_Registry::register().
	 */
	const VALID_BLOCK_NAME = '`^[a-z][a-z0-9-]*/[a-z][a-z0-9-]*$`';

	/**
	 * Warning message shared by both report sites.
	 */
	const MESSAGE = 'Argument 1 of register_block_type() appears to be a path; use register_block_type_from_metadata() instead for predictable file handling.';

	/**
	 * Group name for this group of functions.
	 *
	 * @var string
	 */
	protected $group_name = 'register_block_type';

	/**
	 * Functions this sniff is looking for.
	 *
	 * @var array
	 */
	protected $target_functions = [
		'register_block_type' => true,
	];

	/**
	 * Functions which build filesystem paths. A call to any of these within the
	 * first argument marks it as a path.
	 *
	 * @var array
	 */
	protected $path_functions = [
		'dirname',
		'realpath',
		'plugin_dir_path',
		'get_template_directory',
		'get_stylesheet_directory',
		'get_theme_file_path',
		'get_parent_theme_file_path',
		'trailingslashit',
		'untrailingslashit',
		'path_join',
	];

	/**
	 * Process the parameters of a matched register_block_type() call.
	 *
	 * @param int    $stackPtr        The position of the function name token.
	 * @param string $group_name      The name of the matched group.
	 * @param string $matched_content The matched function name in lowercase.
	 * @param array  $parameters      The parameters passed to the call.
	 * @return void
	 */
	public function process_parameters( $stackPtr, $group_name, $matched_content, $parameters ) {
		$block_type = PassedParameters::getParameterFromStack( $parameters, 1, 'block_type' );
		if ( $block_type === false ) {
			return;
		}

		if ( ! $this->parameter_looks_like_path( $block_type ) ) {
			return;
		}

		/*
		 * register_block_type_from_metadata() names its first parameter $file_or_folder,
		 * so the rename fixer is only safe when the argument is passed positionally.
		 */
		if ( isset( $block_type['name'] ) ) {
			$this->phpcsFile->addWarning( self::MESSAGE, $stackPtr, 'PathDetected' );
			return;
		}

		$fix = $this->phpcsFile->addFixableWarning( self::MESSAGE, $stackPtr, 'PathDetected' );
		if ( $fix ) {
			$this->phpcsFile->fixer->replaceToken( $stackPtr, 'register_block_type_from_metadata' );
		}
	}

	/**
	 * Determine whether a parameter appears to contain a filesystem path.
	 *
	 * Signals, in order of our confidence they indicate a path string:
	 * - __DIR__ or __FILE__ magic constants.
	 * - Call(s) to known path-building function (dirname(), plugin_dir_path(), etc).
	 * - A constant named like a path (FOO_DIR, FOO_PATH, FOO_FILE).
	 * - A string literal containing ".json", a backslash, multiple slashes, a leading
	 *   "/" or leading "." (none of which can appear in valid block names).
	 *
	 * We skip flagging bare variables or single-slash literals to avoid false positives.
	 *
	 * @param array $param Parameter info array from PassedParameters.
	 * @return bool Whether the parameter looks like a path.
	 */
	protected function parameter_looks_like_path( array $param ) {
		$string_literals = [];
		$non_empty_count = 0;

		for ( $i = $param['start']; $i <= $param['end']; $i++ ) {
			$token = $this->tokens[ $i ];

			if ( isset( Tokens::$emptyTokens[ $token['code'] ] ) ) {
				continue;
			}
			$non_empty_count++;

			if ( $token['code'] === T_DIR || $token['code'] === T_FILE ) {
				return true;
			}

			if ( $token['code'] === T_STRING ) {
				$next = $this->phpcsFile->findNext( Tokens::$emptyTokens, $i + 1, $param['end'] + 1, true );
				$is_function_call = $next !== false && $this->tokens[ $next ]['code'] === T_OPEN_PARENTHESIS;

				if ( $is_function_call && in_array( strtolower( $token['content'] ), $this->path_functions, true ) ) {
					return true;
				}

				if ( ! $is_function_call && preg_match( '`_(DIR|PATH|FILE)$`i', $token['content'] ) === 1 ) {
					return true;
				}
			}

			if ( $token['code'] === T_CONSTANT_ENCAPSED_STRING || $token['code'] === T_DOUBLE_QUOTED_STRING ) {
				$string_literals[] = TextStrings::stripQuotes( $token['content'] );
			}
		}

		// The whole parameter is a single string literal.
		if ( $non_empty_count === 1 && count( $string_literals ) === 1 ) {
			$text = $string_literals[0];
			if ( preg_match( self::VALID_BLOCK_NAME, $text ) === 1 ) {
				return false;
			}

			return stripos( $text, '.json' ) !== false
				|| strpos( $text, '\\' ) !== false
				|| substr_count( $text, '/' ) >= 2
				|| ( isset( $text[0] ) && ( $text[0] === '/' || $text[0] === '.' ) );
		}

		/*
		 * String fragments within a larger expression (usually concatenation).
		 * A single-slash fragment such as '/my-block' could be completing a block
		 * name from a namespace prefix, so we look only for unambiguous signals.
		 */
		foreach ( $string_literals as $text ) {
			if ( stripos( $text, '.json' ) !== false || substr_count( $text, '/' ) >= 2 ) {
				return true;
			}
		}

		return false;
	}
}
