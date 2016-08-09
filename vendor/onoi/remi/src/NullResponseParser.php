<?php

namespace Onoi\Remi;

/**
 * @license GNU GPL v2+
 * @since 0.1
 *
 * @author mwjames
 */
class NullResponseParser implements ResponseParser {

	/**
	 * @var FilteredRecord
	 */
	private $filteredRecord;

	/**
	 * @since 0.1
	 *
	 * @param FilteredRecord $filteredRecord
	 */
	public function __construct( FilteredRecord $filteredRecord ) {
		$this->filteredRecord = $filteredRecord;
	}

	/**
	 * @since 0.1
	 *
	 * {@inheritDoc}
	 */
	public function getFilteredRecord() {
		return $this->filteredRecord;
	}

	/**
	 * @since 0.1
	 *
	 * {@inheritDoc}
	 */
	public function getMessages() {
		return array();
	}

	/**
	 * @since 0.1
	 *
	 * {@inheritDoc}
	 */
	public function usesCache() {
		return false;
	}

	/**
	 * @since 0.1
	 *
	 * {@inheritDoc}
	 */
	public function doFilterResponseFor( $query ) {}

	/**
	 * @since 0.1
	 *
	 * {@inheritDoc}
	 */
	public function getRawResponse( $query ) {
		return '';
	}

}
