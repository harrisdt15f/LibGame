<?php namespace App\Lib\Game\Method\Lotto\QW;

use App\Lib\Game\Method\Lotto\Base;

// 猜中位
class LTCZW extends Base
{
    //3&4&5&6&7&8&9  [3-9]

    public static $filterArr = array('3'=>'03','4'=>'04','5'=>'05','6'=>'06','7'=>'07','8'=>'08','9'=>'09');

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $rand=rand(1,count(self::$filterArr));
        return implode('&',(array)array_rand(self::$filterArr,$rand));
    }

    public function fromOld($sCodes){
        return implode('&',explode('|',strtr($sCodes,array_flip(self::$filterArr))));
    }

    public function regexp($sCodes)
    {
        //格式
        if (!preg_match("/^(([0-9]&)*[0-9])$/", $sCodes)) {
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
        sort($numbers);
        $val=$numbers[2];
        $result=[];
        $arr=self::$filterArr;
        foreach($arr as $v){
            $result[]=intval($v==$val);
        }
        return [$result];
    }

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $aCodes = explode("&", $sCodes);

        sort($numbers);

        $z = intval($numbers[2]);

        if($levelId == '1'){
            //3,9
            if(in_array($z,array(3,9)) && in_array($z,$aCodes)){
                return 1;
            }
        }elseif($levelId == '2'){
            //4,8
            if(in_array($z,array(4,8)) && in_array($z,$aCodes)){
                return 1;
            }
        }elseif($levelId == '3'){
            //5,7
            if(in_array($z,array(5,7)) && in_array($z,$aCodes)){
                return 1;
            }
        }elseif($levelId == '4'){
            //6
            if($z==6 && in_array($z,$aCodes)){
                return 1;
            }
        }
    }


    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        $tmp=explode('&', $sCodes);
        $exists=[];
        foreach([3,4,5,6,7,8,9] as $v){
            if($v==3 || $v==9) {
                $exists['39'] = 1;
            }elseif($v==4 || $v==8){
                $exists['48'] = 1;
            }elseif($v==5 || $v==7){
                $exists['57'] = 1;
            }elseif($v==6){
                $exists['6'] = 1;
            }
        }

        $script=
            <<<LUA

LUA;

        $max1=$lockvalue-$prizes[1];
        $max2=$lockvalue-$prizes[2];
        $max3=$lockvalue-$prizes[3];
        $max4=$lockvalue-$prizes[4];
        $script.= <<<LUA

exists=cmd('exists','{$plan}')

if exists==0 and {$max1}<0 then
    do return 0 end
end

ret=cmd('zrangebyscore','{$plan}',{$max1},'+inf')
if (#ret==0) then do return 1 end end

-- 一等奖
if {$exists['39']}==1 then
    _codes={}
    for _,str in pairs(ret) do
        _codes={}
        str:gsub("%w+",function(c) table.insert(_codes,c) end)
        table.sort(_codes)
        v=tonumber(_codes[3])
        if v==3 or v==9 then
            do return 0 end
        end
    end
end

ret=cmd('zrangebyscore','{$plan}',{$max2},'+inf')
if (#ret==0) then do return 1 end end

-- 二等奖
if {$exists['48']}==1 then
    _codes={}
    for _,str in pairs(ret) do
        _codes={}
        str:gsub("%w+",function(c) table.insert(_codes,c) end)
        table.sort(_codes)
        v=tonumber(_codes[3])
        if v==4 or v==8 then
            do return 0 end
        end
    end
end

ret=cmd('zrangebyscore','{$plan}',{$max3},'+inf')
if (#ret==0) then do return 1 end end

-- 三等奖
if {$exists['57']}==1 then
    _codes={}
    for _,str in pairs(ret) do
        _codes={}
        str:gsub("%w+",function(c) table.insert(_codes,c) end)
        table.sort(_codes)
        v=tonumber(_codes[3])
        if v==5 or v==7 then
            do return 0 end
        end
    end
end

ret=cmd('zrangebyscore','{$plan}',{$max4},'+inf')
if (#ret==0) then do return 1 end end

-- 四等奖
if {$exists['6']}==1 then
    _codes={}
    for _,str in pairs(ret) do
        _codes={}
        str:gsub("%w+",function(c) table.insert(_codes,c) end)
        table.sort(_codes)
        v=tonumber(_codes[3])
        if v==6 then
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
_codes={}

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
        _codes={a,b,c,d,e}
        table.sort(_codes)
        v=tonumber(_codes[3])

	    for _,code in pairs(codes) do
	        if tonumber(code)==v then
	            if v==3 or v==9 then cmd('zincrby','{$plan}',{$prizes[1]},table.concat({a,b,c,d,e},' ')) end
	            if v==4 or v==8 then cmd('zincrby','{$plan}',{$prizes[2]},table.concat({a,b,c,d,e},' ')) end
	            if v==5 or v==7 then cmd('zincrby','{$plan}',{$prizes[3]},table.concat({a,b,c,d,e},' ')) end
	            if v==6 then cmd('zincrby','{$plan}',{$prizes[4]},table.concat({a,b,c,d,e},' ')) end
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
