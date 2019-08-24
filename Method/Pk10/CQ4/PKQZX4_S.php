<?php namespace App\Lib\Game\Method\Pk10\CQ4;

use App\Lib\Game\Method\Pk10\Base;

class PKQZX4_S extends Base
{
    // 01 02 03 04

    public static $filterArr = ['0' => 1, '1' => 1, '2' => 1, '3' => 1, '4' => 1, '5' => 1, '6' => 1, '7' => 1, '8' => 1, '9' => 1];

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $rand = 4;
        return implode(' ', (array)array_rand(self::$filterArr, $rand));
    }

    public function fromOld($codes)
    {
        //$sCodes
        return implode(',', explode('|', $codes));
    }

    public function regexp($sCodes)
    {
        //格式
        if (!preg_match('/^(((?!\&)(?!.*\&$)(?!\|)(?!.*\|$)(?!.*?\d\d)([\d]\&?){3})\|?){1,100000}$/', $sCodes)) {
            return false;
        }
        $aCode = explode('|', $sCodes);
        //　去重
        $unique = array_unique($aCode);
        $filter = array_filter($unique, static function ($value) {
            return ($value !== null && $value !== false && $value !== '');
        });
        $countFilter = count($filter);
        if (count($aCode) !== $countFilter) {
            return false;
        }
        // 校验
        foreach ($aCode as $sTmpCode) {
            if (!preg_match("/^((0[1-9]\s)|(10\s)){3}((0[1-9])|(10))$/", $sTmpCode)) {
                return false;
            }

            $aTmpCode = explode(' ', $sTmpCode);
            if (count($aTmpCode) != 4) {
                return false;
            }
            if (count($aTmpCode) != count(array_filter(array_unique($aTmpCode)))) {
                return false;
            }
            foreach ($aTmpCode as $c) {
                if (!isset(self::$filterArr[$c])) {
                    return false;
                }
            }
        }

        return true;
    }

    public function count($sCodes)
    {
        return count(explode(',', $sCodes));
    }

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $str = implode(' ', $numbers);
        $aCodes = explode(',', $sCodes);

        foreach ($aCodes as $code) {
            if ($code === $str) {
                return 1;
            }
        }
    }

}
