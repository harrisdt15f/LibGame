<?php namespace App\Lib\Game\Method\Ssc\H3;

use App\Lib\Game\Method\Ssc\Base;

//组选六
class HZU6 extends Base
{
    //1&2&3&4&5&6
    public $all_count = 120;
    public static $filterArr = array(0 => 1, 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1, 6 => 1, 7 => 1, 8 => 1, 9 => 1);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $rand = rand(3, 10);
        return implode('&', (array)array_rand(self::$filterArr, $rand));
    }

    public function fromOld($codes)
    {
        return implode('&', explode('|', $codes));
    }

    public function regexp($sCodes)
    {
        //格式
        if (!preg_match("/^(([0-9]&)*[0-9])$/", $sCodes)) {
            return false;
        }

        //去重
        $t = explode("&", $sCodes);
        $temp = array_unique($t);
        $arr = self::$filterArr;

        $temp = array_filter($temp, function ($v) use ($arr) {
            return isset($arr[$v]);
        });

        if (count($temp) == 0) {
            return false;
        }

        return count($temp) == count($t);
    }

    public function count($sCodes)
    {
        //C(n,3)

        $n = count(explode("&", $sCodes));

        return $this->getCombinCount($n, 3);
    }

    public function bingoCode(Array $numbers)
    {
        //对子或豹子号
        if (count(array_count_values($numbers)) != 3) return [array_fill(0, count(self::$filterArr), 0)];

        $exists = array_flip($numbers);
        $arr = array_keys(self::$filterArr);
        $result = [];
        foreach ($arr as $_code) {
            $result[] = intval(isset($exists[$_code]));
        }

        return [$result];
    }

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $flip = array_filter(array_count_values($numbers), function ($v) {
            return $v >= 2;
        });

        //非组三 和 豹子
        if (count($flip) == 0) {
            $preg = "|[" . str_replace('&', '', $sCodes) . "]{3}|";
            if (preg_match($preg, implode("", $numbers))) {
                return 1;
            }
        }

    }
}
