<?php

namespace Onoi\Remi\Tests\CrossRef;

use Onoi\Remi\CrossRef\CrossRefFilteredHttpResponseParser;

/**
 * @covers \Onoi\Remi\CrossRef\CrossRefFilteredHttpResponseParser
 * @group onoi-remi
 *
 * @license GNU GPL v2+
 * @since   0.1
 *
 * @author mwjames
 */
class CrossRefFilteredHttpResponseParserTest extends \PHPUnit_Framework_TestCase {

	public function testCanConstruct() {

		$httpRequest = $this->getMockBuilder( '\Onoi\HttpRequest\HttpRequest' )
			->disableOriginalConstructor()
			->getMock();

		$filteredRecord = $this->getMockBuilder( '\Onoi\Remi\FilteredRecord' )
			->disableOriginalConstructor()
			->getMock();

		$this->assertInstanceOf(
			'\Onoi\Remi\CrossRef\CrossRefFilteredHttpResponseParser',
			new CrossRefFilteredHttpResponseParser( $httpRequest, $filteredRecord )
		);
	}

	public function testFailedResponse() {

		$httpRequest = $this->getMockBuilder( '\Onoi\HttpRequest\HttpRequest' )
			->disableOriginalConstructor()
			->getMock();

		$httpRequest->expects( $this->atLeastOnce() )
			->method( 'getLastError' )
			->will( $this->returnValue( 'error' ) );

		$filteredRecord = $this->getMockBuilder( '\Onoi\Remi\FilteredRecord' )
			->disableOriginalConstructor()
			->getMock();

		$instance = new CrossRefFilteredHttpResponseParser(
			$httpRequest,
			$filteredRecord
		);

		$instance->doFilterResponseFor( 'foo' );

		$this->assertEquals(
			array( array(
				'onoi-remi-request-error',
				'error',
				'foo'
			) ),
			$instance->getMessages()
		);
	}

	public function testNullResponse() {

		$httpRequest = $this->getMockBuilder( '\Onoi\HttpRequest\HttpRequest' )
			->disableOriginalConstructor()
			->getMock();

		$httpRequest->expects( $this->atLeastOnce() )
			->method( 'getLastError' )
			->will( $this->returnValue( '' ) );

		$filteredRecord = $this->getMockBuilder( '\Onoi\Remi\FilteredRecord' )
			->disableOriginalConstructor()
			->getMock();

		$instance = new CrossRefFilteredHttpResponseParser(
			$httpRequest,
			$filteredRecord
		);

		$instance->doFilterResponseFor( 'foo' );

		$this->assertEquals(
			array( array(
				'onoi-remi-response-empty',
				'foo'
			) ),
			$instance->getMessages()
		);
	}

}
