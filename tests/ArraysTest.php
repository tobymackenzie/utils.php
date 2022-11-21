<?php
namespace TJM\Component\Utils\Tests;
use PHPUnit\Framework\TestCase;
use TJM\Component\Utils\Arrays;

class ArraysTest extends TestCase{
	public function testDeepMergeWithSimpleNumericArrays(){
		$result = Arrays::deepMerge(Array(11, 12), Array(21), Array(31, 32, 33));
		$this->assertEquals(6, count($result), 'Result should have six items');
		$this->assertEquals(11, $result[0], 'First item should be \'11\'');
		$this->assertEquals(21, $result[2], 'Third item should be \'21\'');
		$this->assertEquals(33, $result[5], 'Last item should be \'33\'');
	}
	public function testDeepMergeWithSimpleStrings(){
		$result = Arrays::deepMerge(
			Array(
				'foo'=> '1'
			)
			,Array(
				'foo'=> '2'
			)
			,Array(
				'foo'=> '3'
			)
		);
		$this->assertEquals(1, count($result), 'Result should have one item');
		$this->assertEquals('1', $result['foo'], 'Result item should be value from first argument');
	}
	public function testDeepMergeWithSimpleStringsAndOverwrite(){
		$result = Arrays::deepMerge(
			Arrays::MERGE_OVERWRITE
			,Array(
				'foo'=> '1'
			)
			,Array(
				'foo'=> '2'
			)
			,Array(
				'foo'=> '3'
			)
		);
		$this->assertEquals(1, count($result), 'Result should have one item');
		$this->assertEquals('3', $result['foo'], 'Result item should be value from last argument');
	}
	public function testDeepMergeWithOneArrayArgument(){
		$result = Arrays::deepMerge(
			Arrays::MERGE_OVERWRITE
			,Array(
				Array(
					'foo'=> '1'
				)
				,Array(
					'foo'=> '2'
				)
			)
		);
		$this->assertEquals(1, count($result), 'Result should have one item');
		$this->assertEquals('2', $result['foo'], 'Result item should be value from last argument');
	}
	public function testDeepMergeWithNestedStrings(){
		$result = Arrays::deepMerge(
			Array(
				'foo'=> Array(
					'bar'=> '1'
				)
			)
			,Array(
				'foo'=> Array(
					'bar'=> '2'
				)
			)
		);
		$this->assertEquals(1, count($result), 'Result should have one item');
		$this->assertTrue(is_array($result['foo']), 'Result[foo] should be an array');
		$this->assertEquals('1', $result['foo']['bar'], 'Result[foo] value should come from first argument');
	}
	public function testDeepMergeWithNestedStringsWithOverwrite(){
		$result = Arrays::deepMerge(
			Arrays::MERGE_OVERWRITE
			,Array(
				'foo'=> Array(
					'bar'=> '1'
				)
			)
			,Array(
				'foo'=> Array(
					'bar'=> '2'
				)
			)
		);
		$this->assertEquals(1, count($result), 'Result should have one item');
		$this->assertTrue(is_array($result['foo']), 'Result[foo] should be an array');
		$this->assertEquals('2', $result['foo']['bar'], 'Result[foo] value should come from last argument');
	}
	public function testDeepMergeWithNestedNumericArrays(){
		$result = Arrays::deepMerge(
			Array(
				'foo'=> Array(1, 2)
			)
			,Array(
				'foo'=> Array(3, 4, 5)
			)
		);
		$this->assertEquals(1, count($result), 'Result should have one item');
		$this->assertTrue(is_array($result['foo']), 'Result[foo] should be an array');
		$this->assertEquals(5, count($result['foo']), 'Result[foo] should have five items');
		$this->assertEquals(1, $result['foo'][0], 'Result[foo] first item should be \'1\'');
		$this->assertEquals(5, $result['foo'][4], 'Result[foo] last item should be \'5\'');
	}
	public function testDeepMergeWithNestedNumericArraysWithOverwrite(){
		$result = Arrays::deepMerge(
			Arrays::MERGE_OVERWRITE
			,Array(
				'foo'=> Array(1, 2)
			)
			,Array(
				'foo'=> Array(3, 4, 5)
			)
		);
		$this->assertEquals(1, count($result), 'Result should have one item');
		$this->assertTrue(is_array($result['foo']), 'Result[foo] should be an array');
		$this->assertEquals(5, count($result['foo']), 'Result[foo] should have five items');
		$this->assertEquals(1, $result['foo'][0], 'Result[foo] first item should be \'1\'');
		$this->assertEquals(5, $result['foo'][4], 'Result[foo] last item should be \'5\'');
	}
	public function testDeepMergeWithUniqueFlag(){
		$result = Arrays::deepMerge(
			Arrays::MERGE_NUMERIC_UNIQUE_VALUES,
			array(
				1,
				array(1, 2),
				'three',
			),
			array(
				2,
				'three',
				array(1, 2),
				array(1, 2, 3),
			),
		);
		$this->assertEquals(5, count($result));
		$this->assertEquals(1, $result[0]);
		$this->assertEquals(array(1, 2), $result[1]);
		$this->assertEquals('three', $result[2]);
		$this->assertEquals(2, $result[3]);
		$this->assertEquals(array(1, 2, 3), $result[4]);
	}
}
