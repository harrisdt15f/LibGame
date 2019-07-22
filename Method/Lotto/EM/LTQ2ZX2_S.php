<?php namespace App\Lib\Game\Method\Lotto\EM;

use App\Lib\Game\Method\Lotto\Base;

class LTQ2ZX2_S extends Base
{
    // 01 02,01 02,01 02,01 02

    public static $filterArr = array('01'=>1, '02'=>1, '03'=>1, '04'=>1, '05'=>1, '06'=>1, '07'=>1, '08'=>1, '09'=>1, '10'=>1, '11'=>1);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $rand=2;
        return implode(' ',(array)array_rand(self::$filterArr,$rand));
    }

    public function fromOld($codes)
    {
        return implode(',',explode('|',$codes));
    }

    public function regexp($sCodes)
    {
        //格式
        if (!preg_match("/^(((0[1-9]\s)|(1[01]\s))((0[1-9])|(1[01]))\,)*(((0[1-9]\s)|(1[01]\s))((0[1-9])|(1[01])))$/", $sCodes)) {
            return false;
        }

        $aCode = explode(",",$sCodes);

        //去重
        if(count($aCode) != count(array_filter(array_unique($aCode)))) return true;

        //校验
        foreach ($aCode as $sTmpCode) {
            $aTmpCode = explode(" ", $sTmpCode);
            if (count($aTmpCode) != 2) {
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
        $str = implode(' ', $numbers);
        $aCodes = explode(',', $sCodes);

        foreach ($aCodes as $code) {
            if ($code === $str) {
                return 1;
            }
        }
    }


    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        $aCodes = explode(',', $sCodes);
        $codes=[];
        foreach($aCodes as $v1){
            $v1=explode(' ',$v1);
            $codes[implode(' ',$v1)]=1;
        }
        if(count($codes)){
            $codes="'".implode("','",array_keys($codes))."'";
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
    _codes={_codes[{$pos[0]}],_codes[{$pos[1]}]}
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
        //01&03&04&05
        $aCodes = explode('&', $sCodes);
        $aP1=$this->getCombination($aCodes,2);
        $codes=[];
        foreach($aP1 as $v1){
            $v1=explode(' ',$v1);
            $codes[implode(' ',$v1)]=1;
        }
        if(count($codes)){
            $codes="'".implode("','",array_keys($codes))."'";
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
        _codes={_codes[{$pos[0]}],_codes[{$pos[1]}]}
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
