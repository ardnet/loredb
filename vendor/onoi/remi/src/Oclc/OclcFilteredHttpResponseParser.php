<?php

namespace Onoi\Remi\Oclc;

use Onoi\Remi\FilteredHttpResponseParser;
use Onoi\Remi\FilteredRecord;

/**
 * @license GNU GPL v2+
 * @since 0.1
 *
 * @author mwjames
 */
class OclcFilteredHttpResponseParser extends FilteredHttpResponseParser {

	/**
	 * @see http://dataliberate.com/2013/06/content-negotiation-for-worldcat/
	 */
	const OCLC_REST = "http://www.worldcat.org/oclc/";

	/**
	 * @since 0.1
	 *
	 * {@inheritDoc}
	 */
	public function getRawResponse( $oclcID ) {
		return $this->requestResponseFor( $oclcID );
	}

	/**
	 * @since 0.1
	 *
	 * {@inheritDoc}
	 */
	public function doFilterResponseFor( $oclcID ) {

		$text = $this->requestResponseFor( $oclcID );

		if ( $this->httpRequest->getLastError() !== '' ) {
			return $this->addMessage( array( 'onoi-remi-request-error', $this->httpRequest->getLastError(), $oclcID ) );
		}

		$jsonld = json_decode( $text, true );

		if ( $jsonld === null || $jsonld === '' ) {
			return $this->addMessage( array( 'onoi-remi-response-empty', $oclcID ) );
		}

		$this->doProcessJsonLd( $oclcID, $jsonld );
	}

	private function doProcessJsonLd( $oclcID, $jsonld ) {

		$simpleOclcJsonLdGraphProcessor = new SimpleOclcJsonLdGraphProcessor(
			$this->filteredRecord
		);

		$simpleOclcJsonLdGraphProcessor->doProcess( $oclcID, $jsonld );
	}

	/**
	 * @param string $id
	 *
	 * @return string
	 */
	public function requestResponseFor( $id ) {

		$this->httpRequest->setOption( CURLOPT_FOLLOWLOCATION, true );

		$this->httpRequest->setOption( CURLOPT_RETURNTRANSFER, true );
		$this->httpRequest->setOption( CURLOPT_FAILONERROR, true );
		$this->httpRequest->setOption( CURLOPT_URL, self::OCLC_REST . $id );

		$this->httpRequest->setOption( CURLOPT_HTTPHEADER, array(
			'Accept: application/ld+json',
			'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'
		) );

		return $this->httpRequest->execute();
	}

}
