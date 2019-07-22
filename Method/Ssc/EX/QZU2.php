<?php namespace App\Lib\Game\Method\Ssc\EX;

use App\Lib\Game\Method\Ssc\Base;

//组选2
class QZU2 extends Base
{
    //1&2&3&4&5&6
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
        $temp=explode("&",$sCodes);
        $filterArr = self::$filterArr;

        $iNums = count(array_filter(array_unique($temp),function($v) use ($filterArr) {
            return isset($filterArr[$v]);
        }));

        if($iNums==0){
            return false;
        }

        return count($temp) == $iNums;
    }

    public function count($sCodes)
    {
        //C(n,2)

        $n = count(explode("&",$sCodes));

        return $this->getCombinCount($n,2);
    }

    public function bingoCode(Array $numbers)
    {
        //对子号
        if(count(array_count_values($numbers))==1) return [];

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
        //非对子
        if ($numbers[0] != $numbers[1]) {
            $preg = "|[" . str_replace('&', '', $sCodes) . "]{2}|";
            if (preg_match($preg, implode("", $numbers))) {
                return 1;
            }
        }

    }


    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        //1&2&3&4&5&6

        $aCodes = explode('&', $sCodes);
        $aP1 = $this->getCombination($aCodes, 2);
        $codes=[];
        foreach($aP1 as $v1){
            $v1=str_replace(' ','',$v1);
            $codes[$this->strOrder($v1[0].$v1[1])]=1;
            $codes[$this->strOrder($v1[1].$v1[0])]=1;
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
    table.sort(_codes)
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
        //1&2&3&4&5&6

        $aCodes = explode('&', $sCodes);
        $aP1 = $this->getCombination($aCodes, 2);
        $codes=[];
        foreach($aP1 as $v1){
            $v1=str_replace(' ','',$v1);
            $codes[$v1[0].$v1[1]]=1;
            $codes[$v1[1].$v1[0]]=1;
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

        $script='';

        $script.= <<<LUA

codes={{$codes}}
for _,_code in pairs(codes) do
    mix={}
    _code:gsub(".",function(c) table.insert(mix,c) end)
    $intersect[0]=mix[1]
    $intersect[1]=mix[2]

LUA;
        if($x3){
            $script.= <<<LUA
        for _,$diff[0] in pairs({0,1,2,3,4,5,6,7,8,9}) do
            cmd('zincrby','{$plan}',{$prizes[1]},table.concat({{$positions}}))
        end

LUA;
        }else{
            $script.= <<<LUA

        for _,$diff[0] in pairs({0,1,2,3,4,5,6,7,8,9}) do
        for _,$diff[1] in pairs({0,1,2,3,4,5,6,7,8,9}) do
        for _,$diff[2] in pairs({0,1,2,3,4,5,6,7,8,9}) do
            cmd('zincrby','{$plan}',{$prizes[1]},table.concat({{$positions}}))
        end
        end
        end

LUA;
        }

        $script.= <<<LUA

end

LUA;

        return $script;
    }

}
