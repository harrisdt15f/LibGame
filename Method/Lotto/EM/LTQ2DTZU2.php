<?php namespace App\Lib\Game\Method\Lotto\EM;

use App\Lib\Game\Method\Lotto\Base;

// 二胆拖组选
class LTQ2DTZU2 extends Base
{
    //01&02&03&04&05&06&07&08&09&10&11|01&02&03&04&05&06&07&08&09&10&11

    public static $filterArr = array('01'=>1, '02'=>1, '03'=>1, '04'=>1, '05'=>1, '06'=>1, '07'=>1, '08'=>1, '09'=>1, '10'=>1, '11'=>1);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $cnt=count(self::$filterArr);
        $rand=1;
        $rand2=$cnt-$rand;

        $temp=(array)array_rand(self::$filterArr,$rand);
        $_arr2=array_diff(array_keys(self::$filterArr),$temp);
        $arr[]=implode('&',$temp);
        $arr[]=implode('&',(array)array_rand(array_flip($_arr2),$rand2));

        return implode('|',$arr);
    }

    public function fromOld($sCodes){
        return implode('|',array_map(function($v){
            return implode('&',explode(' ',$v));
        },explode('|',$sCodes)));
    }

    public function regexp($sCodes)
    {
        if (!preg_match("/^(((0[1-9]&)|(1[01]&)){0,6}((0[1-9])|(1[01]))\|){1}(((0[1-9]&)|(1[01]&)){0,10}((0[1-9])|(1[01])))$/", $sCodes)) {
            return false;
        }

        $filterArr = self::$filterArr;

        $aTmp = explode('|', $sCodes);
        $aDan = explode('&', $aTmp[0]);
        if (count($aDan) != count(array_filter(array_unique($aDan),function($v) use($filterArr) {
                return isset($filterArr[$v]);
            }))) { //不能有重复的号码
            return false;
        }
        $aTuo = explode('&', $aTmp[1]);
        if (count($aTuo) != count(array_filter(array_unique($aTuo),function($v) use($filterArr) {
                return isset($filterArr[$v]);
            }))) { //不能有重复的号码
            return false;
        }

        if(count($aDan)==0 || count($aTuo)==0){
            return false;
        }

        if (count($aDan) >= 2) {
            return false;
        }

        //有重复的
        if (count(array_intersect($aDan, $aTuo)) > 0) {
            return false;
        }

        return true;
    }

    public function count($sCodes)
    {
        $aTmp = explode('|', $sCodes);
        $aDan = explode('&', $aTmp[0]);
        $aTuo = explode('&', $aTmp[1]);
        return $this->getCombinCount(count($aTuo), 2 - count($aDan));
    }

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        //胆码必须 + 拖码 顺序不限
        $sCodes = $this->convertLtCodes($sCodes);
        $numbers = $this->convertLtCodes($numbers);

        $aTmp = explode('|', $sCodes);
        $aDan = explode('&',$aTmp[0]);
        $aTuo = explode('&',$aTmp[1]);

        $iNum = count($aDan);
        //胆码都存在
        if(count(array_intersect($aDan,$numbers)) == $iNum){
            $iCnt = 2-$iNum;
            $i=0;
            $arr = array_diff($numbers, $aDan);
            foreach($aTuo as $t){
                if(in_array($t,$arr)){
                    $i++;
                }
            }

            if($i >= $iCnt){
                return 1;
            }
        }

    }


    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        //01|03&04&05
        $aTmp = explode('|', $sCodes);
        $aDan = explode('&',$aTmp[0]);
        $aTuo = explode('&',$aTmp[1]);

        $codes=[];
        $aP1=$this->getCombination($aTuo,1);
        foreach($aP1 as $v){
            $tmp=$aDan;
            $tmp[]=$v;
            sort($tmp);
            $codes[]=implode(" ",$tmp);
        }

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
_codes={}
for _,str in pairs(ret) do
    _codes={}
    str:gsub("%w+",function(c) table.insert(_codes,c) end)
    _codes={_codes[1],_codes[2]}
    table.sort(_codes)
    _code=table.concat(_codes,' ')

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
        //01|03&04&05
        $aTmp = explode('|', $sCodes);
        $aDan = explode('&',$aTmp[0]);
        $aTuo = explode('&',$aTmp[1]);

        $codes=[];
        $aP1=$this->getCombination($aTuo,1);
        foreach($aP1 as $v){
            $tmp=array_merge($aDan,explode(' ',$v));
            sort($tmp);
            $codes[]=implode(" ",$tmp);
        }

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
        _codes={a,b}
        table.sort(_codes)
        _code=table.concat(_codes,' ')

	    for _,code in pairs(codes) do
            if code==_code then
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
