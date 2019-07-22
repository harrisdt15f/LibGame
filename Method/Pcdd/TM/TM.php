<?php namespace App\Lib\Game\Method\Pcdd\TM;

use App\Lib\Game\Method\Pcdd\Base;

// 特码
class TM extends Base
{
    public $totalCount          = 1000;
    public static $filterArr    = array(
        0  =>    0, 1  =>    1,  2  =>   2,  3  =>   3,  4  =>   4,  5  =>   5,  6  =>   6,  7  =>   7,  8  =>   8,  9  =>   9,  10  => 10,
        11 => '11', 12 => '12', 13 => '13', 14 => '14', 15 => '15', 16 => '16', 17 => '17', 18 => '18', 19 => '19', 20 => '20',
        21 => '21', 22 => '22', 23 => '23', 24 => '24', 25 => '25', 26 => '26', 27 => '27',
    );

    // 展开
    public function expand($sCode, $pos = null)
    {
        $methodId = "T" . $sCode;
        $result[]   = array(
            'method_id' => $methodId,
            'codes'     => $sCode,
            'count'     => 1,
        );
        return $result;
    }

    // 是否复式
    public function isMulti()
    {
        return true;
    }

    // 供测试用 生成随机投注
    public function randomCodes()
    {
        return array_rand(self::$filterArr, 1);
    }


    public function regexp($sCodes)
    {
        $regexp = '/^[0-27]$/';
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
        $openCodeArr = array_flip($numbers);
        if(isset($openCodeArr[$sCodes])) {
            return 1;
        }
        return 0;
    }
}
