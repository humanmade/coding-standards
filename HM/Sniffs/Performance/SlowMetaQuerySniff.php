<?php

namespace HM\Sniffs\Performance;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use WordPressCS\WordPress\AbstractArrayAssignmentRestrictionsSniff;

/**
 * Flag slow meta queries.
 */
class SlowMetaQuerySniff extends AbstractArrayAssignmentRestrictionsSniff {
	/**
	 * Indicates a dynamic value.
	 */
	const DYNAMIC_VALUE = '__dynamic';

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
				'message' => 'Querying by %s is not performant.',
				'keys'    => array(
					'meta_query',
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

	/**
	 * Recursively check a meta_query value.
	 */
	protected function check_meta_query() {
		// Grab the token we're detecting.
		$token = $this->tokens[ $this->stackPtr ];

		// Find the value of meta_query, and check it.
		$array_open = $this->phpcsFile->findNext( array_merge( Tokens::$emptyTokens, [ T_COMMA, T_CLOSE_SHORT_ARRAY ] ), $this->stackPtr + 1, null, true );
		$this->check_meta_query_item( $array_open );

		// Disable the built-in warnings.
		return false;
	}

	/**
	 * Check an individual meta_query item.
	 *
	 * @param int $array_open Token pointer for the array open token.
	 */
	protected function check_meta_query_item( int $array_open ) {
		$array_open_token = $this->tokens[ $array_open ];
		if ( $array_open_token['code'] !== T_ARRAY && $array_open_token['code'] !== T_OPEN_SHORT_ARRAY ) {
			// Dynamic value, we can't check.
			$this->addMessage(
				'meta_query is dynamic, cannot be checked.',
				$array_open,
				'warning',
				'dynamic_query'
			);

			return;
		}

		$array_bounds = $this->find_array_open_close( $array_open );
		$elements = $this->get_array_indices( $array_bounds['opener'], $array_bounds['closer'] );

		// Is this a "first-order" query?
		// @see WP_Meta_Query::is_first_order_clause
		$first_order_key = $this->find_key_in_array( $elements, 'key' );
		$first_order_value = $this->find_key_in_array( $elements, 'value' );
		if ( $first_order_key || $first_order_value  ) {
			$compare_element = $this->find_key_in_array( $elements, 'compare' );
			if ( ! empty( $compare_element ) ) {
				$compare = $this->get_static_value_for_element( $compare_element );
			}
			if ( empty( $compare ) ) {
				// The default is either IN or = depending on whether value is
				// set, but this only matters for the message.
				$compare = 'default';
			}

			$this->check_compare_value( $compare, $compare_element ? $compare_element['value_start'] : null );
			return;
		}

		foreach ( $elements as $element ) {
			if ( isset( $element['index_start'] ) ) {
				$index = $this->strip_quotes( $this->tokens[ $element['index_start'] ]['content'] );
				if ( strtolower( $index ) === 'relation' ) {
					// Skip 'relation' element.
					continue;
				}
			}

			// Otherwise, recurse.
			$this->check_meta_query_item( $element['value_start'] );
		}
	}

	/**
	 * Get a static value from an array.
	 *
	 * @param array $elements Elements from the array (from get_array_indices())
	 * @param string $array_key Key to find in the array.
	 * @return string|null Static value if available, null otherwise.
	 */
	protected function get_static_value_for_element( array $element ) : ?string {
		// Got the compare, grab the value.
		$value_start = $element['value_start'];
		if ( $this->tokens[ $value_start ]['code'] !== T_CONSTANT_ENCAPSED_STRING ) {
			// Dynamic value.
			return static::DYNAMIC_VALUE;
		}

		$maybe_value_end = $this->phpcsFile->findNext( Tokens::$emptyTokens, $value_start + 1, null, true );
		$expected_next = [
			T_CLOSE_PARENTHESIS,
			T_CLOSE_SHORT_ARRAY,
			T_COMMA,
		];
		if ( ! in_array( $this->tokens[ $maybe_value_end ]['code'], $expected_next, true ) ) {
			// Dynamic value.
			return static::DYNAMIC_VALUE;
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
				continue;
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
	 * Add an error if the comparison isn't allowed.
	 *
	 * @param string $compare Comparison value
	 */
	protected function check_compare_value( string $compare, int $stackPtr = null ) : void {
		if ( empty( $stackPtr ) ) {
			$stackPtr = $this->stackPtr;
		}

		if ( $compare === static::DYNAMIC_VALUE ) {
			$this->addMessage(
				'meta_query is using a dynamic comparison; this cannot be checked automatically, and may be non-performant.',
				$stackPtr,
				'warning',
				'dynamic_compare'
			);
		} elseif ( $compare !== 'EXISTS' && $compare !== 'NOT EXISTS' ) {
			// Add a message ourselves.
			$this->addMessage(
				'meta_query is using %s comparison, which is non-performant.',
				$stackPtr,
				'warning',
				'nonperformant_comparison',
				[ $compare ]
			);
		}
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

		while ( $ptr < $arrayEnd ) {
			if ( isset( $tokens[ $ptr ]['scope_closer']) === true ) {
				$ptr = $tokens[ $ptr ]['scope_closer'];
			} elseif ( isset( $tokens[ $ptr ]['parenthesis_closer'] ) === true ) {
				$ptr = $tokens[ $ptr ]['parenthesis_closer'];
			} elseif ( isset( $tokens[ $ptr ]['bracket_closer'] ) === true ) {
				$ptr = $tokens[ $ptr ]['bracket_closer'];
			}

			if ( $tokens[ $ptr ]['code'] === T_COMMA || $tokens[ $ptr ]['code'] === T_DOUBLE_ARROW ) {
				return $ptr;
			}

			++$ptr;
		}

		return $ptr;
	}
}
