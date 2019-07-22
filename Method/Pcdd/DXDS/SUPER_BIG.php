<?php namespace App\Lib\Game\Method\Pcdd\DXDS;

use App\Lib\Game\Method\Pcdd\Base;

// 超大
class SUPER_BIG extends Base
{
    public $totalCount = 1000;

    // 供测试用 生成随机投注
    public function randomCodes()
    {
        return ['sb'];
    }

    public function regexp($sCode)
    {
        return !($sCode != 'sb');
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
