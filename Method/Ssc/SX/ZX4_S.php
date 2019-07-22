<?php namespace App\Lib\Game\Method\Ssc\SX;

use App\Lib\Game\Method\Ssc\Base;
use Illuminate\Support\Facades\Validator;

class ZX4_S extends Base
{
    // 12345,12345,12345,12345,12345,12345,
    public $all_count = 10000;
    public static $filterArr = array(0 => 1, 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1, 6 => 1, 7 => 1, 8 => 1, 9 => 1);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $rand = 4;
        return implode('', (array)array_rand(self::$filterArr, $rand));
    }

    public function fromOld($codes)
    {
        //1121|2311|3411
        return implode(',', explode('|', $codes));
    }

    public function parse64($codes)
    {
        if (strpos($codes, 'base64:') !== false) {
            $ex = explode('base64:', $codes);
            $codes = $this->_parse64($ex[1], 4);
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

    /**
     * @param $sCodes
     * @return bool
     */
    public function regexp($sCodes)
    {
        $data['code'] = explode('|', $sCodes);
        $validator = Validator::make($data, [
            'code' => 'required|array|max:100000',//只能十万万个号码能传过来
            'code.*' => ['regex:/^((?!\&)(?!.*\&$)(?!.*?\&\&)[\d&]{1,9}?)$/'],//1&2&3&4 ....
        ]);
        if ($validator->fails()) {
            return false;
        }
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


    //检查封锁
    public function tryLockScript($sCodes, $plan, $prizes, $lockvalue)
    {
        $aCodes = explode(',', $sCodes);
        $codes = [];
        foreach ($aCodes as $code) {
            $codes[$code] = 1;
        }
        $codes = "'".implode("','", array_keys($codes))."'";

        $pos = array_keys(array_intersect($this->lottery->position, $this->levels[1]['position']));
        array_walk($pos, function (&$v) {
            $v++;
        });

        $script =
            <<<LUA

LUA;

        $max = $lockvalue - $prizes[1];
        $script .= <<<LUA

exists=cmd('exists','{$plan}')

if exists==0 and {$max}<0 then
    do return 0 end
end

ret=cmd('zrangebyscore','{$plan}',{$max},'+inf')
if (#ret==0) then
    do return 1 end
end

codes={{$codes}}
_codes={}

for _,str in pairs(ret) do
    _codes={}
    str:gsub(".",function(c) table.insert(_codes,c) end)
    _codes={_codes[{$pos[0]}],_codes[{$pos[1]}],_codes[{$pos[2]}],_codes[{$pos[3]}]}
    _code=table.concat(_codes)

    for _,code in pairs(codes) do
        if code==_code then
            do return 0 end
        end
    end
end


do return 1 end

LUA;

        return $script;
    }

    //写入封锁值
    public function lockScript($sCodes, $plan, $prizes)
    {
        $aCodes = explode(',', $sCodes);
        $codes = "'".implode("','", $aCodes)."'";

        $diff = array_values(array_diff($this->lottery->position, $this->levels[1]['position']));
        $intersect = array_values(array_intersect($this->lottery->position, $this->levels[1]['position']));
        $positions = implode(",", $this->lottery->position);

        //不同奖级的中奖金额
        $script = <<<LUA

codes={{$codes}}
for _,code in pairs(codes) do
    mix={}
    code:gsub(".",function(c) table.insert(mix,c) end)
    $intersect[0]=mix[1]
    $intersect[1]=mix[2]
    $intersect[2]=mix[3]
    $intersect[3]=mix[4]

    for _,$diff[0] in pairs({0,1,2,3,4,5,6,7,8,9}) do
        cmd('zincrby','{$plan}',{$prizes[1]},table.concat({{$positions}}))
    end

end

LUA;

        return $script;
    }

}
