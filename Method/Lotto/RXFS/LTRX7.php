<?php namespace App\Lib\Game\Method\Lotto\RXFS;

use App\Lib\Game\Method\Lotto\Base;

class LTRX7 extends Base
{
    //01&02&03&04&05&06&07&08

    public static $filterArr = array('01'=>1, '02'=>1, '03'=>1, '04'=>1, '05'=>1, '06'=>1, '07'=>1, '08'=>1, '09'=>1, '10'=>1, '11'=>1);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $rand=rand(7,10);
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

        $filterArr= self::$filterArr;

        $aCode = explode("|", $sCodes);
        foreach ($aCode as $sCode) {
            $t=explode("&", $sCode);
            $iUniqueCount = count(array_filter(array_unique($t),function($v) use($filterArr) {
                return isset($filterArr[$v]);
            }));
            if ($iUniqueCount != count($t)) {
                return false;
            }
            if($iUniqueCount<7){
                return false;
            }
        }

        return true;
    }

    public function count($sCodes)
    {
        return $this->getCombinCount(count(explode("&", $sCodes)),7);
    }

    public function bingoCode(Array $numbers)
    {
        $exists=array_flip($numbers);
        $arr= array_keys(self::$filterArr);
        $result=[];
        foreach($arr as $pos=>$_code){
            $result[]=intval(isset($exists[$_code]));
        }

        return [$result];
    }

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $len=7;
        $aCodes = explode('&', $sCodes);
        $iRates = count(array_intersect($aCodes, $numbers));
        if ($iRates != 5) {
            return 0;
        }

        return $this->GetCombinCount(count($aCodes) - 5, $len - 5);
    }


    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        //01&03&04&05
        $codes = explode('&', $sCodes);

        if(count($codes)){
            $codes="'".implode("','",$codes)."'";
        }else{
            $codes='';
        }

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
exists={}
for _,e in pairs(codes) do exists[e]=1 end

for _,str in pairs(ret) do
    _codes={}
    str:gsub("%w+",function(c) table.insert(_codes,c) end)

    cnt=0
    for _,_code in pairs(codes) do
        if exists[_code]==1 then cnt=cnt+1 end
    end

    if cnt==5 then
        do return 0 end
    end

end

do return 1 end

LUA;

        return $script;
    }

    //写入封锁值
    public function lockScript($sCodes,$plan,$prizes)
    {
        //01&03&04&05
        $codes = explode('&', $sCodes);
        $len=count($codes);
        if(count($codes)){
            $codes="'".implode("','",$codes)."'";
        }else{
            $codes='';
        }

        $script='';
        //不同奖级的中奖金额
        $script.= <<<LUA

x={'01','02','03','04','05','06','07','08','09','10','11'}
codes={{$codes}}
times= fun.Pcnt({$len}-5,2)

exists={}
for _,e in pairs(codes) do exists[e]=1 end

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
	    cnt=0
	    if exists[a]==1 then cnt=cnt+1 end
	    if exists[b]==1 then cnt=cnt+1 end
	    if exists[c]==1 then cnt=cnt+1 end
	    if exists[d]==1 then cnt=cnt+1 end
	    if exists[e]==1 then cnt=cnt+1 end

        if cnt==5 then
            cmd('zincrby','{$plan}',{$prizes[1]}*times,table.concat({a,b,c,d,e},' '))
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
