<?php namespace App\Lib\Game\Method\Ssc\H3;

use App\Lib\Game\Method\Ssc\Base;
use Illuminate\Support\Facades\Validator;

// 前直选3 单式
class HZX3_S extends Base
{
    // 12345,12345,12345,12345,12345,12345,
    public $all_count = 1000;
    public static $filterArr = array(0 => 1, 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1, 6 => 1, 7 => 1, 8 => 1, 9 => 1);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $rand = 3;
        return implode('', (array)array_rand(self::$filterArr, $rand));
    }

    public function fromOld($codes)
    {
        //112|123|341
        return implode(',', explode('|', $codes));
    }

    public function parse64($codes)
    {
        if (strpos($codes, 'base64:') !== false) {
            $ex = explode('base64:', $codes);
            $codes = $this->_parse64($ex[1], 3);
            if (is_array($codes)) {
                $codes = implode(',', $codes);
            }
        }
        return $codes;
    }

    public function encode64($codes)
    {
        return $this->_encode64(explode(',', $codes));
    }

    public function regexp($sCodes)
    {
        $data['code'] = explode('|', $sCodes);
        $validator = Validator::make($data, [
            'code' => 'required|array|max:100000', //只能十万个号码能传过来
            'code.*' => ['regex:/^((?!\&)(?!.*\&$)(?!.*?\&\&)[\d&]{1,5}?)$/'], //1&2&3
        ]);
        if ($validator->fails()) {
            return false;
        }

        //重复号码
        $temp = explode(",", $sCodes);
        $i = count(array_filter(array_unique($temp)));
        if ($i != count($temp)) return false;

        return true;
    }

    public function count($sCodes)
    {
        return count(explode(",", $sCodes));
    }

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $str = implode('', $numbers);
        $exists = array_flip(explode(',', $sCodes));
        return intval(isset($exists[$str]));
    }
}
