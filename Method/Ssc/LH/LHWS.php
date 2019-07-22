<?php namespace App\Lib\Game\Method\Ssc\LH;

use App\Lib\Game\Method\Ssc\Base;
use Illuminate\Support\Facades\Validator;

class LHWS extends Base
{
    // 1&2&3龙虎和
    public $all_count =3;
    public static $filterArr = array(0 => 1, 1 => 1, 2 => 1, 3 => 1);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $arr=[];
        $rand=rand(1,10);
        $arr[]=implode('&',(array)array_rand(self::$filterArr,$rand));
        $rand=rand(1,10);
        $arr[]=implode('&',(array)array_rand(self::$filterArr,$rand));

        return implode('|',$arr);
    }

    public function regexp($sCodes)
    {
        $data['code'] = $sCodes;
        $validator = Validator::make($data, [
            'code' => ['regex:/^((?!\&)(?!.*\&$)(?!.*?\&\&)(?!.*?\d\d)[0-2&]{1,5})$/'],//0&1&2 龙虎和
        ]);
        if ($validator->fails()) {
            return false;
        }
        return true;
    }

    public function count($sCodes)
    {
        $temp = explode('&', $sCodes);
        $temp = array_unique($temp);
        return count($temp);
    }

    public function bingoCode(Array $numbers)
    {
        $result=[];
        $arr=array_keys(self::$filterArr);

        foreach($numbers as $pos=>$code){
            $tmp=[];
            foreach($arr as $_code){
                $tmp[]=intval($code==$_code);
            }
            $result[$pos]=$tmp;
        }

        return $result;
    }

    public function assertLevel($levelId, $sCodes, Array $numbers)
    {

        $aCodes = explode('&', $sCodes);
        $w = $numbers[4];
        $q = $numbers[3];

        $count = 0;
        if ($w > $q && in_array(1, $aCodes)) {
            $count = 1;
        }

        if ($w == $q && in_array(3, $aCodes)) {
            $count = 1;
        }

        if ($w <= $q && in_array(2, $aCodes)) {
            $count = 1;
        }

        return $count;
    }



    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        $codes=explode('|',$sCodes);
        foreach($codes as &$code){
            $code="'".implode("','",explode('&',$code))."'";
        }

        $pos=array_keys(array_intersect($this->lottery->position,$this->levels[1]['position']));
        array_walk($pos,function(&$v){$v++;});

        $script=
            <<<LUA

LUA;

        //检查各奖级最大值,如果最大值没有出现超过的值，则略过
        //封锁值 <= 总封锁值＋销量－奖金
        $max=$lockvalue-$prizes[1];
        $script.= <<<LUA

exists=cmd('exists','{$plan}')

if exists==0 and {$max}<0 then
    do return 0 end
end

ret=cmd('zrangebyscore','{$plan}',{$max},'+inf')

if (#ret==0) then
    do return 1 end
end

codes1={{$codes[0]}}
codes2={{$codes[1]}}
_codes={}
for _,str in pairs(ret) do
    _codes={}
    str:gsub(".",function(c) table.insert(_codes,c) end)

    for _,code1 in pairs(codes1) do
    for _,code2 in pairs(codes2) do
        if code1==_codes[{$pos[0]}] and code2==_codes[{$pos[1]}] then
            do return 0 end
        end
    end
    end
end

do return 1 end

LUA;

        return $script;
    }

    //写入封锁值
    public function lockScript($sCodes,$plan,$prizes)
    {
        $codes=explode('|',$sCodes);
        foreach($codes as &$code){
            $code=str_replace('&',',',$code);
        }

        $diff=array_values(array_diff($this->lottery->position,$this->levels[1]['position']));
        $intersect=array_values(array_intersect($this->lottery->position,$this->levels[1]['position']));
        $positions=implode(",",$this->lottery->position);
        $x3=count($this->lottery->position)==3;

        //不同奖级的中奖金额
        $script= <<<LUA

codes1={{$codes[0]}}
codes2={{$codes[1]}}

for _,$intersect[0] in pairs(codes1) do
for _,$intersect[1] in pairs(codes2) do

LUA;

        if($x3){
            $script.=<<<LUA
        for _,$diff[0] in pairs({0,1,2,3,4,5,6,7,8,9}) do
            cmd('zincrby','{$plan}',{$prizes[1]},table.concat({{$positions}}))
        end

LUA;
        }else{
            $script.=<<<LUA
        for _,$diff[0] in pairs({0,1,2,3,4,5,6,7,8,9}) do
        for _,$diff[1] in pairs({0,1,2,3,4,5,6,7,8,9}) do
        for _,$diff[2] in pairs({0,1,2,3,4,5,6,7,8,9}) do
            cmd('zincrby','{$plan}',{$prizes[1]},table.concat({{$positions}}))
        end
        end
        end

LUA;
        }
        $script.=<<<LUA

end
end

LUA;
        return $script;
    }

}
