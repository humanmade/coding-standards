<?php

namespace HM\Sniffs\Performance;

use WordPressCS\WordPress\AbstractArrayAssignmentRestrictionsSniff;

/**
 * Flag slow orderby usage in WP queries.
 */
class SlowOrderBySniff extends AbstractArrayAssignmentRestrictionsSniff {
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
			'slow_order' => array(
				'type'    => 'warning',
				'message' => 'Ordering query results by %s is not performant.',
				'keys'    => array(
					'orderby',
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
		switch ( $val ) {
			case 'rand':
			case 'meta_value':
			case 'meta_value_num':
				$this->addMessage(
					'Ordering query results by %s is not performant.',
					$this->stackPtr,
					'warning',
					'slow_order',
					[ $val ]
				);

				// Skip built-in message.
				return false;

			default:
				// No match.
				return false;
		}
	}
}
