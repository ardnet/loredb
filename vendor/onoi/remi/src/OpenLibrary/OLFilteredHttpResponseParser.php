<?php

namespace Onoi\Remi\OpenLibrary;

use Onoi\Remi\FilteredHttpResponseParser;
use Onoi\Remi\FilteredRecord;

/**
 * @license GNU GPL v2+
 * @since 0.1
 *
 * @author mwjames
 */
class OLFilteredHttpResponseParser extends FilteredHttpResponseParser {

	/**
	 * @see https://openlibrary.org/dev/docs/api/books
	 */
	const OL_REST = "https://openlibrary.org/api/";

	/**
	 * @since 0.1
	 *
	 * {@inheritDoc}
	 */
	public function getRawResponse( $olID ) {
		return $this->requestResponseFor( $olID );
	}

	/**
	 * @since 0.1
	 *
	 * {@inheritDoc}
	 */
	public function doFilterResponseFor( $olID ) {

		$text = $this->requestResponseFor( $olID );

		if ( $this->httpRequest->getLastError() !== '' ) {
			return $this->addMessage( array( 'onoi-remi-request-error', $this->httpRequest->getLastError(), $olID ) );
		}

		$json = json_decode(
			$text,
			true
		);

		if ( $json === null || $json === '' || $json === array() ) {
			return $this->addMessage( array( 'onoi-remi-response-empty', $olID ) );
		}

		$this->doProcessJson( $json );
	}

	private function doProcessJson( $json ) {

		$olBooksAPIJsonProcessor = new OLBooksAPIJsonProcessor(
			$this->filteredRecord
		);

		$olBooksAPIJsonProcessor->doProcess( $json );
	}

	/**
	 * @see https://openlibrary.org/dev/docs/api/books#data
	 *
	 * @param string $id
	 *
	 * @return string
	 */
	private function requestResponseFor( $id ) {

		$this->httpRequest->setOption( CURLOPT_FOLLOWLOCATION, true );

		$this->httpRequest->setOption( CURLOPT_RETURNTRANSFER, true );
		$this->httpRequest->setOption( CURLOPT_FAILONERROR, true );
		$this->httpRequest->setOption( CURLOPT_SSL_VERIFYPEER, false );

		$this->httpRequest->setOption( CURLOPT_HTTPHEADER, array(
			'Accept: application/json',
			'Content-type: application/json; charset=utf-8'
		) );

		$this->httpRequest->setOption(
			CURLOPT_URL,
			self::OL_REST . "books?bibkeys=" . 'OLID:' . $id . ',ISBN:' . $id . '&format=json&jscmd=data' );

		return $this->httpRequest->execute();
	}

}
