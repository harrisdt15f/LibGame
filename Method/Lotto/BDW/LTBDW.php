<?php namespace App\Lib\Game\Method\Lotto\BDW;

use App\Lib\Game\Method\Lotto\Base;

// 不定位
class LTBDW extends Base
{
    //01&02&03&04&05&06&07&08&09&10&11

    public static $filterArr = array('01'=>1, '02'=>1, '03'=>1, '04'=>1, '05'=>1, '06'=>1, '07'=>1, '08'=>1, '09'=>1, '10'=>1, '11'=>1);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $rand=rand(1,count(self::$filterArr));
        return implode('&',(array)array_rand(self::$filterArr,$rand));
    }

    public function fromOld($sCodes){
        return implode('&',explode('|',$sCodes));
    }

    public function regexp($sCodes)
    {
        //格式
        if (!preg_match("/^((0[1-9]&)|(1[01]&)){0,10}((0[1-9])|(1[01]))$/", $sCodes)) {
            return false;
        }

        //去重
        $t=explode("&",$sCodes);
        $filterArr = self::$filterArr;

        $temp = array_filter(array_unique($t),function($v) use ($filterArr) {
            return isset($filterArr[$v]);
        });

        if(count($temp)==0){
            return false;
        }

        return count($temp) == count($t);
    }

    public function count($sCodes)
    {
        //n

        $n = count(explode("&",$sCodes));

        return $n;
    }

    public function bingoCode(Array $numbers)
    {
        $numbers=array_flip($numbers);
        $result=[];
        $arr=array_keys(self::$filterArr);
        foreach($arr as $v){
            $result[]=intval(isset($numbers[$v]));
        }
        return [$result];
    }

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        return count(array_intersect($numbers,explode("&", $sCodes)));
    }

    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        $tmp=explode('&', $sCodes);
        $codes="'".implode("','",$tmp)."'";

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
    str:gsub("%w+",function(c) table.insert(_codes,c) end)

    for _,code in pairs(codes) do
        if code==_codes[1] or code==_codes[2] or code==_codes[3] then
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
        $codes=explode('&', $sCodes);
        $codes="'".implode("','",$codes)."'";

        $script='';
        //不同奖级的中奖金额
        $script.= <<<LUA


x={'01','02','03','04','05','06','07','08','09','10','11'}
codes={{$codes}}

for i1,a in pairs(x) do
for i2,b in pairs(x) do
for i3,c in pairs(x) do
for i4,d in pairs(x) do
for i5,e in pairs(x) do
	if i1==i2 or i1==i3 or i1==i4  or i1==i5
		or i2==i3  or i2==i4  or i2==i5
		or i3==i4  or i3==i5
		or i4==i5 then

	else
	    n=0
	    for _,code in pairs(codes) do
	        if code==a or code==b or code==c then
                n=n+1
	        end
	    end

	    if n>0 then
            cmd('zincrby','{$plan}',{$prizes[1]}*n,table.concat({a,b,c,d,e},' '))
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
