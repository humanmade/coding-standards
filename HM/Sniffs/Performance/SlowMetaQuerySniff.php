<?php
/**
 * WordPress Coding Standard.
 *
 * @package WPCS\WordPressCodingStandards
 * @link    https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace HM\Sniffs\Performance;

// use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use WordPress\AbstractArrayAssignmentRestrictionsSniff;

/**
 * Flag potentially slow queries.
 *
 * @link    https://vip.wordpress.com/documentation/vip-go/code-review-blockers-warnings-notices/#uncached-pageload
 *
 * @package WPCS\WordPressCodingStandards
 *
 * @since   0.3.0
 * @since   0.12.0 Introduced new and more intuitively named 'slow query' whitelist
 *                 comment, replacing the 'tax_query' whitelist comment which is now
 *                 deprecated.
 * @since   0.13.0 Class name changed: this class is now namespaced.
 * @since   1.0.0  This sniff has been moved from the `VIP` category to the `DB` category.
 */
class SlowMetaQuerySniff extends AbstractArrayAssignmentRestrictionsSniff {
	/**
	 * Current stack pointer.
	 *
	 * @var int
	 */
	protected $stackPtr;

	/**
	 * Groups of variables to restrict.
	 *
	 * @return array
	 */
	public function getGroups() {
		return array(
			'slow_query' => array(
				'type'    => 'warning',
				'message' => 'Detected non-performant usage of %s.',
				'keys'    => array(
					'meta_compare',
					'meta_query',
					'meta_key',
					'meta_value',
				),
			),
		);
	}

	/**
	 * Process a token.
	 *
	 * Overrides the parent to store the stackPtr for later use.
	 *
	 * @param int $stackPtr
	 */
	public function process_token( $stackPtr ) {
		$this->stackPtr = $stackPtr;
		parent::process_token( $stackPtr );
		unset( $this->stackPtr );
	}

	/**
	 * Callback to process each confirmed key, to check value.
	 * This must be extended to add the logic to check assignment value.
	 *
	 * @param  string $key   Array index / key.
	 * @param  mixed  $val   Assigned value.
	 * @param  int    $line  Token line.
	 * @param  array  $group Group definition.
	 * @return mixed         FALSE if no match, TRUE if matches, STRING if matches
	 *                       with custom error message passed to ->process().
	 */
	public function callback( $key, $val, $line, $group ) {
		switch ( $key ) {
			case 'meta_value':
				// When meta_value is specified, the query operates on the value,
				// and is hence expensive. (UNLESS: meta_compare is set)
				return true;

			case 'meta_query':
				return $this->check_meta_query();

			default:
				// Unknown key, assume it's an error.
				return true;
		}
	}

	protected function check_meta_query() {
		// Grab the token we're detecting.
		$token = $this->tokens[ $this->stackPtr ];
		if ( $token['code'] !== T_DOUBLE_ARROW ) {
			var_dump( $token );
			exit;
		}

		// Get the array's bounds, then grab the indices.
		$array_open = $this->phpcsFile->findNext( array_merge( Tokens::$emptyTokens, [ T_COMMA, T_CLOSE_SHORT_ARRAY ] ), $this->stackPtr + 1, null, true );
		$array_open_token = $this->tokens[ $array_open ];
		if ( $array_open_token['code'] !== T_ARRAY && $array_open_token['code'] !== T_OPEN_SHORT_ARRAY ) {
			// Dynamic value, we can't check.
			return true;
		}

		$array_bounds = $this->find_array_open_close( $array_open );
		$elements = $this->get_array_indices( $array_bounds['opener'], $array_bounds['closer'] );

		$default_compare = $this->get_static_value_from_array( $elements, 'compare' );
		if ( empty( $default_compare ) ) {
			// The default is either IN or = depending on whether value is
			// set, but this only matters for the message.
			$default_compare = 'default';
		}

		foreach ( $elements as $element ) {
			// Is this a named index?
			if ( isset( $element['index_start'] ) ) {
				// Skip it, already handled above.
				continue;
			}

			// Numeric index, so this is a specific comparison. Explore the array.
			$value_token = $this->tokens[ $element['value_start'] ];
			if ( $value_token['code'] !== T_ARRAY && $value_token['code'] !== T_OPEN_SHORT_ARRAY ) {
				// Invalid item in meta query.
				continue;
			}

			$value_bounds = $this->find_array_open_close( $element['value_start'] );
			$value_elements = $this->get_array_indices( $value_bounds['opener'], $value_bounds['closer'] );
			$compare = $this->get_static_value_from_array( $value_elements, 'compare' );
			if ( empty( $compare ) ) {
				$compare = $default_compare;
			}

			if ( $compare !== 'EXISTS' && $compare !== 'NOT EXISTS' ) {
				// Add a message ourselves.
				$this->addMessage(
					'meta_query is using %s comparison, which is non-performant.',
					$this->stackPtr,
					'warning',
					'nonperformant_comparison',
					[ $compare ]
				);
			}
		}

		// Disable the built-in warnings.
		return false;
	}

	/**
	 * Get a static value from an array.
	 *
	 * @param array $elements Elements from the array (from get_array_indices())
	 * @param string $array_key Key to find in the array.
	 * @return string|null Static value if available, null otherwise.
	 */
	protected function get_static_value_from_array( array $elements, string $array_key ) : ?string {
		$element = $this->find_key_in_array( $elements, $array_key );
		if ( empty( $elements ) ) {
			return null;
		}

		// Got the compare, grab the value.
		$value_start = $element['value_start'];
		if ( $this->tokens[ $value_start ]['code'] !== T_CONSTANT_ENCAPSED_STRING ) {
			// Dynamic value, unknown.
			return null;
		}

		$maybe_value_end = $this->phpcsFile->findNext( Tokens::$emptyTokens, $value_start + 1, null, true );
		if ( $this->tokens[ $maybe_value_end ]['code'] !== T_COMMA ) {
			// Dynamic value, unknown.
			var_dump( 'invalid value, error' );
			return null;
		}

		return $this->strip_quotes( $this->tokens[ $value_start ]['content'] );
	}

	/**
	 * Find a given key in an array.
	 *
	 * Searches a list of elements for a given (static) index.
	 *
	 * @param array $elements Elements from the array (from get_array_indices())
	 * @param string $array_key Key to find in the array.
	 * @return string|null Static value if available, null otherwise.
	 */
	protected function find_key_in_array( array $elements, string $array_key ) : ?array {
		foreach ( $elements as $element ) {
			if ( ! isset( $element['index_start'] ) ) {
				// Numeric item, skip.
				continue;
			}

			// Ensure the index is a static string first.
			$start = $element['index_start'];
			if ( $this->tokens[ $start ]['code'] !== T_CONSTANT_ENCAPSED_STRING ) {
				// Dynamic key.
				continue;
			}

			$maybe_index_end = $this->phpcsFile->findNext( Tokens::$emptyTokens, $start + 1, null, true );
			if ( $this->tokens[ $maybe_index_end ]['code'] !== T_DOUBLE_ARROW ) {
				// Dynamic key, maybe? This is probably not valid syntax.
				exit;
			}

			$index = $this->strip_quotes( $this->tokens[ $start ]['content'] );
			if ( $index !== $array_key ) {
				// Not the item we want, skip.
				continue;
			}

			return $element;
		}

		return null;
	}

	/**
	 * Get array indices information.
	 *
	 * @internal From phpcs' AbstractArraySniff::get_array_indices
	 *
	 * @param integer $array_start
	 * @param integer $array_end
	 * @return array
	 */
	protected function get_array_indices( int $array_start, int $array_end ) : array {
		$indices = [];

		$current = $array_start;
		while ( ( $next = $this->phpcsFile->findNext( Tokens::$emptyTokens, ( $current + 1 ), $array_end, true ) ) !== false ) {
			$end = $this->get_next( $this->phpcsFile, $next, $array_end );

			if ( $this->tokens[ $end ]['code'] === T_DOUBLE_ARROW ) {
				$indexEnd = $this->phpcsFile->findPrevious( T_WHITESPACE, $end - 1, null, true );
				$value_start = $this->phpcsFile->findNext( Tokens::$emptyTokens, $end + 1, null, true);

				$indices[] = [
					'index_start' => $next,
					'index_end' => $indexEnd,
					'arrow' => $end,
					'value_start' => $value_start,
				];
			} else {
				$value_start = $next;
				$indices[] = [
					'value_start' => $value_start,
				];
			}

			$current = $this->get_next( $this->phpcsFile, $value_start, $array_end );
		}

		return $indices;
	}

	/**
	 * Find next separator in array - either: comma or double arrow.
	 *
	 * @internal From phpcs' AbstractArraySniff::getNext
	 *
	 * @param File $phpcsFile The current file being checked.
	 * @param int $ptr The position of current token.
	 * @param int $arrayEnd The token that ends the array definition.
	 *
	 * @return int
	 */
	protected function get_next( File $phpcsFile, $ptr, $arrayEnd ) {
		$tokens = $phpcsFile->getTokens();

		while ($ptr < $arrayEnd) {
			if (isset($tokens[$ptr]['scope_closer']) === true) {
				$ptr = $tokens[$ptr]['scope_closer'];
			} else if (isset($tokens[$ptr]['parenthesis_closer']) === true) {
				$ptr = $tokens[$ptr]['parenthesis_closer'];
			} else if (isset($tokens[$ptr]['bracket_closer']) === true) {
				$ptr = $tokens[$ptr]['bracket_closer'];
			}

			if ($tokens[$ptr]['code'] === T_COMMA
				|| $tokens[$ptr]['code'] === T_DOUBLE_ARROW
			) {
				return $ptr;
			}

			++$ptr;
		}

		return $ptr;
	}
}
