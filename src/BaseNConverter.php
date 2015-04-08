<?php
namespace TJM\Component\Utils;

/*
Class: BaseNConverter
Use to convert to and from a base-n number with provided character table.  Only works for integers currently.
*/
class BaseNConverter{
	public function __construct($characterTable = null){
		$this->setCharacterTable($characterTable);
	}

	/*
	Property: base
	'n' of base-n, calculated based on the number of characters in characterTable.
	*/
	protected $base = 2;

	/*
	Property: characterTable
	Table of characters to be used in base-n number, with first value representing 0, second value representing 1, and so on.
	*/
	protected $characterTable = Array(0, 1);

	public function setCharacterTable($characterTable){
		if(is_string($characterTable)){
			$this->characterTable = static::getNamedTable($characterTable);
		}else{
			$this->characterTable = $characterTable;
		}
		$this->base = count($this->characterTable);
	}

	/*
	Property: negativeCharacter
	Character to prepend to number to represent a negative value.
	*/
	protected $negativeCharacter = '-';

	public function setNegativeCharacter($negativeCharacter){
		$this->negativeCharacter = $negativeCharacter;
	}

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
			$char = $number{$i};
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

	/*=====
	==tables
	=====*/
	static public function getNamedTable($name){
		switch($name){
			case 'base16':
			case 'base16LC':
				return Array(
					'0'
					,'1'
					,'2'
					,'3'
					,'4'
					,'5'
					,'6'
					,'7'
					,'8'
					,'9'
					,'a'
					,'b'
					,'c'
					,'d'
					,'e'
					,'f'
				);
			break;
			case 'base16UC':
				return Array(
					'0'
					,'1'
					,'2'
					,'3'
					,'4'
					,'5'
					,'6'
					,'7'
					,'8'
					,'9'
					,'A'
					,'B'
					,'C'
					,'D'
					,'E'
					,'F'
				);
			break;
			case 'urlSafe':
				return Array(
					'0'
					,'1'
					,'2'
					,'3'
					,'4'
					,'5'
					,'6'
					,'7'
					,'8'
					,'9'
					,'a'
					,'b'
					,'c'
					,'d'
					,'e'
					,'f'
					,'g'
					,'h'
					,'i'
					,'j'
					,'k'
					,'l'
					,'m'
					,'n'
					,'o'
					,'p'
					,'q'
					,'r'
					,'s'
					,'t'
					,'u'
					,'v'
					,'w'
					,'x'
					,'y'
					,'z'
					,'A'
					,'B'
					,'C'
					,'D'
					,'E'
					,'F'
					,'G'
					,'H'
					,'I'
					,'J'
					,'K'
					,'L'
					,'M'
					,'N'
					,'O'
					,'P'
					,'Q'
					,'R'
					,'S'
					,'T'
					,'U'
					,'V'
					,'W'
					,'X'
					,'Y'
					,'Z'
					,'.'
					,'!'
					,'*'
					,'-'
					,'_'
				);
			break;
		}
	}
}
