<?php

namespace Onoi\Remi;

use Onoi\HttpRequest\HttpRequest;

/**
 * @license GNU GPL v2+
 * @since 0.1
 *
 * @author mwjames
 */
abstract class FilteredHttpResponseParser implements ResponseParser {

	/**
	 * @var HttpRequest
	 */
	protected $httpRequest;

	/**
	 * @var FilteredRecord
	 */
	protected $filteredRecord;

	/**
	 * @var array
	 */
	private $messages = array();

	/**
	 * @since 0.1
	 *
	 * @param HttpRequest $httpRequest
	 * @param FilteredRecord $filteredRecord
	 */
	public function __construct( HttpRequest $httpRequest, FilteredRecord $filteredRecord ) {
		$this->httpRequest = $httpRequest;
		$this->filteredRecord = $filteredRecord;
	}

	/**
	 * @since 0.1
	 *
	 * @param string[] $message
	 */
	public function addMessage( $message ) {
		$this->messages[] = $message;
	}

	/**
	 * @since 0.1
	 *
	 * {@inheritDoc}
	 */
	public function getMessages() {
		return $this->messages;
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
	public function usesCache() {
		return method_exists( $this->httpRequest, 'isCached' ) ? $this->httpRequest->isCached() : false;
	}

	/**
	 * @since 0.1
	 *
	 * {@inheritDoc}
	 */
	abstract public function doFilterResponseFor( $query );

	/**
	 * @since 0.1
	 *
	 * {@inheritDoc}
	 */
	abstract public function getRawResponse( $query );

}
