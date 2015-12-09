<?php
namespace TJM\Component\Utils\Tests;

use PHPUnit_Framework_TestCase;
use TJM\Component\Utils\BaseNConverter;

class BaseNConverterTest extends PHPUnit_Framework_TestCase{
	public function testCustomTableConfigRegex(){
		//--matching
		$matchingTests = Array(
			'(abcd)'=> Array('abcd')
			,'(abcd)::-'=> Array('abcd', '::-', '-')
			,'((abcd))'=> Array('(abcd)')
			,'((abcd))::)'=> Array('(abcd)', '::)', ')')
			,'((abcd)):::'=> Array('(abcd)', ':::', ':')
			,'((abcd:)):::'=> Array('(abcd:)', ':::', ':')
		);
		foreach($matchingTests as $string=> $expectedResult){
			$matched = preg_match(BaseNConverter::CUSTOM_TABLE_CONFIG_REGEX, $string, $matches);
			array_shift($matches);
			$this->assertTrue($matched && $matches === $expectedResult, "Config string '{$string}' should match and produce expected result " . json_encode($expectedResult));
		}

		//--non-matching
		$nonMatchingTests = Array(
			'abcd'
			,'abcd::-'
			,'(abcd):::-'
			,'(abcd::-'
			,'abcd(abcd)'
		);
		foreach($nonMatchingTests as $string){
			$matched = preg_match(BaseNConverter::CUSTOM_TABLE_CONFIG_REGEX, $string, $matches);
			array_shift($matches);
			$this->assertTrue(!$matched, "Config string '{$string}' should not be matched");
		}
	}
	public function testNamedConfigRegex(){
		//--matching
		$matchingTests = Array(
			'abcd'=> Array('abcd')
			,'abcd::-'=> Array('abcd', '::-', '-')
			,'1234:::'=> Array('1234', ':::', ':')
		);
		foreach($matchingTests as $string=> $expectedResult){
			$matched = preg_match(BaseNConverter::NAMED_CONFIG_REGEX, $string, $matches);
			array_shift($matches);
			$this->assertTrue($matched && $matches === $expectedResult, "Config string '{$string}' should match and produce expected result " . json_encode($expectedResult));
		}

		//--non-matching
		$nonMatchingTests = Array(
			'abcd:::-'
			,':abcd:::'
			,'(abcd)'
			,'(abcd)::-'
			,'((abcd))'
			,'((abcd))::-'
			,'(abcd):::'
			,'(abcd:::'
			,'abcd(abcd)'
		);
		foreach($nonMatchingTests as $string){
			$matched = preg_match(BaseNConverter::NAMED_CONFIG_REGEX, $string, $matches);
			array_shift($matches);
			$this->assertTrue(!$matched, "Config string '{$string}' should not be matched");
		}
	}
	public function testFromForNewBase60(){
		$converter = new BaseNConverter('newBase60');
		$map = Array(
			'0'=> '0'
			,'1'=> '1'
			,'-1'=> '-1'
			,'A'=> '10'
			,'1a'=> '95'
			,'13'=> '63'
			,'10'=> '60'
		);
		foreach($map as $newBase60=> $base10){
			$this->assertEquals($base10, $converter->from($newBase60));
		}
	}
	public function testToForNewBase60(){
		$converter = new BaseNConverter('newBase60');
		$map = Array(
			'0'=> '0'
			,'1'=> '1'
			,'-1'=> '-1'
			,'10'=> 'A'
			,'95'=> '1a'
			,'63'=> '13'
			,'60'=> '10'
		);
		foreach($map as $base10=> $newBase60){
			$this->assertEquals($newBase60, $converter->to($base10));
		}
	}
	public function testFromForUrlSafeMap(){
		$converter = new BaseNConverter('urlSafe::~');
		$map = Array(
			'0'=> '0'
			,'1'=> '1'
			,'~1'=> '-1'
			,'a'=> '10'
			,'1a'=> '75'
			,'-'=> '63'
			,'10'=> '65'
		);
		foreach($map as $baseUrlSafe=> $base10){
			$this->assertEquals($base10, $converter->from($baseUrlSafe));
		}
	}
	public function testToForUrlSafeMap(){
		$converter = new BaseNConverter('urlSafe');
		$map = Array(
			'0'=> '0'
			,'1'=> '1'
			,'-1'=> '~1'
			,'10'=> 'a'
			,'75'=> '1a'
			,'63'=> '-'
			,'65'=> '10'
		);
		foreach($map as $base10=> $baseUrlSafe){
			$this->assertEquals($baseUrlSafe, $converter->to($base10));
		}
	}
	public function testFromForBase16Map(){
		$converter = new BaseNConverter('base16');
		$map = Array(
			'0'=> '0'
			,'1'=> '1'
			,'-1'=> '-1'
			,'a'=> '10'
			,'4b'=> '75'
		);
		foreach($map as $base16=> $base10){
			$this->assertEquals($base10, $converter->from($base16));
		}
	}
	public function testToForBase16Map(){
		$converter = new BaseNConverter('base16');
		$map = Array(
			'0'=> '0'
			,'1'=> '1'
			,'-1'=> '-1'
			,'10'=> 'a'
			,'75'=> '4b'
		);
		foreach($map as $base10=> $base16){
			$this->assertEquals($base16, $converter->to($base10));
		}
	}
}
