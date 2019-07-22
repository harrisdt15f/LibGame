<?php namespace App\Lib\Game\Method\Ssc\DWD;

use App\Lib\Game\Method\BaseDWD;
use App\Lib\Game\Method\Ssc\Base;
use Illuminate\Support\Facades\Validator;

class DWD extends Base
{
    use BaseDWD;

    public $all_count = 10;

    public static $filterArr = [0 => 1, 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1, 6 => 1, 7 => 1, 8 => 1, 9 => 1];

    // 供测试用 生成随机投注
    public function randomCodes()
    {
        $arr = [];
        $rand = rand(1, 10);
        return implode('&', (array)array_rand(self::$filterArr, $rand));
    }

    public function fromOld($codes)
    {
        return implode('&', explode('|', $codes));
    }

    public function regexp($sCodes)
    {
        $data['code'] = $sCodes;
        $validator = Validator::make($data, [
            'code' => ['regex:/^((?!\&)(?!.*\&$)(?!.*?\&\&)(?!.*?\&\|)(?!.*?\d\d)[0-9&]{0,19}\|?){1,5}$/'],
            //0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9  定位胆
        ]);
        if ($validator->fails()) {
            return false;
        }
        return true;
    }

    public function count($sCodes)
    {
        // n
        return count(explode('&', $sCodes));
    }

    public function bingoCode(Array $numbers)
    {
        $result = [];
        $arr = array_keys(self::$filterArr);

        foreach ($numbers as $pos => $code) {
            $tmp = [];
            foreach ($arr as $_code) {
                $tmp[] = intval($code == $_code);
            }
            $result[$pos] = $tmp;
        }

        return $result;
    }

    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $codes = explode('&', $sCodes);
        $exists = array_flip($numbers);
        foreach ($codes as $c) {
            if (isset($exists[$c])) {
                return 1;
            }
        }
    }
}
