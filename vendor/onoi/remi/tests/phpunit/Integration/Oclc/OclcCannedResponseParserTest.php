<?php

namespace Onoi\Remi\Tests\Integration\Oclc;

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
class OclcCannedResponseParserTest extends \PHPUnit_Framework_TestCase {

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

		$instance = $filteredHttpResponseParserFactory->newOclcFilteredHttpResponseParser(
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
			'2776723',
			$path . '2776723.json',
			$path . '2776723.expected'
		);

		$provider[] = array(
			'24880509',
			$path . '24880509.json',
			$path . '24880509.expected'
		);

		$provider[] = array(
			'31754096',
			$path . '31754096.json',
			$path . '31754096.expected'
		);

		$provider[] = array(
			'41266045',
			$path . '41266045.json',
			$path . '41266045.expected'
		);

		$provider[] = array(
			'74330434',
			$path . '74330434.json',
			$path . '74330434.expected'
		);

		return $provider;
	}

}
