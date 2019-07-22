<?php namespace App\Lib\Game\Method\K3\SLH;

use App\Lib\Game\Method\K3\Base;

// 三连号
class SLH extends Base
{
    // 三连号
    public static $filterArr = array('123' =>1, '234' => 1, '345' => 1, '456' => 1);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        return '通选';
    }

    public function fromOld($codes)
    {
        return str_replace('0','t',$codes);
    }

    //格式解析
    public function resolve($codes)
    {
        return strtr($codes,array('通选'=>'t'));
    }

    //还原格式
    public function unresolve($codes)
    {
        return strtr($codes,array('t'=>'通选'));
    }

    public function regexp($sCodes)
    {
        //去重
        return $sCodes =='t';
    }

    public function count($sCodes)
    {
        return 1;
    }

    public function bingoCode(Array $numbers)
    {
        sort($numbers);
        if( ($numbers[2]-$numbers[1])!=1 || ($numbers[1]-$numbers[0])!=1) return [[0]];

        return [[1]];
    }

    // 判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        //三连号通选：当期开奖号码为三个相连的号码(仅限：123、234、345、456)，即中奖。
        $str = implode('', $numbers);

        if (isset(self::$filterArr[$str])) {
            return 1;
        }
    }

}
