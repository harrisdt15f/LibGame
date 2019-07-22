<?php namespace App\Lib\Game\Method\Pcdd\DXDS;

use App\Lib\Game\Method\Pcdd\Base;

// 大
class PCDDDXDS extends Base
{
    public $all_count = 1000;

    public static $methods = [
        'b'     => "BIG",
        's'     => "SMALL",
        'o'     => "ODD",
        'e'     => "EVEN",
        'bo'    => "BIG_ODD",
        'be'    => "BIG_EVEN",
        'so'    => "SUPER_ODD",
        'se'    => "SUPER_EVEN",
        'sb'    => "SUPER_BIG",
        'ss'    => "SUPER_SMALL",
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

    // 供测试用 生成随机投注
    public function randomCodes()
    {
        return ["b"];
    }


    public function regexp($sCode)
    {
        return $sCode == 'b';
    }

    public function count($sCodes)
    {
        return 1;
    }

    // 判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $count = $numbers[0] + $numbers[1] + $numbers[2];

        $res = $count >= 14 ? 1 : 0;

        return $res;
    }
}
