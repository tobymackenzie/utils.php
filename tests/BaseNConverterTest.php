<?php
namespace TJM\Component\Utils\Tests;

use PHPUnit_Framework_TestCase;
use TJM\Component\Utils\BaseNConverter;

class BaseNConverterTest extends PHPUnit_Framework_TestCase{
	public function testFromForUrlSafeMap(){
		$converter = new BaseNConverter('urlSafe');
		$map = Array(
			'0'=> '0'
			,'1'=> '1'
			,'-1'=> '-1'
			,'a'=> '10'
			,'18'=> '75'
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
			,'-1'=> '-1'
			,'10'=> 'a'
			,'75'=> '18'
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
