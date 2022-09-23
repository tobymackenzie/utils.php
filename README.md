PHP Utils
=========

General PHP utilities.  Currently only contains:
- a method for deeply merging:
	`$arr = TJM\Component\Utils\Arrays::deepMerge([], []);`
- a base-n converter to convert from / to integers and shorter strings:
	`$str = (new TJM\Component\Utils\BaseNConverter('newBase64'))->to(123);`
