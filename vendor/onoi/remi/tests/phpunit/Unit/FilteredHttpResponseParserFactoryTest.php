<?php

namespace Onoi\Remi\Tests;

use Onoi\Remi\FilteredHttpResponseParserFactory;

/**
 * @covers \Onoi\Remi\FilteredHttpResponseParserFactory
 * @group onoi-remi
 *
 * @license GNU GPL v2+
 * @since   0.1
 *
 * @author mwjames
 */
class FilteredHttpResponseParserFactoryTest extends \PHPUnit_Framework_TestCase {

	public function testCanConstruct() {

		$httpRequest = $this->getMockBuilder( '\Onoi\HttpRequest\HttpRequest' )
			->disableOriginalConstructor()
			->getMock();

		$this->assertInstanceOf(
			'\Onoi\Remi\FilteredHttpResponseParserFactory',
			new FilteredHttpResponseParserFactory( $httpRequest )
		);
	}

	public function testCanConstructCrossRefFilteredHttpResponseParser() {

		$httpRequest = $this->getMockBuilder( '\Onoi\HttpRequest\HttpRequest' )
			->disableOriginalConstructor()
			->getMock();

		$filteredRecord = $this->getMockBuilder( '\Onoi\Remi\FilteredRecord' )
			->disableOriginalConstructor()
			->getMock();

		$instance = new FilteredHttpResponseParserFactory( $httpRequest );

		$this->assertInstanceOf(
			'\Onoi\Remi\CrossRef\CrossRefFilteredHttpResponseParser',
			$instance->newCrossRefFilteredHttpResponseParser( $filteredRecord )
		);
	}

	public function testCanConstructViafFilteredHttpResponseParser() {

		$httpRequest = $this->getMockBuilder( '\Onoi\HttpRequest\HttpRequest' )
			->disableOriginalConstructor()
			->getMock();

		$filteredRecord = $this->getMockBuilder( '\Onoi\Remi\FilteredRecord' )
			->disableOriginalConstructor()
			->getMock();

		$instance = new FilteredHttpResponseParserFactory( $httpRequest );

		$this->assertInstanceOf(
			'\Onoi\Remi\Viaf\ViafFilteredHttpResponseParser',
			$instance->newViafFilteredHttpResponseParser( $filteredRecord )
		);
	}

	public function testCanConstructOclcFilteredHttpResponseParser() {

		$httpRequest = $this->getMockBuilder( '\Onoi\HttpRequest\HttpRequest' )
			->disableOriginalConstructor()
			->getMock();

		$filteredRecord = $this->getMockBuilder( '\Onoi\Remi\FilteredRecord' )
			->disableOriginalConstructor()
			->getMock();

		$instance = new FilteredHttpResponseParserFactory( $httpRequest );

		$this->assertInstanceOf(
			'\Onoi\Remi\Oclc\OclcFilteredHttpResponseParser',
			$instance->newOclcFilteredHttpResponseParser( $filteredRecord )
		);
	}

	public function testCanConstructPubMedFilteredHttpResponseParser() {

		$httpRequest = $this->getMockBuilder( '\Onoi\HttpRequest\HttpRequest' )
			->disableOriginalConstructor()
			->getMock();

		$filteredRecord = $this->getMockBuilder( '\Onoi\Remi\FilteredRecord' )
			->disableOriginalConstructor()
			->getMock();

		$instance = new FilteredHttpResponseParserFactory( $httpRequest );

		$this->assertInstanceOf(
			'\Onoi\Remi\Ncbi\NcbiPubMedFilteredHttpResponseParser',
			$instance->newNcbiPubMedFilteredHttpResponseParser( $filteredRecord )
		);
	}

	public function testCanConstructPubMedCentralFilteredHttpResponseParser() {

		$httpRequest = $this->getMockBuilder( '\Onoi\HttpRequest\HttpRequest' )
			->disableOriginalConstructor()
			->getMock();

		$filteredRecord = $this->getMockBuilder( '\Onoi\Remi\FilteredRecord' )
			->disableOriginalConstructor()
			->getMock();

		$instance = new FilteredHttpResponseParserFactory( $httpRequest );

		$this->assertInstanceOf(
			'\Onoi\Remi\Ncbi\NcbiPubMedFilteredHttpResponseParser',
			$instance->newNcbiPubMedCentralFilteredHttpResponseParser( $filteredRecord )
		);
	}

	public function testCanConstructOLFilteredHttpResponseParser() {

		$httpRequest = $this->getMockBuilder( '\Onoi\HttpRequest\HttpRequest' )
			->disableOriginalConstructor()
			->getMock();

		$filteredRecord = $this->getMockBuilder( '\Onoi\Remi\FilteredRecord' )
			->disableOriginalConstructor()
			->getMock();

		$instance = new FilteredHttpResponseParserFactory( $httpRequest );

		$this->assertInstanceOf(
			'\Onoi\Remi\OpenLibrary\OLFilteredHttpResponseParser',
			$instance->newOLFilteredHttpResponseParser( $filteredRecord )
		);
	}

	public function testCanConstructNullResponseParser() {

		$httpRequest = $this->getMockBuilder( '\Onoi\HttpRequest\HttpRequest' )
			->disableOriginalConstructor()
			->getMock();

		$filteredRecord = $this->getMockBuilder( '\Onoi\Remi\FilteredRecord' )
			->disableOriginalConstructor()
			->getMock();

		$instance = new FilteredHttpResponseParserFactory( $httpRequest );

		$this->assertInstanceOf(
			'\Onoi\Remi\NullResponseParser',
			$instance->newNullResponseParser( $filteredRecord )
		);
	}

}
