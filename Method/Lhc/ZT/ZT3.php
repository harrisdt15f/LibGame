<?php namespace App\Lib\Game\Method\Lhc\ZT;

use App\Lib\Game\Method\Lhc\Base;

// 正特3
class ZT3 extends Base
{
    public $all_count = 49;
    public static $filterArr = array(
        1  =>   1,  2  =>   2,  3  =>   3,  4  =>   4,  5  =>   5,  6  =>   6,  7  =>   7,  8  =>   8,  9  =>   9,  10  => 10,
        11 => '11', 12 => '12', 13 => '13', 14 => '14', 15 => '15', 16 => '16', 17 => '17', 18 => '18', 19 => '19', 20 => '20',
        21 => '21', 22 => '22', 23 => '23', 24 => '24', 25 => '25', 26 => '26', 27 => '27', 28 => '28', 29 => '29', 30 => '30',
        31 => '31', 32 => '32', 33 => '33', 34 => '34', 35 => '35', 36 => '36', 37 => '37', 38 => '38', 39 => '39', 40 => '40',
        41 => '41', 42 => '42', 43 => '43', 44 => '44', 45 => '45', 46 => '46', 47 => '47', 48 => '48', 49 => '49',
    );

    // 是否复式
    public function isMulti()
    {
        return true;
    }

    // 供测试用 生成随机投注
    public function randomCodes()
    {
        return implode('', (array)array_rand(self::$filterArr, 1));
    }

    public function fromOld($codes)
    {
        // 01,02,03,04
        return implode(',', explode('|', $codes));
    }

    public function parse64($codes)
    {
        return true;
    }

    public function encode64($codes)
    {
        return $this->_encode64(explode(',', $codes));
    }

    public function regexp($sCodes)
    {
        $regexp = '/^[1-49]$/';
        if(!preg_match($regexp, $sCodes)) return false;

        return true;
    }

    public function count($sCodes)
    {
        return 1;
    }

    // 判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $exists = array_flip($numbers);
        if(isset($exists[$sCodes])) {
            return 1;
        }
        return 0;
    }
}
