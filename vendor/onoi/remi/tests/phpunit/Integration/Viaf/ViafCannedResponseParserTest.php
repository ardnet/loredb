<?php

namespace Onoi\Remi\Tests\Integration\Viaf;

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
class ViafCannedResponseParserTest extends \PHPUnit_Framework_TestCase {

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

		$instance = $filteredHttpResponseParserFactory->newViafFilteredHttpResponseParser(
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
			'253484422',
			$path . '253484422.xml',
			$path . '253484422.expected'
		);

		$provider[] = array(
			'50566653',
			$path . '50566653.xml',
			$path . '50566653.expected'
		);

		$provider[] = array(
			'99929837',
			$path . '99929837.xml',
			$path . '99929837.expected'
		);

		// Corporate
		$provider[] = array(
			'138978451',
			$path . '138978451.xml',
			$path . '138978451.expected'
		);

		return $provider;
	}

}
