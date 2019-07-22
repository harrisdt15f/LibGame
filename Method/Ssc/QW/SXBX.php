<?php namespace App\Lib\Game\Method\Ssc\QW;

use App\Lib\Game\Method\Ssc\Base;

//三星报喜
class SXBX extends Base
{
    //0&1&2&3&4&5&6&7&8&9
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
        if (!preg_match("/^([0-9]&){0,9}[0-9]$/", $sCodes)) {
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
        return count(explode("&",$sCodes));
    }

    public function bingoCode(Array $numbers)
    {
        $arr=array_keys(self::$filterArr);
        $counts=array_count_values($numbers);

        $result=[];
        foreach($arr as $pos=>$_code){
            $result[$pos]=intval(isset($counts[$_code]) && $counts[$_code]>=3);
        }

        return [$result];
    }

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {

        $aCodes = array_flip(explode('&', $sCodes));

        $flip = array_filter(array_count_values($numbers), function ($v) {
            return $v >= 3;
        });

        $e = array_intersect_key($flip, $aCodes);

        $cnt = count($e);

        if ($cnt > 0) {
            return $cnt;
        }

    }


    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        $codes=explode('&',$sCodes);
        $codes="'".implode("','",$codes)."'";

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
        flag=0
        for _,j in pairs(_codes) do
            if (i==j) then
                flag=flag+1
            end
        end

        if(flag>=3) then
            do return 0 end
        end
    end
end
do return 0 end

LUA;

        return $script;
    }

    //写入封锁值
    public function lockScript($sCodes,$plan,$prizes)
    {
        //所选号码至少出现两次
        $codes=explode('&',$sCodes);
        $codes=implode(",",$codes);

        //不同奖级的中奖金额
        $script= <<<LUA

x={0,1,2,3,4,5,6,7,8,9}
codes={{$codes}}

for _,i in pairs(x) do
for _,j in pairs(x) do
for _,k in pairs(x) do
for _,l in pairs(x) do
for _,m in pairs(x) do
    for _,c in pairs(codes) do
        n=0
        if c==i then n=n+1 end
        if c==j then n=n+1 end
        if c==k then n=n+1 end
        if c==l then n=n+1 end
        if c==m then n=n+1 end

        if n>=3 then
            cmd('zincrby','{$plan}',{$prizes[1]},table.concat({i,j,k,l,m}, ''))
        end
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
