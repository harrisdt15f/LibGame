<?php namespace App\Lib\Game\Method\Lotto\SM;

use App\Lib\Game\Method\Lotto\Base;

// 直选3
class LTQ3ZX3 extends Base
{
    //01&02&03&04&05&06&07&08|01&02&03&04&05&06&07&08|01&02&03&04&05&06&07&08

    public static $filterArr = array('01'=>1, '02'=>1, '03'=>1, '04'=>1, '05'=>1, '06'=>1, '07'=>1, '08'=>1, '09'=>1, '10'=>1, '11'=>1);

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

        return implode('|',$arr);
    }

    public function fromOld($sCodes){
        return implode('|',array_map(function($v){
            return implode('&',explode(' ',$v));
        },explode('|',$sCodes)));
    }

    public function regexp($sCodes)
    {
        //格式
        if (!preg_match("/^(((0[1-9]&)|(1[01]&)){0,10}((0[1-9])|(1[01]))\|){2}(((0[1-9]&)|(1[01]&)){0,10}((0[1-9])|(1[01])))$/", $sCodes)) {
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

            if($iUniqueCount==0){
                return false;
            }
        }

        return true;
    }

    public function count($sCodes)
    {
        $iNums = 0;
        $aNums = [];
        $aCode = explode("|", $sCodes);
        foreach ($aCode as $sCode) {
            $aNums[] = explode("&", $sCode);
        }

        if (count($aNums[0]) > 0 && count($aNums[1]) > 0 && count($aNums[2]) > 0) {
            for ($i = 0, $iMax = count($aNums[0]); $i < $iMax; $i++) {
                for ($j = 0, $jMax = count($aNums[1]); $j < $jMax; $j++) {
                    for ($k = 0, $kMax = count($aNums[2]); $k < $kMax; $k++) {
                        if ($aNums[0][$i] != $aNums[1][$j] && $aNums[0][$i] != $aNums[2][$k] && $aNums[1][$j] != $aNums[2][$k]) {
                            $iNums++;
                        }
                    }
                }
            }
        }

        return $iNums;
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

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $aCodes = explode('|', $sCodes);

        $aCodes = $this->convertLtCodes($aCodes);
        $numbers = $this->convertLtCodes($numbers);

        $preg = "|[" . str_replace('&', '', $aCodes[0]) . "][" . str_replace('&', '', $aCodes[1]) . "][" . str_replace('&', '', $aCodes[2]) . "]|";

        if (preg_match($preg, implode("", $numbers))) {
            return 1;
        }
    }


    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        //01&03&04&05 | 01&03&04&05
        $aTmp = explode('|', $sCodes);
        $tmp1 = explode('&', $aTmp[0]);
        $tmp2 = explode('&', $aTmp[1]);
        $tmp3 = explode('&', $aTmp[2]);
        $codes=[];
        foreach($tmp1 as $v1){
            foreach($tmp2 as $v2){
                foreach($tmp3 as $v3) {
                    $codes[] = $v1 . ' ' . $v2.' '.$v3;
                }
            }
        }

        if(count($codes)){
            $codes="'".implode("','",$codes)."'";
        }else{
            $codes='';
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
    str:gsub("%w+",function(c) table.insert(_codes,c) end)
    _codes={_codes[{$pos[0]}],_codes[{$pos[1]}],_codes[{$pos[2]}]}
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
        //01&03&04&05 | 01&03&04&05
        $aTmp = explode('|', $sCodes);
        $tmp1 = explode('&', $aTmp[0]);
        $tmp2 = explode('&', $aTmp[1]);
        $tmp3 = explode('&', $aTmp[2]);
        $codes=[];
        foreach($tmp1 as $v1){
            foreach($tmp2 as $v2){
                foreach($tmp3 as $v3) {
                    $codes[] = $v1 . ' ' . $v2.' '.$v3;
                }
            }
        }

        if(count($codes)){
            $codes="'".implode("','",$codes)."'";
        }else{
            $codes='';
        }

        $pos=array_keys(array_intersect($this->lottery->position,$this->levels[1]['position']));
        array_walk($pos,function(&$v){$v++;});

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
        _codes={_codes[{$pos[0]}],_codes[{$pos[1]}],_codes[{$pos[2]}]}
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
