<?php namespace App\Lib\Game\Method\K3\DTYS;

use App\Lib\Game\Method\K3\Base;
use Illuminate\Support\Facades\Validator;

// 单挑一筛
class DTYS extends Base
{

    public $all_count = 6;
    public static $filterArr = array(1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1, 6 => 1);

    // 供测试用 生成随机投注
    public function randomCodes() {
        $rand = rand(1, 6);
        return $rand;
    }

    public function fromOld($codes) {
        // 0123|0123
        $codes = str_replace(array('0', '1', '2', '3'), array('b', 's', 'a', 'd'), $codes);
        $ex=explode('|', $codes);
        $ex[0]= implode('&', str_split($ex[0]));
        $ex[1]= implode('&', str_split($ex[1]));
        return implode('|', $ex);
    }

    public function regexp($sCodes)
    {
        $data['code'] = $sCodes;
        $validator = Validator::make($data, [
            'code' => ['regex:/^(?!\|)(?!.*\|\|$)(?!.*\|$)(([1-6])\|?){1,6}$/'],//6|5|4|3|2|1 单挑一骰快三
        ]);
        if ($validator->fails()) {
            return false;
        }
        return true;
    }

    public function count($sCodes)
    {
        $count = count(explode("&", $sCodes));
        return $count;
    }

    public function bingoCode(Array $numbers)
    {
        $result = [];
        $arr    = array_keys(self::$filterArr);

        foreach($numbers as $pos => $code) {
            $tmp = [];
            foreach($arr as $_code) {
                $tmp[] = intval($code == $_code);
            }
            $result[$pos] = $tmp;
        }

        return $result;
    }

    /**
     * 判定 中奖注单
     * @param $levelId
     * @param $sCodes
     * @param array $numbers
     * @return int
     */
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        // 投注内容
        $aCodes = explode("&", $sCodes);

        $i      = 0;
        $temp   = [];
        foreach ($aCodes as $code) {
            if(isset($temp[$code])) {
                continue;
            }
            if (in_array($code, $numbers)) {
                $temp[$code]=1;
                $i++;
            }
        }

        return $i;
    }
}
