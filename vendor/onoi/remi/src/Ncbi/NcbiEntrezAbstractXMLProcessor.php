<?php

namespace Onoi\Remi\Ncbi;

use Onoi\Remi\FilteredRecord;
use DOMDocument;

/**
 * @license GNU GPL v2+
 * @since 0.1
 *
 * @author mwjames
 */
class NcbiEntrezAbstractXMLProcessor {

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
	 * @param string $xml
	 */
	public function doProcess( $xml ) {

		$domDocument = new DOMDocument();
		$domDocument->loadXml( $xml );

		$this->findElementsForPubMed( $domDocument );
		$this->findElementsForPubMedCentral( $domDocument );
	}

	private function findElementsForPubMed( DOMDocument $domDocument ) {

		foreach ( $domDocument->getElementsByTagName( 'PubDate' ) as $item ) {
			foreach ( $item->getElementsByTagName( 'Year' ) as $i ) {
				$this->filteredRecord->set( 'year', $i->nodeValue );
			}
		}

		foreach ( $domDocument->getElementsByTagName( 'Abstract' ) as $item ) {
			$this->filteredRecord->set( 'abstract', preg_replace( '#\s{2,}#', ' ', trim( $item->nodeValue ) ) );
		}

		foreach ( $domDocument->getElementsByTagName( 'MeshHeading' ) as $item ) {
			$this->filteredRecord->append( 'subject', preg_replace( '#\s{2,}#', ' ', trim( $item->nodeValue ) ) );
		}
	}

	private function findElementsForPubMedCentral( DOMDocument $domDocument ) {

		foreach ( $domDocument->getElementsByTagName( 'pub-date' ) as $item ) {
			foreach ( $item->getElementsByTagName( 'year' ) as $i ) {
				$this->filteredRecord->set( 'year', $i->nodeValue );
			}
		}

		foreach ( $domDocument->getElementsByTagName( 'abstract' ) as $item ) {
			$this->filteredRecord->set( 'abstract', trim( $item->nodeValue ) );
		}

		foreach ( $domDocument->getElementsByTagName( 'article' ) as $item ) {
			$this->filteredRecord->set( 'type', $item->getAttribute( 'article-type' ) );
		}
	}

}
