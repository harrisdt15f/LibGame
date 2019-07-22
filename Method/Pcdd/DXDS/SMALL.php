<?php namespace App\Lib\Game\Method\Pcdd\DXDS;

use App\Lib\Game\Method\Pcdd\Base;

// 小
class SMALL extends Base
{
    public $all_count = 1000;

    //供测试用 生成随机投注
    public function randomCodes()
    {
        return ["s"];
    }


    public function regexp($sCode)
    {
        return !($sCode != 's');
    }

    public function count($sCodes)
    {
        return 1;
    }

    // 判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $count = $numbers[0] + $numbers[1] + $numbers[2];

        $res = $count <= 13 ? 1 : 0;

        return $res;
    }
}
