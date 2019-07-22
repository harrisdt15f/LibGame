<?php namespace App\Lib\Game\Method\Ssc\Q3;

use App\Lib\Game\Method\Ssc\Base;
use Illuminate\Support\Facades\Validator;

// 前 3 直选和值
class QZXHZ extends Base
{
    // 1&2&3&4&5&6&27
    public $all_count = 1000;
    public static $filterArr = array(0 => 1, 1 => 3, 2 => 6, 3 => 10, 4 => 15, 5 => 21, 6 => 28, 7 => 36, 8 => 45, 9 => 55, 10 => 63, 11 => 69, 12 => 73, 13 => 75, 14 => 75, 15 => 73, 16 => 69, 17 => 63, 18 => 55, 19 => 45, 20 => 36, 21 => 28, 22 => 21, 23 => 15, 24 => 10, 25 => 6, 26 => 3, 27 => 1);

    // 供测试用 生成随机投注
    public function randomCodes()
    {
        $rand = rand(1, count(self::$filterArr));
        return implode('&', (array)array_rand(self::$filterArr, $rand));
    }

    public function fromOld($codes)
    {
        return implode('&', explode('|', $codes));
    }

    public function regexp($sCodes)
    {
        //\b(0?[1-9]|1[0-9]|2[0-5])\b
        $data['code'] = $sCodes;
        $validator = Validator::make($data, [
            'code' => ['regex:/^(?!\|)(?!.*\|$)(?!.*?\|\|)(?!.*?0\d)(0?[\d\|]|1[\d]|2[0-7]){1,73}$/'],
            //0|1|2|3|4|5|6|7|8|9|10|11|12|13|14|15|16|17|18|19|20|21|22|23|24|25|26|27 前三直选和值
        ]);
        if ($validator->fails()) {
            return false;
        }
        return true;
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
        $val    = array_sum($numbers);
        $arr    = array_keys(self::$filterArr);
        $result = [];
        foreach ($arr as $_code) {
            $result[] = intval($_code == $val);
        }

        return [$result];
    }

    // 判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $val = array_sum($numbers);

        $aCodes = explode('&', $sCodes);

        foreach ($aCodes as $code) {
            if ($code == $val) {
                return 1;
            }
        }

    }

}
