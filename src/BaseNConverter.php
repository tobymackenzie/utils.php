<?php
namespace TJM\Component\Utils;

use Exception;

/*
Class: BaseNConverter
Use to convert to and from a base-n number with provided character table.  Only works for integers currently.
Arguments:
	config: can be either an array of characters that make up the character table, or a string representing the configuration (see `$config`)
*/
class BaseNConverter{
	public function __construct($config){
		$this->setConfig($config);
	}

	/*
	Property: base
	'n' of base-n, calculated based on the number of characters in characterTable.
	*/
	protected $base;

	/*
	Property: characterTable
	Table of characters to be used in base-n number, with first value representing 0, second value representing 1, and so on.
	*/
	protected $characterTable;

	protected function setCharacterTable($characterTable){
		if(is_string($characterTable)){
			$this->characterTable = str_split($characterTable);
		}else{
			$this->characterTable = $characterTable;
		}
		$this->base = count($this->characterTable);
	}

	/*
	Property: config
	String representing configuration.  This string represents the entire configuration for the instance of this class, and any instances with the same config string will produce the same output given the same input.  The config consists of one or more pieces separated by '::'.  The pieces are as follows:

	1. (required) The character table.  If wrapped in '(' and ')' characters, will be considered an ordered list of characters making up the table.  Otherwise will be considered a named character table from `getNamedCharacterTableConfig()`.
	2. negativeCharacter
	*/
	protected $config;
	public function getConfig(){
		return $this->config;
	}
	protected function setConfig($config){
		if(is_array($config)){
			$this->setCharacterTable($config);
			$this->config = implode('', $config);
		}else{
			$this->config = $config;
			if(preg_match(static::NAMED_CONFIG_REGEX, $config, $matches)){
				preg_match(static::CUSTOM_TABLE_CONFIG_REGEX, $this->getNamedCharacterTableConfig($matches[1]), $namedConfigMatches);
				$this->setCharacterTable($namedConfigMatches[1]);
				if(isset($matches[3])){
					$this->negativeCharacter = $matches[3];
				}elseif(isset($namedConfigMatches[3])){
					$this->negativeCharacter = $namedConfigMatches[3];
				}
			}elseif(preg_match(static::CUSTOM_TABLE_CONFIG_REGEX, $config, $matches)){
				$this->setCharacterTable($matches[1]);
				if(isset($matches[3])){
					$this->negativeCharacter = $matches[3];
				}
			}else{
				throw new Exception("Config '{$config}' not in valid format.");
			}
		}
	}
	public function getNamedCharacterTableConfig($name){
		switch($name){
			case 'base16':
			case 'base16LC':
				return '(0123456789abcdef)::-';
			break;
			case 'base16UC':
				return '(0123456789ABCDEF)::-';
			break;
			/*
			newBase60: probably ideal when readability and URL safeness are important, like for URL shorteners.  [Tantek's creation](http://tantek.pbworks.com/w/page/19402946/NewBase60)(-@), used for his URL shortener.
			*/
			case 'newBase60':
				return '(0123456789ABCDEFGHJKLMNPQRSTUVWXYZ_abcdefghijkmnopqrstuvwxyz)::-';
			break;
			/*
			newBase64: probably ideal when readability and URL safeness are important, like for URL shorteners.  Not great for filesystem paths because of '*'.  [Tantek's creation](http://tantek.pbworks.com/w/page/24308279/NewBase64)(-@).
			*/
			case 'newBase64':
				return '(0123456789ABCDEFGHJKLMNPQRSTUVWXYZ_abcdefghijkmnopqrstuvwxyz-+*$)::~';
			break;
			/*
			tjmBase65: Provides 5 more characters than 'newBase60' ('l','I','O','.', and '-'), but should still be safe for both HTTP and filesystem paths.  Probably best for URLs when readability isn't important, like for asset versioning.
			*/
			case 'tjmBase65':
				return '(0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ.-_)::~';
			break;
		}
	}

	/*
	Property: CUSTOM_TABLE_CONFIG_REGEX
	Regex used to match custom character table configuration format.
	*/
	const CUSTOM_TABLE_CONFIG_REGEX = '/^\((.*?)\)(::(.))?$/';
	// const CUSTOM_TABLE_CONFIG_REGEX = '/^\(((?:([.])(?!.*\\1))*)\)(::(.))?$/';

	/*
	Property: NAMED_CONFIG_REGEX
	Regex used to match named configuration format.
	*/
	const NAMED_CONFIG_REGEX = '/^(\w+)(::(.))?$/';

	/*
	Property: negativeCharacter
	Character to prepend to number to represent a negative value.
	*/
	protected $negativeCharacter = '-';

	/*=====
	==conversions
	=====*/
	/*
	Method: from
	Convert base-n representation of number to regular integer.
	-@ algorithm based on http://www.eecs.wsu.edu/~ee314/handouts/numsys.pdf
	*/
	public function from($number){
		$number = (string) $number;
		$negative = (strpos($number, $this->negativeCharacter) === 0);
		if($negative){
			$number = substr($number, strlen($this->negativeCharacter));
		}
		// if($number === $this->characterTable[0]){}
		$length = strlen($number);
		$result = 0;
		for($i = 0; $i < $length; ++$i){
			$char = substr($number, $i, 1);
			$tablePosition = array_search($char, $this->characterTable);
			$charPosition = $length - $i - 1;
			$result += $tablePosition * pow($this->base, $charPosition);
		}
		if($negative){
			$result *= -1;
		}
		return $result;
	}

	/*
	Method: to
	Convert regular integer to base-n representation.
	-@ algorithm based on http://www.eecs.wsu.edu/~ee314/handouts/numsys.pdf
	*/
	public function to($number){
		if($number === 0){
			$result = $this->characterTable[$number];
		}else{
			$negative = ($number < 0);
			$result = '';
			$remaining = ($negative) ? abs($number) : $number;
			while($remaining > 0){
				$remainder = $remaining % $this->base;
				$remaining = (int) ($remaining / $this->base);
				$result = $this->characterTable[$remainder] . $result;
			}
			if($negative){
				$result = $this->negativeCharacter . $result;
			}
		}
		return $result;
	}
}
