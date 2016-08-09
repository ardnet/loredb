<?php

namespace Onoi\Remi\Tests;

use Onoi\Remi\FilteredRecord;

/**
 * @covers \Onoi\Remi\FilteredRecord
 * @group onoi-remi
 *
 * @license GNU GPL v2+
 * @since   0.1
 *
 * @author mwjames
 */
class FilteredRecordTest extends \PHPUnit_Framework_TestCase {

	public function testCanConstruct() {

		$this->assertInstanceOf(
			'\Onoi\Remi\FilteredRecord',
			new FilteredRecord()
		);
	}

	/**
	 * @dataProvider setterGetterProvider
	 */
	public function testSetterGetter( $set, $append, $expected ) {

		$instance = new FilteredRecord();

		$instance->set( 'foo', $set );
		$instance->append( 'foo', $append );

		$this->assertTrue(
			$instance->has( 'foo' )
		);

		$this->assertEquals(
			$expected,
			$instance->get( 'foo' )
		);

		$instance->delete( 'foo' );

		$this->assertFalse(
			$instance->has( 'foo' )
		);
	}

	/**
	 * @dataProvider setterGetterProvider
	 */
	public function testGetRecordFields( $set, $append, $expected ) {

		$instance = new FilteredRecord();

		$instance->set( 'foo', $set );
		$instance->append( 'foo', $append );

		$this->assertEquals(
			array(
				'foo' => $expected
			),
			$instance->getRecordFields()
		);
	}

	public function testRedactedFields() {

		$instance = new FilteredRecord();
		$instance->setRedactedFields( array( 'bar' ) );

		$instance->set( 'bar', 42 );

		$this->assertFalse(
			$instance->has( 'bar' )
		);

		$instance->append( 'bar', 'foobar' );

		$this->assertFalse(
			$instance->has( 'bar' )
		);

		$this->assertEmpty(
			$instance->getRecordFields()
		);
	}

	public function testUnknownRecordKeyFieldThrowsException() {

		$instance = new FilteredRecord();

		$this->setExpectedException( 'InvalidArgumentException' );
		$instance->get( 'foo' );
	}

	public function setterGetterProvider() {

		$provider[] = array(
			'catch',
			22,
			'catch22'
		);

		$provider[] = array(
			'',
			22,
			'22'
		);

		$provider[] = array(
			array( 'catch' ),
			22,
			array( 'catch', 22 )
		);

		$provider[] = array(
			array( 'catch' ),
			array( 22 ),
			array( 'catch', array( 22 ) )
		);

		return $provider;
	}

}
