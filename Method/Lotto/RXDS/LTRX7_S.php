<?php namespace App\Lib\Game\Method\Lotto\RXDS;

use App\Lib\Game\Method\Lotto\Base;

class LTRX7_S extends Base
{
    // 01 02,01 02,01 02,01 02

    public static $filterArr = array('01'=>1, '02'=>1, '03'=>1, '04'=>1, '05'=>1, '06'=>1, '07'=>1, '08'=>1, '09'=>1, '10'=>1, '11'=>1);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $rand=7;
        return implode(' ',(array)array_rand(self::$filterArr,$rand));
    }

    public function fromOld($codes)
    {
        return implode(',',explode('|',$codes));
    }

    public function regexp($sCodes)
    {

        $aCode = explode(",",$sCodes);

        //去重
        if(count($aCode) != count(array_filter(array_unique($aCode)))) return true;

        //校验
        foreach ($aCode as $sTmpCode) {
            $aTmpCode = explode(" ", $sTmpCode);
            if (count($aTmpCode) != 7) {
                return false;
            }
            if (count($aTmpCode) != count(array_filter(array_unique($aTmpCode)))) {
                return false;
            }
            foreach ($aTmpCode as $c) {
                if (!isset(self::$filterArr[$c])) {
                    return false;
                }
            }
        }

        return true;
    }

    public function count($sCodes)
    {
        return count(explode(",",$sCodes));
    }

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $aCodes = explode(',',$sCodes);
        $i=0;
        foreach ($aCodes as $code) {
            if(count(array_intersect(explode(' ',$code),$numbers)) ==5 ) $i++;
        }

        return $i;
    }


    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        //01&03&04&05
        $aCodes = explode(',', $sCodes);
        $codes=[];
        foreach($aCodes as $c){
            $c=explode(' ',$c);
            $codes[]="{'".implode("','",$c)."'}";
        }

        if(count($codes)){
            $codes=implode(",",$codes);
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
for _,codes2 in pairs(codes) do
    exists2={}
    for _,code in pairs(codes2) do
        exists2[code]=1
    end
    table.insert(exists,exists2)
end

_codes={}
for _,str in pairs(ret) do
    _codes={}
    str:gsub("%w+",function(c) table.insert(_codes,c) end)

    cnt=0
    for _,exists2 in pairs(exists) do
        if exists2[_codes[1]]==1 then cnt=cnt+1 end
        if exists2[_codes[2]]==1 then cnt=cnt+1 end
        if exists2[_codes[3]]==1 then cnt=cnt+1 end
        if exists2[_codes[4]]==1 then cnt=cnt+1 end
        if exists2[_codes[5]]==1 then cnt=cnt+1 end

        if cnt==5 then
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
        //01&03&04&05
        $aCodes = explode(',', $sCodes);
        $codes=[];
        foreach($aCodes as $c){
            $c=explode(' ',$c);
            $codes[]="{'".implode("','",$c)."'}";
        }

        if(count($codes)){
            $codes=implode(",",$codes);
        }else{
            $codes='';
        }

        $script='';
        //不同奖级的中奖金额
        $script.= <<<LUA

x={'01','02','03','04','05','06','07','08','09','10','11'}
codes={{$codes}}
exists={}
for _,codes2 in pairs(codes) do
    exists2={}
    for _,code in pairs(codes2) do
        exists2[code]=1
    end
    table.insert(exists,exists2)
end

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
	    for _,exists2 in pairs(exists) do
            if exists2[a]==1 then cnt=cnt+1 end
            if exists2[b]==1 then cnt=cnt+1 end
            if exists2[c]==1 then cnt=cnt+1 end
            if exists2[d]==1 then cnt=cnt+1 end
            if exists2[e]==1 then cnt=cnt+1 end

            if cnt==5 then
                cmd('zincrby','{$plan}',{$prizes[1]},table.concat({a,b,c,d,e},' '))
                break
            end
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
