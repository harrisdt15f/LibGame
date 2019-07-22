<?php namespace App\Lib\Game\Method\Ssc\EX;

use App\Lib\Game\Method\Ssc\Base;

// 组选和值
class QZU2HZ extends Base
{
    //1&2&3&4&5&6
    public $all_count =45;
    public static $filterArr = array(1 => 1, 2 => 1, 3 => 2, 4 => 2, 5 => 3, 6 => 3, 7 => 4, 8 => 4, 9 => 5, 10 => 4, 11 => 4, 12 => 3, 13 => 3, 14 => 2, 15 => 2, 16 => 1, 17 => 1);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $rand=rand(1,count(self::$filterArr));
        return implode('&',(array)array_rand(self::$filterArr,$rand));
    }

    public function fromOld($codes)
    {
        return implode('&',explode('|',$codes));
    }

    public function regexp($sCodes)
    {
        //去重
        $t=explode("&",$sCodes);
        $temp =array_unique($t);
        $arr = self::$filterArr;

        $temp = array_filter($temp,function($v) use ($arr) {
            return isset($arr[$v]);
        });

        if(count($temp)==0){
            return false;
        }

        return count($temp) == count($t);
    }

    public function count($sCodes)
    {
        //枚举之和
        $n = 0;
        $temp = explode('&',$sCodes);
        foreach($temp as $c){
            $n += self::$filterArr[$c];
        }

        return $n;
    }

    public function bingoCode(Array $numbers)
    {
        //对子号
        if(count(array_count_values($numbers))==1) return [];

        $val=array_sum($numbers);
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

        $val = array_sum($numbers);

        $aCodes = explode('&', $sCodes);

        //不包含对子
        if ($numbers[0] != $numbers[1]) {
            foreach ($aCodes as $code) {
                if ($val == $code) {
                    return 1;
                }
            }
        }

    }


    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        //1&2&3&4&5&6&27

        $aCodes = explode('&', $sCodes);
        $exists=array_flip($aCodes);

        $codes=[];
        for ($i=0;$i<=9;$i++) {
            for ($j=0;$j<=9;$j++) {
                if($i==$j) continue;//不含对子号
                if(isset($exists[$i+$j])){
                    $codes[$this->strOrder($i.$j)]=1;
                }
            }
        }

        if(count($codes)>0){
            $codes="'".implode("','",array_keys($codes))."'";
        }else{
            $codes="";
        }

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
    _codes={_codes[{$pos[0]}],_codes[{$pos[1]}]}
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
        //1&2&3&4&5&6&27

        $aCodes = explode('&', $sCodes);
        $exists=array_flip($aCodes);

        $codes=[];
        for ($i=0;$i<=9;$i++) {
            for ($j=0;$j<=9;$j++) {
                if($i==$j) continue;//不含对子号
                if(isset($exists[$i+$j])){
                    $codes[$i.$j]=1;
                }
            }
        }

        if(count($codes)>0){
            $codes="'".implode("','",array_keys($codes))."'";
        }else{
            $codes="";
        }

        $diff=array_values(array_diff($this->lottery->position,$this->levels[1]['position']));
        $intersect=array_values(array_intersect($this->lottery->position,$this->levels[1]['position']));
        $positions=implode(",",$this->lottery->position);
        $x3=count($this->lottery->position)==3;

        //不同奖级的中奖金额
        $script= <<<LUA

codes={{$codes}}
for _,_code in pairs(codes) do
    mix={}
    _code:gsub(".",function(c) table.insert(mix,c) end)
    $intersect[0]=mix[1]
    $intersect[1]=mix[2]

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

LUA;

        return $script;
    }

}
