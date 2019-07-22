<?php namespace App\Lib\Game\Method\Lotto\RXDT;

use App\Lib\Game\Method\Lotto\Base;

class LTRXDT8 extends Base
{
    //01&02&03&04&05&06&07&08

    public static $filterArr = array('01'=>1, '02'=>1, '03'=>1, '04'=>1, '05'=>1, '06'=>1, '07'=>1, '08'=>1, '09'=>1, '10'=>1, '11'=>1);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $n=8;
        $d=1;
        $t=$n-$d;
        $cnt=count(self::$filterArr);
        $rand1=$d;
        $rand2=rand($t,$cnt);

        $temp=(array)array_rand(self::$filterArr,$rand1);
        $diffs=array_diff(array_keys(self::$filterArr),$temp);

        if($rand2>count($diffs)) $rand2=count($diffs);

        $arr[]=implode('&',$temp);

        $arr[]=implode('&',(array)array_rand(array_flip($diffs),$rand2));

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
        if (count($aDan) >= 8) {
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
        //C(n2,3-n1)
        $aTmp = explode('|', $sCodes);
        $aDan = explode('&', $aTmp[0]);
        $aTuo = explode('&', $aTmp[1]);
        return $this->getCombinCount(count($aTuo), 8 - count($aDan));
    }

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $iParam=8;
        $aCodes = explode("|", $sCodes);

        $aDan = explode("&", $aCodes[0]); //胆码
        $aTuo = explode("&", $aCodes[1]); //拖码
        $iDanLen = count($aDan); //胆码个数
        $iTuoLen = count($aTuo); //拖码个数
        $iDanMatchLen = count(array_intersect($aDan, $numbers)); //胆码与中奖号码匹配次数
        $iTuoMatchLen = count(array_intersect($aTuo, $numbers)); // 拖码与中奖号码匹配次数

        if ($iTuoMatchLen + $iDanMatchLen != 5) { //所有号码与中奖号码的匹配次数不等于5:则不能中奖
            return 0;
        }
        if ($iDanLen - $iDanMatchLen > $iParam - 5) { //胆码中与开奖号码不匹配的个数不能大于:$iParam-5
            return 0;
        }

        return $this->GetCombinCount($iTuoLen - $iTuoMatchLen, $iParam - 5 - ($iDanLen - $iDanMatchLen));
    }


    public function getDtCodes($sCodes)
    {
        $iLen = 8;
        $aTmp = explode("|", $sCodes);
        $aDan = explode('&', $aTmp[0]);
        $aTuo = explode('&', $aTmp[1]);
        $aTuoCombins = $this->getCombination($aTuo, $iLen - count($aDan));
        $aCode = [];
        foreach ($aTuoCombins as $at) {
            $aTp = array_merge($aDan, explode(' ', $at));
            sort($aTp);
            $aCode[] = implode(' ', $aTp);
        }
        $aTemp = [];
        foreach ($aCode as $ac) {
            $acTmp = explode(' ', $ac);
            $aCom = $this->getCombination($acTmp, 5);
            foreach ($aCom as $sNum) {
                $aTemp[$sNum] = isset($aTemp[$sNum]) ? $aTemp[$sNum] + 1 : 1;
            }
        }

        $codes=[];
        $aCode = $this->_ArrayFlip($aTemp);
        foreach ($aCode as $k => $v) {
            foreach($v as $c){
                $codes[$c]=1;
            }
        }

        return array_keys($codes);
    }

    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        $aCodes=$this->getDtCodes($sCodes);
        $codes=[];
        foreach($aCodes as $v2){
            $v2=explode(' ',$v2);
            $codes[]="{'".implode("','",$v2)."'}";
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
for _,str in pairs(ret) do
    exists={}
    str:gsub("%w+",function(c) exists[c]=1 end)

    for _,codes2 in pairs(codes) do
        if exists[codes2[1]]==1 and exists[codes2[2]]==1 and exists[codes2[3]]==1 and exists[codes2[4]]==1 and exists[codes2[5]]==1 then
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
        $aCodes=$this->getDtCodes($sCodes);
        $codes=[];
        foreach($aCodes as $v2){
            $v2=explode(' ',$v2);
            $codes[]="{'".implode("','",$v2)."'}";
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
        exists={}
        exists[a]=1
        exists[b]=1
        exists[c]=1
        exists[d]=1
        exists[e]=1

        for _,codes2 in pairs(codes) do
            if exists[codes2[1]]==1 and exists[codes2[2]]==1 and exists[codes2[3]]==1 and exists[codes2[4]]==1 and exists[codes2[5]]==1 then
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
