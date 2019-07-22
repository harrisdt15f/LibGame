<?php namespace App\Lib\Game\Method\Pcdd\BO;

use App\Lib\Game\Method\Pcdd\Base;

// 蓝波
class BLUE_B extends Base
{
    public $totalCount  = 1000;
    public static $code = array(2, 5, 8, 11, 17, 20, 23, 26);

    // 供测试用 生成随机投注
    public function randomCodes()
    {
        return "blue";
    }

    public function fromOld($codes)
    {
        return [];
    }

    // 蓝色
    public function regexp($sCode)
    {
        return $sCode == 'blue';
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
