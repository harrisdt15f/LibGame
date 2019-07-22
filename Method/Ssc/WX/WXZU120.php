<?php namespace App\Lib\Game\Method\Ssc\WX;
use App\Lib\Game\Method\Ssc\Base;

class WXZU120 extends Base
{
    //0&1&2&3&4&5&6&7&8&9
    public $all_count =252;
    public static $filterArr = array(0 => 1, 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1, 6 => 1, 7 => 1, 8 => 1, 9 => 1);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $rand=rand(5,10);
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
        //C(n,5)
        return $this->getCombinCount(count(explode('&',$sCodes)),5);
    }

    public function bingoCode(Array $numbers)
    {
        $exists=array_flip($numbers);
        $arr= array_keys(self::$filterArr);
        $result=[];
        foreach($arr as $pos=>$_code){
            $result[$pos]=intval(isset($exists[$_code]));
        }

        return [$result];
    }

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $str = $this->strOrder(implode('', $numbers));
        $aCodes = explode('&', $sCodes);

        $aP1 = $this->getCombination($aCodes, 5);

        foreach ($aP1 as $v1) {
            if ($str === $this->strOrder(str_replace(' ', '', $v1)) ) {
                return 1;
            }
        }
    }

    // 是否忽略计算奖金
    public function openIgnore($openCodeArr) {
        $_codeArr = array_unique($openCodeArr);
        return count($_codeArr) != count($openCodeArr);
    }

    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        //0&1&2&3&4&5&6&7&8&9
        //顺序不限

        $aCodes = explode('&', $sCodes);
        $aP1 = $this->getCombination($aCodes, 5);

        $tmp=[];
        foreach ($aP1 as $v1) {
            $tmp[$this->strOrder(str_replace(' ','',$v1))] = 1;
        }

        $codes="'".implode("','",array_keys($tmp))."'";

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
    table.sort(_codes)

    _code=table.concat(_codes,'')

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
        //0&1&2&3&4&5&6&7&8&9
        //顺序不限

        $aCodes = explode('&', $sCodes);
        $aP1 = $this->getCombination($aCodes, 5);

        $tmp=[];
        foreach ($aP1 as $v1) {
            $arr = explode(' ',$v1);
            sort($arr);
            $tmp["{" . implode(',', $arr) . "}"] = 1;
        }

        $codes=implode(",",array_keys($tmp));

        $script='';

        $script.= <<<LUA

codes={{$codes}}
_codes={}
for _,code in pairs(codes) do
    fun.P(code,5,_codes)
end

for _code,_ in pairs(_codes) do
    cmd('zincrby','{$plan}',{$prizes[1]},_code)
end

LUA;

        return $script;
    }
}
