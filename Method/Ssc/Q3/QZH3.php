<?php namespace App\Lib\Game\Method\Ssc\Q3;

use App\Lib\Game\Method\Ssc\Base;

// 前 组合3
class QZH3 extends Base
{
    // 0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9
    public $all_count = 3000;
    public static $filterArr = array(
        0 => 1,
        1 => 1,
        2 => 1,
        3 => 1,
        4 => 1,
        5 => 1,
        6 => 1,
        7 => 1,
        8 => 1,
        9 => 1
    );

    // 供测试用 生成随机投注
    public function randomCodes()
    {
        $arr    = [];
        $rand   = rand(1, 10);
        $arr[]  = implode('&', (array)array_rand(self::$filterArr, $rand));
        $rand   = rand(1, 10);
        $arr[]  = implode('&', (array)array_rand(self::$filterArr, $rand));
        $rand   = rand(1, 10);
        $arr[]  = implode('&', (array)array_rand(self::$filterArr, $rand));

        return implode('|', $arr);
    }

    public function regexp($sCodes)
    {
        $regexp = '/^(([0-9]&){0,9}[0-9])\|(([0-9]&){0,9}[0-9])\|(([0-9]&){0,9}[0-9])$/';
        if( !preg_match($regexp,$sCodes) ) return false;

        $filterArr = self::$filterArr;

        //去重
        $sCodes = explode("|", $sCodes);
        foreach($sCodes as $codes) {
            $temp = explode('&', $codes);
            if(count($temp) != count(array_filter(array_unique($temp), function($v) use($filterArr) {
                    return isset($filterArr[$v]);
                }))) return false;

            if(count($temp) == 0) {
                return false;
            }
        }

        return true;
    }

    public function count($sCodes)
    {
        //n1*n2*n3*3
        $cnt    = 1;
        $temp   = explode('|', $sCodes);
        foreach($temp as $c) {
            $cnt *= count(explode('&',$c));
        }

        $cnt *= 3;

        return $cnt;
    }

    public function bingoCode(Array $numbers)
    {
        $result = [];
        $arr    = array_keys(self::$filterArr);

        foreach($numbers as $pos => $code) {
            $tmp = [];
            foreach($arr as $_code) {
                $tmp[] = intval($code == $_code);
            }
            $result[$pos] = $tmp;
        }

        return $result;
    }

    // 判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {

        $aCodes = explode('|', $sCodes);

        if ($levelId == '1') {
            $preg = "|[" . str_replace('&', '', $aCodes[0]) . "][" . str_replace('&', '', $aCodes[1]) . "][" . str_replace('&', '', $aCodes[2]) . "]|";
            if (preg_match($preg, implode("", $numbers))) {
                return 1;
            }
        } elseif ($levelId == '2') {
            $preg = "|[" . str_replace('&', '', $aCodes[1]) . "][" . str_replace('&', '', $aCodes[2]) . "]|";
            if (preg_match($preg, implode("", $numbers))) {
                $times = count(explode('&',$aCodes[0]));
                return $times;
            }
        } elseif ($levelId == '3') {
            $preg = "|[" . str_replace('&', '', $aCodes[2]) . "]|";
            if (preg_match($preg, implode("", $numbers))) {
                $times = count(explode('&',$aCodes[0])) * count(explode('&',$aCodes[1]));
                return $times;
            }
        }

    }

}
