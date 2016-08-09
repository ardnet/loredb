<?php

namespace Onoi\Remi\Tests;

use Onoi\Remi\FilteredHttpResponseParser;

/**
 * @covers \Onoi\Remi\FilteredHttpResponseParser
 * @group onoi-remi
 *
 * @license GNU GPL v2+
 * @since   0.1
 *
 * @author mwjames
 */
class FilteredHttpResponseParserTest extends \PHPUnit_Framework_TestCase {

	public function testCanConstruct() {

		$httpRequest = $this->getMockBuilder( '\Onoi\HttpRequest\HttpRequest' )
			->disableOriginalConstructor()
			->getMock();

		$filteredRecord = $this->getMockBuilder( '\Onoi\Remi\FilteredRecord' )
			->disableOriginalConstructor()
			->getMock();

		$instance = $this->getMockBuilder( '\Onoi\Remi\FilteredHttpResponseParser' )
			->setConstructorArgs( array( $httpRequest, $filteredRecord ) )
			->getMockForAbstractClass();

		$this->assertInstanceOf(
			'\Onoi\Remi\FilteredHttpResponseParser',
			$instance
		);
	}

	public function testCommonMethods() {

		$httpRequest = $this->getMockBuilder( '\Onoi\HttpRequest\HttpRequest' )
			->disableOriginalConstructor()
			->getMock();

		$filteredRecord = $this->getMockBuilder( '\Onoi\Remi\FilteredRecord' )
			->disableOriginalConstructor()
			->getMock();

		$instance = $this->getMockBuilder( '\Onoi\Remi\FilteredHttpResponseParser' )
			->setConstructorArgs( array( $httpRequest, $filteredRecord ) )
			->getMockForAbstractClass();

		$this->assertEquals(
			$filteredRecord,
			$instance->getFilteredRecord()
		);

		$this->assertFalse(
			$instance->usesCache()
		);

		$this->assertEmpty(
			$instance->getMessages()
		);
	}

	public function testAddMessage() {

		$httpRequest = $this->getMockBuilder( '\Onoi\HttpRequest\HttpRequest' )
			->disableOriginalConstructor()
			->getMock();

		$filteredRecord = $this->getMockBuilder( '\Onoi\Remi\FilteredRecord' )
			->disableOriginalConstructor()
			->getMock();

		$instance = $this->getMockBuilder( '\Onoi\Remi\FilteredHttpResponseParser' )
			->setConstructorArgs( array( $httpRequest, $filteredRecord ) )
			->getMockForAbstractClass();

		$instance->addMessage( 'foo' );
		$instance->addMessage( array( 42 ) );
		$instance->addMessage( array( 'bar', 0.11 ) );

		$this->assertEquals(
			array(
				'foo',
				array( 42 ),
				array( 'bar', 0.11 )
			),
			$instance->getMessages()
		);
	}

}
