<?php namespace App\Lib\Game\Method\Ssc\WX;
use App\Lib\Game\Method\Ssc\Base;

class WXZU30 extends Base
{
    // 0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9
    public $all_count =360;
    public static $filterArr = array(0 => 1, 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1, 6 => 1, 7 => 1, 8 => 1, 9 => 1);


    // 供测试用 生成随机投注
    public function randomCodes()
    {
        $arr=array();

        $rand=rand(2,count(self::$filterArr));
        $arr[]= implode('&',(array)array_rand(self::$filterArr,$rand));
        $rand=rand(2,count(self::$filterArr));
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

    public function count($sCodes)
    {
        //m表示上一排数量
        //n表示下一排数量
        //h表示重复的数量
        //C(m,2)*C(n,1)-C(h,2)*C(2,1)-C(h,1)*C(m-h,1)

        $temp = explode('|',$sCodes);
        $t1=explode('&',$temp[0]);
        $t2=explode('&',$temp[1]);
        $m = count($t1);
        $n = count($t2);
        $t = array_intersect_key(array_flip($t1), array_flip($t2));
        $h = count($t);

        return $this->getCombinCount($m,2) * $this->getCombinCount($n,1) - $this->getCombinCount($h,2)*$this->getCombinCount(2,1)-$this->getCombinCount($h,1)*$this->getCombinCount($m-$h,1);
    }

    // 判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $str = $this->strOrder(implode('', $numbers));

        $aCodes = explode('|', $sCodes);

        $aP1 = $this->getCombination(explode('&', $aCodes[0]), 2);
        $aP2 = $this->getCombination(explode('&', $aCodes[1]), 1);
        foreach ($aP1 as $v1) {
            $v1 = str_replace(' ', '', $v1);
            $vs = str_split($v1);
            foreach ($aP2 as $v2) {
                if (in_array($v2, $vs)) continue;
                if ($str === $this->strOrder(str_repeat($v1, 2) . str_repeat($v2, 1))) {
                    return 1;
                }
            }
        }
    }

    /**
     * 是否忽略计算奖金
     * @param $openCodeArr
     * @return bool
     */
    public function openIgnore($openCodeArr) {
        $_codeArr = array_unique($openCodeArr);
        if (count($_codeArr) == 3) {
            $intersect = array_diff_assoc($openCodeArr, $_codeArr);
            if (count(array_unique($intersect)) == 2) {
                return false;
            }
        }
        return true;
    }

    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        //0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9
        //2二重 1单号

        $aCodes = explode('|', $sCodes);

        $aP1 = $this->getCombination(explode('&', $aCodes[0]), 2);
        $aP1 = $this->getRepeat($aP1);
        $aP2 = $this->getCombination(explode('&', $aCodes[1]), 1);

        $tmp=[];
        foreach ($aP1 as $v1) {
            foreach ($aP2 as $v2) {
                $vs = explode(' ',$v1);
                if (in_array($v2, $vs)) continue;

                $tmp[$this->strOrder(str_replace(' ','',$v1) . str_replace(' ','',$v2))] = 1;
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
        //2二重 1单号

        $aCodes = explode('|', $sCodes);
        $tmp=[];
        $aP1 = $this->getCombination(explode('&',$aCodes[0]), 2);
        $aP2 = $this->getCombination(explode('&',$aCodes[1]), 1);

        foreach ($aP1 as $v1) {
            $vs = explode(' ',$v1);

            foreach ($aP2 as $v2) {
                if($v2==$vs[0] || $v2==$vs[1]) continue;

                $arr = [$vs[0],$vs[0],$vs[1],$vs[1],$v2];
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
