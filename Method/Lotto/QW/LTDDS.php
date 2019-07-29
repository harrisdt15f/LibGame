<?php namespace App\Lib\Game\Method\Lotto\QW;

use App\Lib\Game\Method\Lotto\Base;
use Illuminate\Support\Facades\Validator;

// 定单双
class LTDDS extends Base
{
    // 5&4&3&2&1&0  // 单的个数
    public static $filterArr = array('5' => '5单0双', '4' => '4单1双', '3' => '3单2双', '2' => '2单3双', '1' => '1单4双', '0' => '0单5双');

    // 供测试用 生成随机投注
    public function randomCodes()
    {
        $rand = rand(1, count(self::$filterArr));
        return implode('&', (array)array_rand(self::$filterArr, $rand));
    }

    public function fromOld($sCodes)
    {
        return implode('&', explode('|', $sCodes));
    }

    public function toOld($sCodes)
    {
        return $this->unresolve($sCodes);
    }

    // 格式解析
    public function resolve($codes)
    {
        return strtr($codes, array_flip(self::$filterArr));
    }

    // 还原格式
    public function unresolve($codes)
    {
        return strtr($codes, self::$filterArr);
    }

    public function regexp($sCodes)
    {
        $regex = '/^((?! )(?!.*  $)(?!.* $)(([0-5]) ?){1,6})*$/';
        $data['code'] = $sCodes;
        $validator = Validator::make($data, [
            'code' => ['regex:'.$regex],//1码
            //'0 1 2 3 4 5'  十一选五 定单双 趣味
        ]);
        if ($validator->fails()) {
            return false;
        }
        return true;
    }

    public function count($sCodes)
    {
        return count(explode("&", $sCodes));
    }

    public function bingoCode(Array $numbers)
    {
        $val = 0;
        foreach ($numbers as $v) {
            if (intval($v) % 2 != 0) $val++;
        }
        $result = [];
        $result[] = intval($val == 5);
        $result[] = intval($val == 4);
        $result[] = intval($val == 3);
        $result[] = intval($val == 2);
        $result[] = intval($val == 1);
        $result[] = intval($val == 0);

        return [$result];
    }

    // 判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $aCodes = explode("&", $sCodes);

        $d = 0;
        foreach ($numbers as $n) {
            if (intval($n) % 2 !== 0) {
                $d++;
            }
        }

        $d .= '';

        if ($levelId == '1') {
            //0
            if ($d == '0' && in_array('0', $aCodes)) {
                return 1;
            }
        } elseif ($levelId == '2') {
            //5
            if ($d == '5' && in_array('5', $aCodes)) {
                return 1;
            }
        } elseif ($levelId == '3') {
            //1
            if ($d == '1' && in_array('1', $aCodes)) {
                return 1;
            }
        } elseif ($levelId == '4') {
            //4
            if ($d == '4' && in_array('4', $aCodes)) {
                return 1;
            }
        } elseif ($levelId == '5') {
            //2
            if ($d == '2' && in_array('2', $aCodes)) {
                return 1;
            }
        } elseif ($levelId == '6') {
            //3
            if ($d == '3' && in_array('3', $aCodes)) {
                return 1;
            }
        }
    }
}
