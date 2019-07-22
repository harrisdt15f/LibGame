<?php namespace App\Lib\Game\Method\Ssc\SX;

use App\Lib\Game\Method\Ssc\Base;

class SXZU6 extends Base
{
    //0&1&2&3&4&5&6&7&8&9
    public $all_count =45;
    public static $filterArr = array(0 => 1, 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1, 6 => 1, 7 => 1, 8 => 1, 9 => 1);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $rand=rand(2,10);
        return implode('&',(array)array_rand(self::$filterArr,$rand));
    }

    public function fromOld($codes)
    {
        return implode('&',explode('|',$codes));
    }


    public function regexp($sCodes)
    {
        $regexp = '/^([0-9]&){0,9}[0-9]$/';
        if( !preg_match($regexp,$sCodes) ) return false;

        $filterArr = self::$filterArr;
        //去重
        $sCodes = explode("|", $sCodes);
        foreach($sCodes as $codes){
            $temp = explode('&',$codes);
            if(count($temp) != count(array_filter(array_unique($temp),function($v) use($filterArr) {
                    return isset($filterArr[$v]);
                }))) return false;

            if(count($temp)==0){
                return false;
            }
        }

        return true;
    }

    public function count($sCodes)
    {
        //C(n,2)
        return $this->getCombinCount(count(explode('&',$sCodes)),2);
    }

    public function bingoCode(Array $numbers)
    {
        $counts=array_count_values($numbers);

        $arr= array_keys(self::$filterArr);
        $result=[];
        foreach($arr as $pos=>$_code){
            $result[$pos]=intval(isset($counts[$_code]) && $counts[$_code]>=2);
        }

        return [$result];
    }

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $str = $this->strOrder(implode('', $numbers));

        $aCodes = explode('&', $sCodes);

        $aP1 = $this->getCombination($aCodes, 2);
        $aP1 = $this->getRepeat($aP1);

        foreach ($aP1 as $v1) {
            if ($str == $this->strOrder(str_replace(' ', '', $v1)) ) {
                return 1;
            }
        }

    }


    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        //2二重号
        //0&1&2&3&4&5&6&7&8&9

        $aCodes = explode('&', $sCodes);
        $aP1 = $this->getCombination($aCodes, 2);

        $tmp=[];
        foreach ($aP1 as $v1) {
            $c=str_replace(' ','',$v1);
            $tmp[$this->strOrder($c[0].$c[0].$c[1].$c[1])]=1;
        }

        $codes="'".implode("','",array_keys($tmp))."'";
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
    _codes={_codes[{$pos[0]}],_codes[{$pos[1]}],_codes[{$pos[2]}],_codes[{$pos[3]}]}
    table.sort(_codes)
    _code=table.concat(_codes,'')

    for _,code in pairs(codes) do
        if code==_code then
            do return 0 end
        end
    end
end

--do return tmp end
do return 1 end

LUA;

        return $script;
    }

    //写入封锁值
    public function lockScript($sCodes,$plan,$prizes)
    {
        //0&1&2&3&4&5&6&7&8&9

        $aCodes = explode('&', $sCodes);
        $aP1 = $this->getCombination($aCodes, 2);

        $tmp=[];
        foreach ($aP1 as $v1) {
            $t=explode(' ',$v1);
            $arr=[$t[0],$t[0],$t[1],$t[1]];
            sort($arr);
            $tmp["{".implode(',',$arr)."}"]=1;
        }

        $codes=implode(",",array_keys($tmp));

        $diff=array_values(array_diff($this->lottery->position,$this->levels[1]['position']));
        $intersect=array_values(array_intersect($this->lottery->position,$this->levels[1]['position']));
        $positions=implode(",",$this->lottery->position);

        $script='';
        //不同奖级的中奖金额
        $script.= <<<LUA

codes={{$codes}}
_codes={}

for _,code in pairs(codes) do
    fun.P(code,4,_codes)
end

for _code,_ in pairs(_codes) do
    mix={}
    _code:gsub(".",function(c) table.insert(mix,c) end)

    $intersect[0]=mix[1]
    $intersect[1]=mix[2]
    $intersect[2]=mix[3]
    $intersect[3]=mix[4]

    for _,$diff[0] in pairs({0,1,2,3,4,5,6,7,8,9}) do
        cmd('zincrby','{$plan}',{$prizes[1]},table.concat({{$positions}}))
    end
end


LUA;

        return $script;
    }

}
