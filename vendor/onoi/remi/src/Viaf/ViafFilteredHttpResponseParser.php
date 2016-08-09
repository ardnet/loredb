<?php

namespace Onoi\Remi\Viaf;

use Onoi\Remi\FilteredHttpResponseParser;
use Onoi\Remi\FilteredRecord;
use DOMDocument;

/**
 * @license GNU GPL v2+
 * @since 0.1
 *
 * @author mwjames
 */
class ViafFilteredHttpResponseParser extends FilteredHttpResponseParser {

	/**
	 * @see http://crosscite.org/cn/
	 */
	const VIAF_REST = "http://viaf.org/viaf/";

	/**
	 * @since 0.1
	 *
	 * {@inheritDoc}
	 */
	public function getRawResponse( $viafID ) {
		return $this->requestResponseFor( $viafID );
	}

	/**
	 * @since 0.1
	 *
	 * {@inheritDoc}
	 */
	public function doFilterResponseFor( $viafID ) {

		$xml = $this->requestResponseFor( $viafID );

		if ( $this->httpRequest->getLastError() !== '' ) {
			return $this->addMessage( array( 'onoi-remi-request-error', $this->httpRequest->getLastError(), $viafID ) );
		}

		if ( $xml === null || $xml === '' || $xml === false ) {
			return $this->addMessage( array( 'onoi-remi-response-empty', $viafID ) );
		}

		$dom = new DOMDocument();
		$dom->loadXml( $xml );

		$this->doProcessDom( $dom, $viafID );
	}

	private function doProcessDom( $dom, $viafID ) {

		$viaf = '';

		foreach ( $dom->getElementsByTagName( 'viafID' ) as $item ) {
			$viaf = $item->nodeValue;
		}

		if ( $viaf != $viafID ) {
			return $this->addMessage( array( 'onoi-remi-parser-no-id-match', $viafID ) );
		}

		$this->filteredRecord->set( 'viaf', $viafID );

		foreach ( $dom->getElementsByTagName( 'nameType' ) as $item ) {
			$this->filteredRecord->set( 'type', $item->nodeValue );
		}

		foreach ( $dom->getElementsByTagName( 'sources' ) as $item ) {
			foreach ( $item->getElementsByTagName( 'source' ) as $i ) {
				list( $key, $value ) = explode( '|', $i->nodeValue, 2 );
				$this->filteredRecord->set( strtolower( $key ), str_replace( ' ' ,'', $value ) );
			}
		}

		// Not sure what we want to search/iterate for therefore stop after the
		// first data/name element
		foreach ( $dom->getElementsByTagName( 'data' ) as $item ) {

			foreach ( $item->getElementsByTagName( 'text' ) as $i ) {
				$this->filteredRecord->set( 'name', str_replace( '.', '', $i->nodeValue ) );
				break;
			}

			break;
		}
	}

	/**
	 * @param string $id
	 *
	 * @return string
	 */
	private function requestResponseFor( $id ) {

		$this->httpRequest->setOption( CURLOPT_FOLLOWLOCATION, true );

		$this->httpRequest->setOption( CURLOPT_RETURNTRANSFER, true );
		$this->httpRequest->setOption( CURLOPT_FAILONERROR, true );
		$this->httpRequest->setOption( CURLOPT_URL, self::VIAF_REST . $id );

		$this->httpRequest->setOption( CURLOPT_HTTPHEADER, array(
			'Accept: application/xml',
			'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'
		) );

		return $this->httpRequest->execute();
	}

}
