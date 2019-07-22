<?php namespace App\Lib\Game\Method\Ssc\WX;

use App\Lib\Game\Method\Ssc\Base;

class WXZU5 extends Base
{
    //0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9
    public $all_count =90;
    public static $filterArr = array(0 => 1, 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1, 6 => 1, 7 => 1, 8 => 1, 9 => 1);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $arr=array();

        $rand=rand(2,count(self::$filterArr));
        $arr[]= implode('&',(array)array_rand(self::$filterArr,$rand));
        $rand=rand(1,count(self::$filterArr));
        $arr[]= implode('&',(array)array_rand(self::$filterArr,$rand));

        return implode('|',$arr);
    }

    public function regexp($sCodes)
    {
        $regexp = '/^(([0-9]&){0,9}[0-9])\|(([0-9]&){0,9}[0-9])$/';
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

    /**
     * 是否忽略计算奖金
     * @param $openCodeArr
     * @return bool
     */
    public function openIgnore($openCodeArr) {
        $_codeArr = array_unique($openCodeArr);
        if (count($_codeArr) == 2) {
            $intersect = array_diff_assoc($openCodeArr, $_codeArr);
            if (count(array_unique($intersect)) == 1) {
                return false;
            }
        }
        return true;
    }

    public function count($sCodes)
    {
        //m表示上一排数量
        //n表示下一排数量
        //h表示重复的数量
        //C(m,1)*C(n,1)-C(h,1)

        $temp = explode('|',$sCodes);
        $t1=explode('&',$temp[0]);
        $t2=explode('&',$temp[1]);
        $m = count($t1);
        $n = count($t2);
        $t = array_intersect_key(array_flip($t1), array_flip($t2));
        $h = count($t);

        return $this->getCombinCount($m,1) * $this->getCombinCount($n,1) - $this->getCombinCount($h,1);
    }

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $str = $this->strOrder(implode('', $numbers));

        $aCodes = explode('|', $sCodes);

        $aP1 = $this->getCombination(explode('&', $aCodes[0]), 1);
        $aP2 = $this->getCombination(explode('&', $aCodes[1]), 1);
        foreach ($aP1 as $v1) {
            foreach ($aP2 as $v2) {
                if ($v1 == $v2) continue;
                if ($str === $this->strOrder(str_repeat($v1, 4) . str_repeat($v2, 1))) {
                    return 1;
                }
            }
        }
    }


    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        //0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9
        //1四重 1单号

        $aCodes = explode('|', $sCodes);

        $aP1 = $this->getCombination(explode('&', $aCodes[0]), 1);
        $aP2 = $this->getCombination(explode('&', $aCodes[1]), 1);

        $tmp=[];
        foreach ($aP1 as $v1) {
            foreach ($aP2 as $v2) {
                if ($v1==$v2) continue;

                $tmp[$this->strOrder($v1 . $v1 . $v1 . $v1. $v2)] = 1;
            }
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
        //0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9
        //1四重 1单号

        $aCodes = explode('|', $sCodes);
        $tmp=[];
        $aP1 = $this->getCombination(explode('&',$aCodes[0]), 1);
        $aP2 = $this->getCombination(explode('&',$aCodes[1]), 1);

        foreach ($aP1 as $v1) {
            foreach ($aP2 as $v2) {
                if ($v1==$v2) continue;

                $arr = [$v1,$v1,$v1,$v1,$v2];
                sort($arr);
                $tmp["{" . implode(',', $arr) . "}"] = 1;
            }
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
