<?php namespace App\Lib\Game\Method\Ssc\SX;

use App\Lib\Game\Method\Ssc\Base;

class ZX4 extends Base
{
    // 0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9
    public $all_count =10000;
    public static $filterArr = array(0 => 1, 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1, 6 => 1, 7 => 1, 8 => 1, 9 => 1);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $arr=[];
        $rand=rand(1,10);
        $arr[]=implode('&',(array)array_rand(self::$filterArr,$rand));
        $rand=rand(1,10);
        $arr[]=implode('&',(array)array_rand(self::$filterArr,$rand));
        $rand=rand(1,10);
        $arr[]=implode('&',(array)array_rand(self::$filterArr,$rand));
        $rand=rand(1,10);
        $arr[]=implode('&',(array)array_rand(self::$filterArr,$rand));

        return implode('|',$arr);
    }

    public function regexp($sCodes)
    {
        $regexp = '/^(([0-9]&){0,9}[0-9])\|(([0-9]&){0,9}[0-9])\|(([0-9]&){0,9}[0-9])\|(([0-9]&){0,9}[0-9])$/';
        if(!preg_match($regexp,$sCodes)) return false;

        $filterArr = self::$filterArr;

        //去重
        $sCodes = explode("|", $sCodes);
        foreach($sCodes as $codes){
            $temp = explode('&',$codes);
            if(count($temp) != count(array_filter(array_unique($temp),function($v) use($filterArr) {
                    return isset($filterArr[$v]);
                }))) return false;

            if(count($temp)==0){
                return false;
            }
        }

        return true;
    }

    public function count($sCodes)
    {
        //n1*n2*n3*n4
        $cnt = 1;
        $temp = explode('|',$sCodes);

        foreach($temp as $c){
            $cnt *= count(explode('&',$c));
        }

        return $cnt;
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
        $aCodes = explode('|', $sCodes);

        $preg = "|[" . str_replace('&', '', $aCodes[0]) . "][" . str_replace('&', '', $aCodes[1]) . "][" . str_replace('&', '', $aCodes[2]) . "][" . str_replace('&', '', $aCodes[3]) . "]|";

        if (preg_match($preg, implode("", $numbers))) {
            return 1;
        }

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
codes3={{$codes[2]}}
codes4={{$codes[3]}}
_codes={}
for _,str in pairs(ret) do
    _codes={}
    str:gsub(".",function(c) table.insert(_codes,c) end)

    for _,code1 in pairs(codes1) do
    for _,code2 in pairs(codes2) do
    for _,code3 in pairs(codes3) do
    for _,code4 in pairs(codes4) do
        if code1==_codes[{$pos[0]}] and code2==_codes[{$pos[1]}] and code3==_codes[{$pos[2]}]  and code4==_codes[{$pos[3]}] then
            do return 0 end
        end
    end
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

        //不同奖级的中奖金额
        $script= <<<LUA

codes1={{$codes[0]}}
codes2={{$codes[1]}}
codes3={{$codes[2]}}
codes4={{$codes[3]}}

for _,$intersect[0] in pairs(codes1) do
for _,$intersect[1] in pairs(codes2) do
for _,$intersect[2] in pairs(codes3) do
for _,$intersect[3] in pairs(codes4) do
    for _,$diff[0] in pairs({0,1,2,3,4,5,6,7,8,9}) do
        cmd('zincrby','{$plan}',{$prizes[1]},table.concat({{$positions}}))
    end
end
end
end
end

LUA;
        return $script;
    }

}
