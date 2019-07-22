<?php namespace App\Lib\Game\Method\Ssc\H3;

use App\Lib\Game\Method\Ssc\Base;

// 前三　组选包胆
class HZU3BD extends Base
{
    //1
    public $all_count = 486;
    public static $filterArr = array(0 => 54, 1 => 54, 2 => 54, 3 => 54, 4 => 54, 5 => 54, 6 => 54, 7 => 54, 8 => 54, 9 => 54);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        return array_rand(self::$filterArr, 1);
    }

    public function regexp($sCodes)
    {
        return isset(self::$filterArr[$sCodes]);
    }

    public function count($sCodes)
    {
        //枚举之和
        $n = 0;
        $temp = explode('&', $sCodes);
        foreach ($temp as $c) {
            $n += self::$filterArr[$c];
        }

        return $n;
    }

    public function bingoCode(Array $numbers)
    {
        //豹子号
        if (count(array_count_values($numbers)) == 1) return [];

        $exists = array_flip($numbers);
        $arr = array_keys(self::$filterArr);
        $result = [];
        foreach ($arr as $pos => $_code) {
            $result[$pos] = intval(isset($exists[$_code]));
        }

        return [$result];
    }

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $aCodes = explode('&', $sCodes);

        if ($levelId == '1') {
            $flip = array_filter(array_count_values($numbers), function ($v) {
                return $v == 2;
            });

            //组三
            if (count($flip) == 1) {
                foreach ($aCodes as $code) {
                    if (in_array($code, $numbers)) {
                        return 1;
                    }
                }
            }
        } elseif ($levelId == '2') {
            //排除组3组6
            $flip = array_filter(array_count_values($numbers), function ($v) {
                return $v >= 2;
            });

            //组六
            if (count($flip) == 0) {
                foreach ($aCodes as $code) {
                    if (in_array($code, $numbers)) {
                        return 1;
                    }
                }
            }
        }

    }
}
