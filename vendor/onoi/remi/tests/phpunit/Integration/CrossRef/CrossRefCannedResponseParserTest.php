<?php

namespace Onoi\Remi\Tests\Integration\CrossRef;

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
class CrossRefCannedResponseParserTest extends \PHPUnit_Framework_TestCase {

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

		$instance = $filteredHttpResponseParserFactory->newCrossRefFilteredHttpResponseParser(
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
			'10.1007/978-0-387-76978-3',
			$path . '10.1007-2F978-0-387-76978-3.json',
			$path . '10.1007-2F978-0-387-76978-3.expected'
		);

		$provider[] = array(
			'10.5555/12345678',
			$path . '10.5555-2F12345678.json',
			$path . '10.5555-2F12345678.expected'
		);


		$provider[] = array(
			'10.1126/science.1152662',
			$path . '10.1126-2Fscience.1152662.json',
			$path . '10.1126-2Fscience.1152662.expected'
		);

		// Dataset
		$provider[] = array(
			'10.5524/100005',
			$path . '10.5524-2F100005.json',
			$path . '10.5524-2F100005.expected'
		);

		return $provider;
	}

}
