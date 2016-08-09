<?php

namespace Onoi\Remi\CrossRef;

use Onoi\Remi\FilteredHttpResponseParser;
use Onoi\Remi\FilteredRecord;

/**
 * @license GNU GPL v2+
 * @since 0.1
 *
 * @author mwjames
 */
class CrossRefFilteredHttpResponseParser extends FilteredHttpResponseParser {

	/**
	 * @see http://crosscite.org/cn/
	 */
	const CROSSREF_CONTENT_API = "https://dx.doi.org/";

	/**
	 * @since 0.1
	 *
	 * {@inheritDoc}
	 */
	public function getRawResponse( $doi ) {
		return $this->requestResponseFor( $doi );
	}

	/**
	 * @since 0.1
	 *
	 * {@inheritDoc}
	 */
	public function doFilterResponseFor( $doi ) {

		$json = json_decode(
			$this->requestResponseFor( $doi ),
			true
		);

		if ( $this->httpRequest->getLastError() !== '' ) {
			return $this->addMessage( array( 'onoi-remi-request-error', $this->httpRequest->getLastError(), $doi ) );
		}

		if ( $json === null || $json === array() ) {
			return $this->addMessage( array( 'onoi-remi-response-empty', $doi ) );
		}

		$this->doProcessCiteproc( $json );
	}

	private function doProcessCiteproc( $json ) {

		$crossRefCiteprocJsonProcessor = new CrossRefCiteprocJsonProcessor(
			$this->getFilteredRecord()
		);

		$crossRefCiteprocJsonProcessor->doProcess( $json );
	}

	/**
	 * @param string $doi
	 *
	 * @return string
	 */
	private function requestResponseFor( $doi ) {

		$this->httpRequest->setOption( CURLOPT_FOLLOWLOCATION, true );

		$this->httpRequest->setOption( CURLOPT_RETURNTRANSFER, true );
		$this->httpRequest->setOption( CURLOPT_FAILONERROR, true );
		$this->httpRequest->setOption( CURLOPT_SSL_VERIFYPEER, false );

		$this->httpRequest->setOption( CURLOPT_URL, self::CROSSREF_CONTENT_API . $doi );

		$this->httpRequest->setOption( CURLOPT_HTTPHEADER, array(
			'Accept: application/vnd.citationstyles.csl+json',
			'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'
		) );

		return $this->httpRequest->execute();
	}

}
