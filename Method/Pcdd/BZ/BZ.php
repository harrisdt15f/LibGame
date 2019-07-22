<?php namespace App\Lib\Game\Method\Pcdd\BZ;

use App\Lib\Game\Method\Pcdd\Base;

// 包子
class BZ extends Base
{
    public $totalCount  = 10;

    // 供测试用 生成随机投注
    public function randomCodes()
    {

        $code = "bz";
        return $code;
    }

    public function regexp($sCodes)
    {
        return $sCodes === 'bz';
    }

    public function count($sCodes)
    {
        return 1;
    }

    public function bingoCode(Array $numbers)
    {
        return [];
    }

    // 判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $openCodeArr = array_flip($numbers);
        return $openCodeArr[0] == $openCodeArr[1] && $openCodeArr[1] == $openCodeArr[2];
    }
}
