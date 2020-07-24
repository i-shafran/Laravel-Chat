<?php

namespace App\Helpers;

use App\Exceptions\CustomErrorException;
use Illuminate\Database\Eloquent\Model;

class Helper
{
	/**
	 * Транслит из имени
	 *
	 * @param $string - Строка для транслита
	 * @return mixed|string
	 */
	public static function translit($string)
	{
		$string = (string) $string; // преобразуем в строковое значение
		$string = strip_tags($string); // убираем HTML-теги
		$string = str_replace(array("\n", "\r"), " ", $string); // убираем перевод каретки
		$string = preg_replace("/\s+/", ' ', $string); // удаляем повторяющие пробелы
		$string = trim($string); // убираем пробелы в начале и конце строки
		$string = function_exists('mb_strtolower') ? mb_strtolower($string) : strtolower($string); // переводим строку в нижний регистр (иногда надо задать локаль)
		$string = strtr($string, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
		$string = preg_replace("/[^0-9a-z-_ ]/i", "", $string); // очищаем строку от недопустимых символов
		$string = str_replace(" ", "-", $string); // заменяем пробелы знаком минус
		return $string; // возвращаем результат
	}

	/**
	 * Для функции preg_match_all массив $matches в нормальный вид
	 * 
	 * @param $matches
	 * @return array
	 */
	public static function turn_array($matches)
	{
		$res = array();
		for ($z = 0;$z < count($matches);$z++)
		{
			for ($x = 0;$x < count($matches[$z]);$x++)
			{
				$res[$x][$z] = $matches[$z][$x];
			}
		}

		return $res;
	}

	/**
	 * Сортировка многомерного ассоциативного массива
	 * Пример: $sorted = array_orderby($data, 'volume', SORT_DESC, 'edition', SORT_ASC);
	 * 
	 *  $data[] = array('volume' => 67, 'edition' => 2);
		$data[] = array('volume' => 86, 'edition' => 1);
		$data[] = array('volume' => 85, 'edition' => 6);
		$data[] = array('volume' => 98, 'edition' => 2);
		$data[] = array('volume' => 86, 'edition' => 6);
		$data[] = array('volume' => 67, 'edition' => 7);
	 * 
	 * @return mixed
	 */
	public static function arrayOrderBy()
	{
		$args = func_get_args();
		$data = array_shift($args);
		foreach ($args as $n => $field) {
			if (is_string($field)) {
				$tmp = array();
				foreach ($data as $key => $row)
					$tmp[$key] = $row[$field];
				$args[$n] = $tmp;
			}
		}
		$args[] = &$data;
		call_user_func_array('array_multisort', $args);
		
		return array_pop($args);
	}

	/**
	 * Рандомное число заданной длины
	 * 
	 * @param $size - Размер числа
	 * @param $min
	 * @param $max
	 * @return string
	 */
	public static function randSizeInt($size, $min = 0, $max = 9)
	{
		$res = array();
		for($i = 0; $i < $size; $i++)
		{
			$res[] = rand($min, $max);
		}

		return implode("", $res);
	}

	/*
	 * Сортировка многомерного массива по нескольким полям с направлением сортировки 
	 * 
	 * @param array $ary the array we want to sort
	 * @param string $clause a string specifying how to sort the array similar to SQL ORDER BY clause
	 * @param bool $ascending that default sorts fall back to when no direction is specified
	 * @example orderBy($testAry, 'a ASC, b DESC');
	 * 
	 * @return null
	*/
	public static function orderBy(&$ary, $clause, $ascending = true) {
		$clause = str_ireplace('order by', '', $clause);
		$clause = preg_replace('/\s+/', ' ', $clause);
		$keys = explode(',', $clause);
		$dirMap = array('desc' => 1, 'asc' => -1);
		$def = $ascending ? -1 : 1;

		$keyAry = array();
		$dirAry = array();
		foreach($keys as $key) {
			$key = explode(' ', trim($key));
			$keyAry[] = trim($key[0]);
			if(isset($key[1])) {
				$dir = strtolower(trim($key[1]));
				$dirAry[] = $dirMap[$dir] ? $dirMap[$dir] : $def;
			} else {
				$dirAry[] = $def;
			}
		}

		$fnBody = '';
		for($i = count($keyAry) - 1; $i >= 0; $i--) {
			$k = $keyAry[$i];
			$t = $dirAry[$i];
			$f = -1 * $t;
			$aStr = '$a[\''.$k.'\']';
			$bStr = '$b[\''.$k.'\']';
			if(strpos($k, '(') !== false) {
				$aStr = '$a->'.$k;
				$bStr = '$b->'.$k;
			}

			if($fnBody == '') {
				$fnBody .= "if({$aStr} == {$bStr}) { return 0; }\n";
				$fnBody .= "return ({$aStr} < {$bStr}) ? {$t} : {$f};\n";
			} else {
				$fnBody = "if({$aStr} == {$bStr}) {\n" . $fnBody;
				$fnBody .= "}\n";
				$fnBody .= "return ({$aStr} < {$bStr}) ? {$t} : {$f};\n";
			}
		}

		if($fnBody) {
			$sortFn = create_function('$a,$b', $fnBody);
			usort($ary, $sortFn);
		}
	}

	/**
	 * To get the memory usage in KB or MB
	 * 
	 * @param $size - memory_get_usage(true)
	 * @return string
	 */
	public static function convert($size)
	{
		$unit=array('b','kb','mb','gb','tb','pb');
		return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
	}

	/**
	 * String clear
	 * 
	 * @param $str - string
	 * @return null|string|string[]
	 */
	public static function strClear($str)
	{
		$str = preg_replace('/\s+/', ' ', $str);
		$str = trim($str);
		//$str = iconv('UTF-8', 'Windows-1251', $str);

		return $str;
	}

	/**
	 * Slug generator for Eloquent objects
	 *
	 * @param Model|\Eloquent $object - entity
	 * @param $str - title or name entity
	 * @param bool $salt - random integer
	 * @param int $count - number of recursive function calls
	 * @return mixed|string
	 * @throws CustomErrorException
	 */
	public static function slugGenerator(Model $object, $str, $salt = false, $count = 0)
	{
		if($count > 20){
			// stop recursive
			throw new CustomErrorException("slugGenerator stoped recursive after $count tries");
		}

		if($salt){
			$slug = self::translit($str)."-".$salt;
		} else {
			$slug = self::translit($str);
		}

		if(method_exists($object, "restore")){ // restore is a trait's SoftDeletes method 
			$oldObject = $object->withTrashed()->where("slug", $slug)->count();
		} else {
			$oldObject = $object->where("slug", $slug)->count();
		}
		
		if($oldObject > 0 and $count == 0){
			$count++;
			$last_id = $object->latest()->first()->id;
			$slug = self::slugGenerator($object, $str, $last_id + 1, $count);
		}
		elseif($oldObject > 0){
			$count++;
			$slug = self::slugGenerator($object, $str, rand(1, 10000), $count);
		}

		return $slug;
	}

	/**
	 * Get random password
	 * 
	 * @param int $length - pass length
	 * @return bool|string
	 */
	public static function passRandom($length = 5)
	{
		$pool = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

		return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
	}

	/**
	 * Check date of birth format
	 *
	 * @param $date - Дата в формате сайта
	 * @return string|boolean
	 */
	public static function checkAndGetMysqlDate($date)
	{
		$date = trim($date);
		$pattern = "#^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$#"; // 01.02.2000, 1.2.2000
		if(preg_match($pattern, $date, $matches) and checkdate($matches[2], $matches[1], $matches[3])){
			return "$matches[3]-$matches[2]-$matches[1]";
		} 

		return false;
	}

	/**
	 * Обрезка лишних нулей
	 * 
	 * @param $float
	 * @return float|string
	 */
	public static function cutZerro($float)
	{
		$float1 = sprintf("%01.08f",$float);
		$float2 = sprintf("%01.07f",$float);
		$sizeofFloat1 = strlen($float1)-1;
		$sizeofFloat2 = strlen($float2)-1;
		if ($float[$sizeofFloat1] != 0) {
			return sprintf("%01.08f",$float);
		}
		if ($float[$sizeofFloat2] != 0) {
			return sprintf("%01.07f",$float);
		}

		return round($float,8);
	}
}