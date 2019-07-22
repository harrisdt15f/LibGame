<?php namespace App\Lib\Game\Method\Ssc\COLH;

use App\Lib\Game\Method\Ssc\Base;

class CO_LHQG extends Base
{
    // 1&2&3龙虎和
    public $all_count =3;
    public static $filterArr = array(0 => 1, 1 => 1, 2 => 1, 3 => 1);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $arr=[];
        $rand=rand(1,10);
        $arr[]=implode('&',(array)array_rand(self::$filterArr,$rand));
        $rand=rand(1,10);
        $arr[]=implode('&',(array)array_rand(self::$filterArr,$rand));

        return implode('|',$arr);
    }

    public function regexp($sCodes)
    {
        $aCodes = explode('&', $sCodes);
        foreach ($aCodes as $code) {

        }
    }

    public function count($sCodes)
    {
        $temp = explode('&', $sCodes);
        $temp = array_unique($temp);
        return count($temp);
    }

    public function bingoCode(Array $numbers)
    {
        $result=[];
        $arr=array_keys(self::$filterArr);

        foreach($numbers as $pos=>$code){
            $tmp=[];
            foreach($arr as $_code){
                $tmp[]=intval($code==$_code);
            }
            $result[$pos]=$tmp;
        }

        return $result;
    }

    public function assertLevel($levelId, $sCodes, Array $numbers)
    {

        $aCodes = explode('&', $sCodes);
        $w = $numbers[4];
        $q = $numbers[3];

        $count = 0;
        if ($w > $q && in_array(1, $aCodes)) {
            $count = 1;
        }

        if ($w == $q && in_array(3, $aCodes)) {
            $count = 1;
        }

        if ($w <= $q && in_array(2, $aCodes)) {
            $count = 1;
        }

        return $count;
    }
}
