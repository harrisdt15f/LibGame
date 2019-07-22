<?php namespace App\Lib\Game\Method\Pcdd\DXDS;

use App\Lib\Game\Method\Pcdd\Base;

// 大双
class BIG_EVEN extends Base
{
    public $all_count = 1000;

    // 供测试用 生成随机投注
    public function randomCodes()
    {
        return ["be"];
    }

    public function regexp($sCode)
    {
        return $sCode == 'be';
    }

    public function count($sCodes)
    {
        return 1;
    }

    // 判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $count = $numbers[0] + $numbers[1] + $numbers[2];

        $res = $count >= 14 && $count % 2 === 0 ? 1 : 0;

        return $res;
    }
}
