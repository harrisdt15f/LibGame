<?php namespace App\Lib\Game\Method\Pcdd\BO;

use App\Lib\Game\Method\Pcdd\Base;

// 波
class BO extends Base
{
    public $totalCount  = 1000;
    public static $methods = [
        'red'       => "RED_B",
        'green'     => "GREEN_B",
        'blue'      => "BLUE_B",
    ];

    // 展开
    public function expand($sCode, $pos = null)
    {
        $methodId   = self::$methods[$sCode];
        $result[]   = array(
            'method_id' => $methodId,
            'codes'     => $sCode,
            'count'     => 1,
        );
        return $result;
    }

    //供测试用 生成随机投注
    public function randomCodes()
    {
        return array_rand(self::$code);
    }

    public function fromOld($codes)
    {
        return [];
    }

    public function regexp($sCode)
    {
        if (in_array($sCode, self::$code)) {
            return true;
        }
        return false;
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
