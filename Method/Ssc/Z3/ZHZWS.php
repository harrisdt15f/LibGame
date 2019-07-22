<?php  namespace App\Lib\Game\Method\Ssc\Z3;

use App\Lib\Game\Method\Ssc\Base;

// 和值尾数
class ZHZWS extends Base
{
    //0&1&2&3&4&5&6&7&8&9 [0-9]
    public $all_count =10;
    public static $filterArr = array(0 => 1, 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1, 6 => 1, 7 => 1, 8 => 1, 9 => 1);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $rand=rand(1,10);
        return implode('&',(array)array_rand(self::$filterArr,$rand));
    }

    public function fromOld($codes)
    {
        return implode('&',explode('|',$codes));
    }

    public function regexp($sCodes)
    {
        if (!preg_match("/^([0-9]&){0,9}[0-9]$/", $sCodes)) {
            return false;
        }

        //去重
        $temp=explode("&",$sCodes);
        $arr = self::$filterArr;

        $iNums = count(array_filter(array_unique($temp),function($v) use ($arr) {
            return isset($arr[$v]);
        }));

        if($iNums==0){
            return false;
        }

        return $iNums == count($temp);
    }

    public function count($sCodes)
    {
        //C(n,1)
        $n = count(explode("&",$sCodes));
        return $this->getCombinCount($n,1);
    }

    public function bingoCode(Array $numbers)
    {
        $val=array_sum($numbers) % 10;
        $arr= array_keys(self::$filterArr);
        $result=[];
        foreach($arr as $pos=>$_code){
            $result[$pos]=intval($_code == $val);
        }

        return [$result];
    }

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $vals = str_split(array_sum($numbers));
        $val = array_pop($vals);

        $aCodes = explode('&', $sCodes);

        foreach ($aCodes as $code) {
            if ($code == $val) {
                return 1;
            }
        }

    }


    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        $codes=explode('&',$sCodes);
        $codes=implode(",",$codes);

        $pos=array_keys(array_intersect($this->lottery->position,$this->levels[1]['position']));
        array_walk($pos,function(&$v){$v++;});

        $script=
            <<<LUA

LUA;

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

codes={{$codes}}

_codes={}
for _,str in pairs(ret) do
    _codes={}
    str:gsub(".",function(c) table.insert(_codes,c) end)

    for _,i in pairs(codes) do
        if (i== (tonumber(_codes[{$pos[0]}])+tonumber(_codes[{$pos[1]}])+tonumber(_codes[{$pos[2]}]))%10 ) then
            do return 0 end
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
        //所选号码至少出现两次
        $codes=explode('&',$sCodes);
        $codes=implode(",",$codes);

        $diff=array_values(array_diff($this->lottery->position,$this->levels[1]['position']));
        $intersect=array_values(array_intersect($this->lottery->position,$this->levels[1]['position']));
        $positions=implode(",",$this->lottery->position);
        $x3=count($this->lottery->position)==3;

        //不同奖级的中奖金额
        $script= <<<LUA

codes={{$codes}}

for _,c in pairs(codes) do
    for _,{$intersect[0]} in pairs({0,1,2,3,4,5,6,7,8,9}) do
    for _,{$intersect[1]} in pairs({0,1,2,3,4,5,6,7,8,9}) do
    for _,{$intersect[2]} in pairs({0,1,2,3,4,5,6,7,8,9}) do
        if c==( tonumber($intersect[0])+tonumber($intersect[1])+tonumber($intersect[2]) )%10 then

LUA;
        if($x3){
            $script.= <<<LUA
            cmd('zincrby','{$plan}',{$prizes[1]},table.concat({{$positions}}, ''))

LUA;
        }else{
            $script.= <<<LUA

            for _,$diff[0] in pairs({0,1,2,3,4,5,6,7,8,9}) do
            for _,$diff[1] in pairs({0,1,2,3,4,5,6,7,8,9}) do
                    cmd('zincrby','{$plan}',{$prizes[1]},table.concat({{$positions}}, ''))
            end
            end

        end

LUA;
        }

        $script.= <<<LUA

    end
    end
    end
end

LUA;
        return $script;
    }

}
