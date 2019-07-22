<?php namespace App\Lib\Game\Method\Ssc\BDW;

use App\Lib\Game\Method\Ssc\Base;

// 4星1码不定位
class BDW41 extends Base
{
    // 1&2&3&4&5&6&7&8
    public $all_count =10;

    public static $filterArr = array(0=>1,1=>1,2=>1,3=>1,4=>1,5=>1,6=>1,7=>1,8=>1,9=>1);

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
        if (!preg_match('/^(([0-9]&){0,9}[0-9])$/', $sCodes)) {
            return false;
        }

        $filterArr = self::$filterArr;

        $iNums = count(array_filter(array_unique(explode("&", $sCodes)),function($v) use ($filterArr) {
            return isset($filterArr[$v]);
        }));

        if($iNums==0){
            return false;
        }

        return $iNums == count(explode("&", $sCodes));
    }

    public function count($sCodes)
    {
        //C(n,1)
        $iNums = count(explode("&",$sCodes));
        return $this->getCombinCount($iNums,1);
    }

    //冷热 & 遗漏
    public function bingoCode(Array $numbers)
    {
        $numbers=array_flip($numbers);
        $result=[];
        $arr=array_keys(self::$filterArr);
        foreach($arr as $v){
            $result[]= (int)isset($numbers[$v]);
        }
        return [$result];
    }

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $temp =array();
        $nums = array_count_values ($numbers);

        $aCodes = explode("&", $sCodes);
        $i = 0;
        foreach ($aCodes as $code) {
            if(isset($temp[$code])) {
                continue;
            }
            $temp[$code]=1;
            if (isset($nums[$code]) && $nums[$code]>=1) {
                $i++;
            }
        }

        return $i;
    }


    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        //0&1&2&3&4&5&6&7&8&9

        $aCodes = explode('&', $sCodes);
        $aP1 = $this->getCombination($aCodes, 1);

        $tmp=[];
        foreach ($aP1 as $v1) {
            for($i=0;$i<=9;$i++){
                for($j=0;$j<=9;$j++){
                    for($k=0;$k<=9;$k++){
                        $tmp[$this->strOrder($i.$j.$k.$v1)]=1;
                    }
                }
            }
        }

        $codes="'".implode("','",array_keys($tmp))."'";
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
    _codes={_codes[{$pos[0]}],_codes[{$pos[1]}],_codes[{$pos[2]}],_codes[{$pos[3]}]}
    table.sort(_codes)

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
    public function lockScript($sCodes,$plan,$prizes)
    {
        //0&1&2&3&4&5&6&7&8&9

        $aCodes = explode('&', $sCodes);
        $codes=implode(",",$aCodes);

        $diff=array_values(array_diff($this->lottery->position,$this->levels[1]['position']));
        $intersect=array_values(array_intersect($this->lottery->position,$this->levels[1]['position']));
        $positions=implode(",",$this->lottery->position);
        $x3=count($this->lottery->position)==3;

        $script='';
        //不同奖级的中奖金额
        $script.= <<<LUA

codes={{$codes}}

for _,$intersect[0] in pairs({0,1,2,3,4,5,6,7,8,9}) do
for _,$intersect[1] in pairs({0,1,2,3,4,5,6,7,8,9}) do
for _,$intersect[2] in pairs({0,1,2,3,4,5,6,7,8,9}) do
for _,$intersect[3] in pairs({0,1,2,3,4,5,6,7,8,9}) do
    exists={}
    exists[$intersect[0]]=1
    exists[$intersect[1]]=1
    exists[$intersect[2]]=1
    exists[$intersect[3]]=1

    n =0;
    for _,v in pairs(codes) do
        if exists[v]==1 then
            n=n+1
        end
    end

    if n>=1 then
        times=fun.Pcnt(n,1)
        for _,$diff[0] in pairs({0,1,2,3,4,5,6,7,8,9}) do
            cmd('zincrby','{$plan}',{$prizes[1]}*times,table.concat({{$positions}}))
        end
    end
end
end
end
end

LUA;

        return $script;
    }
}
