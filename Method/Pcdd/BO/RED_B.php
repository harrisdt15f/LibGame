<?php namespace App\Lib\Game\Method\Pcdd\BO;

use App\Lib\Game\Method\Pcdd\Base;

// 红波
class RED_B extends Base
{
    public $totalCount  = 1000;
    public static $code = array(3, 6, 9, 12, 15, 18, 21, 24);

    // 供测试用 生成随机投注
    public function randomCodes()
    {
        return "red";
    }

    public function fromOld($codes)
    {
        return [];
    }

    public function regexp($sCode)
    {
        return $sCode == 'red';
    }

    public function count($sCodes)
    {
        return 1;
    }

    // 判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $tmCode = $numbers[0] + $numbers[1] + $numbers[2];
        if(in_array($tmCode, self::$code)) {
            return 1;
        }
        return 0;
    }
}
