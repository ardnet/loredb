<?php

namespace Onoi\Remi\Tests\Integration\Ncbi;

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
class PubMedCannedResponseParserTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider pmidFileProvider
	 */
	public function testPMIDParser( $id, $httpJsonRequestFile, $httpXmlRequestFile, $expectedResultFile ) {

		list( $id, $httpRequest, $jsonContents, $xmlContents, $expected ) = $this->prepareFileContents(
			$id,
			$httpJsonRequestFile,
			$httpXmlRequestFile,
			$expectedResultFile
		);

		$filteredHttpResponseParserFactory = new FilteredHttpResponseParserFactory(
			$httpRequest
		);

		$instance = $filteredHttpResponseParserFactory->newNcbiPubMedFilteredHttpResponseParser(
			new FilteredRecord()
		);

		$this->assertEquals(
			$jsonContents . $xmlContents,
			$instance->getRawResponse( $id )
		);

		$instance->doFilterResponseFor( $id );

		$this->assertEquals(
			$expected,
			$instance->getFilteredRecord()->getRecordFields()
		);
	}

	/**
	 * @dataProvider pmcidFileProvider
	 */
	public function testPMCIDParser( $id, $httpJsonRequestFile, $httpXmlRequestFile, $expectedResultFile ) {

		list( $id, $httpRequest, $jsonContents, $xmlContents, $expected ) = $this->prepareFileContents(
			$id,
			$httpJsonRequestFile,
			$httpXmlRequestFile,
			$expectedResultFile
		);

		$FilteredHttpResponseParserFactory = new FilteredHttpResponseParserFactory(
			$httpRequest
		);

		$instance = $FilteredHttpResponseParserFactory->newNcbiPubMedCentralFilteredHttpResponseParser(
			new FilteredRecord()
		);

		$this->assertEquals(
			$jsonContents . $xmlContents,
			$instance->getRawResponse( $id )
		);

		$instance->doFilterResponseFor( $id );

		$this->assertEquals(
			$expected,
			$instance->getFilteredRecord()->getRecordFields()
		);
	}

	private function prepareFileContents( $id, $httpJsonRequestFile, $httpXmlRequestFile, $expectedResultFile ) {

		$id = str_replace( array( 'PMID', 'PMC' ), '', $id );

		$jsonContents = file_get_contents( $httpJsonRequestFile );
		$xmlContents = file_get_contents( $httpXmlRequestFile );

		$expected = unserialize(
			str_replace( "\r\n", "\n", file_get_contents( $expectedResultFile ) )
		);

		$httpRequest = $this->getMockBuilder( '\Onoi\HttpRequest\HttpRequest' )
			->disableOriginalConstructor()
			->getMock();

		$httpRequest->expects( $this->any() )
			->method( 'execute' )
			->will( $this->onConsecutiveCalls(
				$jsonContents, $xmlContents,
				$jsonContents, $xmlContents ) );

		$httpRequest->expects( $this->any() )
			->method( 'getLastError' )
			->will( $this->returnValue( '' ) );

		return array( $id, $httpRequest, $jsonContents, $xmlContents, $expected );
	}

	public function pmidFileProvider() {

		$path = __DIR__ . '/Fixtures/';
		$provider = array();

		$provider[] = array(
			'PMID19782018',
			$path . 'PMID19782018.json',
			$path . 'PMID19782018.xml',
			$path . 'PMID19782018.expected'
		);

		return $provider;
	}

	public function pmcidFileProvider() {

		$path = __DIR__ . '/Fixtures/';
		$provider = array();

		$provider[] = array(
			'PMC2286209',
			$path . 'PMC2286209.json',
			$path . 'PMC2286209.xml',
			$path . 'PMC2286209.expected'
		);

		$provider[] = array(
			'PMC2776723',
			$path . 'PMC2776723.json',
			$path . 'PMC2776723.xml',
			$path . 'PMC2776723.expected'
		);

		return $provider;
	}

}
