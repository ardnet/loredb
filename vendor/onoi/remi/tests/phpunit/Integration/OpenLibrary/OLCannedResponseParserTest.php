<?php

namespace Onoi\Remi\Tests\Integration\OpenLibrary;

use Onoi\Remi\FilteredHttpResponseParserFactory;
use Onoi\Remi\FilteredRecord;

/**
 * @group semantic-cite
 *
 * @license GNU GPL v2+
 * @since 0.1
 *
 * @author mwjames
 */
class OLCannedResponseParserTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider fileProvider
	 */
	public function testParser( $id, $httpRequestFile, $expectedResultFile ) {

		$contents = file_get_contents( $httpRequestFile );

		$expected = unserialize(
			str_replace( "\r\n", "\n", file_get_contents( $expectedResultFile ) )
		);

		$httpRequest = $this->getMockBuilder( '\Onoi\HttpRequest\HttpRequest' )
			->disableOriginalConstructor()
			->getMock();

		$httpRequest->expects( $this->any() )
			->method( 'execute' )
			->will( $this->returnValue( $contents ) );

		$httpRequest->expects( $this->any() )
			->method( 'getLastError' )
			->will( $this->returnValue( '' ) );

		$filteredHttpResponseParserFactory = new FilteredHttpResponseParserFactory(
			$httpRequest
		);

		$instance = $filteredHttpResponseParserFactory->newOLFilteredHttpResponseParser(
			new FilteredRecord()
		);

		$this->assertEquals(
			$contents,
			$instance->getRawResponse( $id )
		);

		$instance->doFilterResponseFor( $id );

		$this->assertEquals(
			$expected,
			$instance->getFilteredRecord()->getRecordFields()
		);
	}

	public function fileProvider() {

		$path = __DIR__ . '/Fixtures/';
		$provider = array();

		$provider[] = array(
			'9780387707037',
			$path . '9780387707037.json',
			$path . '9780387707037.expected'
		);

		$provider[] = array(
			'OL2206423M',
			$path . 'OL2206423M.json',
			$path . 'OL2206423M.expected'
		);

		$provider[] = array(
			'OL10070390M',
			$path . 'OL10070390M.json',
			$path . 'OL10070390M.expected'
		);

		return $provider;
	}

}
