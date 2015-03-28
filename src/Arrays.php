<?php
namespace TJM\Component\Utils;

class Arrays{
	/*
	Constant: MERGE_OVERWRITE
	Tell merge to overwrite previous values.  By default, existing values will be preserved.
	*/
	const MERGE_OVERWRITE = 1;

	/*
	Method: deepMerge
	Recursively merge potentially nested arrays.
	Arguments:
		$arrays…(Array): arrays to merge.  If provided single array, will treat array's items as the arrays to merge.
	-@ based on [Drupal's drupal_array_merge_deep_array()](https://github.com/drupal/drupal/blob/7.x/includes/bootstrap.inc#L2139)
	*/
	static public function deepMerge(){
		$result = Array();
		$arrays = func_get_args();
		$flags = (is_integer($arrays[0])) ? array_shift($arrays) : 0;

		//--if only one item, treat as array of items to merge
		if(count($arrays) === 1){
			$arrays = $arrays[0];
		}
		foreach($arrays as $array){
			foreach($array as $key=> $value){

				//--if key is integer, implying numeric array, push into result array
				if(is_integer($key)){
					$result[] = $value;
				//--if array, recursively merge into key
				}elseif(isset($result[$key]) && is_array($result[$key]) && is_array($value)){
					$result[$key] = static::deepMerge($flags, $result[$key], $value);
				//--otherwise, set value for key, possibly overriding existing
				}else{
					if(!array_key_exists($key, $result) || $flags & static::MERGE_OVERWRITE){
						$result[$key] = $value;
					}
				}
			}
		}
		return $result;
	}
}
