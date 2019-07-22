<?php  namespace App\Lib\Game\Method\Ssc\EX;

use App\Lib\Game\Method\Ssc\Base;

class QZX2KD extends Base
{
    //1&2&3&4&5&6
    public $all_count =1000;
    public static $filterArr = array(0 => 10, 1 => 18, 2 => 16, 3 => 14, 4 => 12, 5 => 10, 6 => 8, 7 => 6, 8 => 4, 9 => 2);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $rand=rand(1,count(self::$filterArr));
        return implode('&',(array)array_rand(self::$filterArr,$rand));
    }

    public function fromOld($codes)
    {
        return implode('&',explode('|',$codes));
    }

    public function regexp($sCodes)
    {
        //去重
        $t=explode("&",$sCodes);
        $temp =array_unique($t);
        $arr = self::$filterArr;

        $temp = array_filter($temp,function($v) use ($arr) {
            return isset($arr[$v]);
        });

        if(count($temp)==0){
            return false;
        }

        return count($temp) == count($t);
    }

    public function count($sCodes)
    {
        //枚举之和
        $n = 0;
        $temp = explode('&',$sCodes);
        foreach($temp as $c){
            $n += self::$filterArr[$c];
        }

        return $n;
    }

    public function bingoCode(Array $numbers)
    {
        sort($numbers);
        $val=$numbers[1]-$numbers[0];
        $arr= array_keys(self::$filterArr);
        $result=[];
        foreach($arr as $pos=>$_code){
            $result[$pos]=intval($_code == $val);
        }

        return [$result];
    }

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        sort($numbers);

        $min = array_shift($numbers);
        $max = array_pop($numbers);
        $val = $max - $min;

        $aCodes = explode('&', $sCodes);

        foreach ($aCodes as $code) {
            if ($code == $val) {
                return 1;
            }
        }

    }


    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        //1&2&3&4&5&6&27

        $aCodes = explode('&', $sCodes);
        $exists=array_flip($aCodes);

        $codes=[];
        for ($i=0;$i<=9;$i++) {
            for ($j=0;$j<=9;$j++) {
                $v=abs($i-$j);
                if(isset($exists[$v])){
                    $codes[$i.$j]=1;
                }
            }
        }

        if(count($codes)>0){
            $codes="'".implode("','",array_keys($codes))."'";
        }else{
            $codes="";
        }

        $pos=array_keys(array_intersect($this->lottery->position,$this->levels[1]['position']));
        array_walk($pos,function(&$v){$v++;});

        $script=
            <<<LUA

LUA;

        //检查各奖级最大值,如果最大值没有出现超过的值，则略过
        //封锁值 <= 总封锁值＋销量－奖金
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
    _codes={_codes[{$pos[0]}],_codes[{$pos[1]}]}
    _code=table.concat(_codes)

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
        $aCodes = explode('&', $sCodes);
        $exists=array_flip($aCodes);

        $codes=[];
        for ($i=0;$i<=9;$i++) {
            for ($j=0;$j<=9;$j++) {
                $v=abs($i-$j);
                if(isset($exists[$v])){
                    $codes[$i.$j]=1;
                }
            }
        }

        if(count($codes)>0){
            $codes="'".implode("','",array_keys($codes))."'";
        }else{
            $codes="";
        }

        $diff=array_values(array_diff($this->lottery->position,$this->levels[1]['position']));
        $intersect=array_values(array_intersect($this->lottery->position,$this->levels[1]['position']));
        $positions=implode(",",$this->lottery->position);
        $x3=count($this->lottery->position)==3;

        //不同奖级的中奖金额
        $script= <<<LUA

codes={{$codes}}
for _,_code in pairs(codes) do
    mix={}
    _code:gsub(".",function(c) table.insert(mix,c) end)
    $intersect[0]=mix[1]
    $intersect[1]=mix[2]

LUA;

        if($x3){
            $script.=<<<LUA
        for _,$diff[0] in pairs({0,1,2,3,4,5,6,7,8,9}) do
            cmd('zincrby','{$plan}',{$prizes[1]},table.concat({{$positions}}))
        end

LUA;
        }else{
            $script.=<<<LUA
        for _,$diff[0] in pairs({0,1,2,3,4,5,6,7,8,9}) do
        for _,$diff[1] in pairs({0,1,2,3,4,5,6,7,8,9}) do
        for _,$diff[2] in pairs({0,1,2,3,4,5,6,7,8,9}) do
            cmd('zincrby','{$plan}',{$prizes[1]},table.concat({{$positions}}))
        end
        end
        end

LUA;
        }
        $script.=<<<LUA

end

LUA;

        return $script;
    }

}
