<?php namespace App\Lib\Game\Method\Ssc\Z3;

use App\Lib\Game\Method\Ssc\Base;

class ZZH3 extends Base
{
    // 0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9
    public $all_count =3000;
    public static $filterArr = array(0 => 1, 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1, 6 => 1, 7 => 1, 8 => 1, 9 => 1);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $arr=[];
        $rand=rand(1,10);
        $arr[]= implode('&',(array)array_rand(self::$filterArr,$rand));
        $rand=rand(1,10);
        $arr[]= implode('&',(array)array_rand(self::$filterArr,$rand));
        $rand=rand(1,10);
        $arr[]= implode('&',(array)array_rand(self::$filterArr,$rand));

        return implode('|',$arr);
    }

    public function regexp($sCodes)
    {
        $regexp = '/^(([0-9]&){0,9}[0-9])\|(([0-9]&){0,9}[0-9])\|(([0-9]&){0,9}[0-9])$/';
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
        //n1*n2*n3*3
        $cnt = 1;
        $temp = explode('|',$sCodes);
        foreach($temp as $c){
            $cnt *= count(explode('&',$c));
        }

        $cnt *= 3;

        return $cnt;
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

        if ($levelId == '1') {
            $preg = "|[" . str_replace('&', '', $aCodes[0]) . "][" . str_replace('&', '', $aCodes[1]) . "][" . str_replace('&', '', $aCodes[2]) . "]|";
            if (preg_match($preg, implode("", $numbers))) {
                return 1;
            }
        } elseif ($levelId == '2') {
            $preg = "|[" . str_replace('&', '', $aCodes[1]) . "][" . str_replace('&', '', $aCodes[2]) . "]|";
            if (preg_match($preg, implode("", $numbers))) {
                $times = count(explode('&',$aCodes[0]));
                return $times;
            }
        } elseif ($levelId == '3') {
            $preg = "|[" . str_replace('&', '', $aCodes[2]) . "]|";
            if (preg_match($preg, implode("", $numbers))) {
                $times = count(explode('&',$aCodes[0])) * count(explode('&',$aCodes[1]));
                return $times;
            }
        }

    }


    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        $codes=explode('|',$sCodes);
        $tmp=[0,1,2,3,4,5,6,7,8,9];
        $diffs=[];
        foreach($codes as $k=>&$code){
            $code=str_replace('&',',',$code);
            $diffs[$k]=implode(',',array_diff($tmp,explode(',',$code)));
        }

        $intersect=array_values(array_intersect($this->lottery->position,$this->levels[1]['position']));
        $positions=implode(",",$this->lottery->position);
        $pos=array_keys(array_intersect($this->lottery->position,$this->levels[1]['position']));
        array_walk($pos,function(&$v){$v++;});

        $script=
            <<<LUA

LUA;
        //一等奖
        $max1=$lockvalue-$prizes[1];
        $max2=$lockvalue-$prizes[2];
        $max3=$lockvalue-$prizes[3];

        $script.= <<<LUA

exists=cmd('exists','{$plan}')

if exists==0 and {$max1}<0 then
    do return 0 end
end

-- 一等奖
ret=cmd('zrangebyscore','{$plan}',{$max1},'+inf')
if (#ret==0) then
    do return 1 end
end

aa={{$codes[0]}}
bb={{$codes[1]}}
cc={{$codes[2]}}
_codes={}
for _,str in pairs(ret) do
    _codes={}
    str:gsub(".",function(c) table.insert(_codes,c) end)
    _codes={_codes[{$pos[0]}],_codes[{$pos[1]}],_codes[{$pos[2]}]}
    _code=table.concat(_codes)

    for _,{$intersect[0]} in pairs(aa) do
    for _,{$intersect[1]} in pairs(bb) do
    for _,{$intersect[2]} in pairs(cc) do
        if (_code==table.concat({$positions})) then
            return 0
        end
    end
    end
    end
end

-- 二等奖
ret=cmd('zrangebyscore','{$plan}',{$max2},'+inf')
if (#ret==0) then
    do return 1 end
end

aa={{$diffs[0]}}
for _,str in pairs(ret) do
    _codes={}
    str:gsub(".",function(c) table.insert(_codes,c) end)
    _codes={_codes[{$pos[0]}],_codes[{$pos[1]}],_codes[{$pos[2]}]}
    _code=table.concat(_codes)

    for _,{$intersect[0]} in pairs(aa) do
    for _,{$intersect[1]} in pairs(bb) do
    for _,{$intersect[2]} in pairs(cc) do
        if (_code==table.concat({$positions})) then
            return 0
        end
    end
    end
    end
end


-- 三等奖
ret=cmd('zrangebyscore','{$plan}',{$max3},'+inf')
if (#ret==0) then
    do return 1 end
end


aa={{$diffs[0]}}
bb={{$diffs[1]}}
for _,str in pairs(ret) do
    _codes={}
    str:gsub(".",function(c) table.insert(_codes,c) end)
    _codes={_codes[{$pos[0]}],_codes[{$pos[1]}],_codes[{$pos[2]}]}
    _code=table.concat(_codes)

    for _,{$intersect[0]} in pairs(aa) do
    for _,{$intersect[1]} in pairs(bb) do
    for _,{$intersect[2]} in pairs(cc) do
        if (_code==table.concat({$positions})) then
            return 0
        end
    end
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
        //1&2&3|1&2&3|1&2&3
        $tmp=[0,1,2,3,4,5,6,7,8,9];
        $codes=explode('|',$sCodes);
        $diffs=[];
        $pcnt=[];
        foreach($codes as $k=>&$code){
            $code=str_replace('&',',',$code);
            $diffs[$k]=implode(',',array_diff($tmp,explode(',',$code)));

            $pcnt[$k]=count(explode(',',$code));
        }

        $times = $pcnt[0]*$pcnt[1];
        $p3=$times*$prizes[3];

        $times = $pcnt[0];
        $p2=$times*$prizes[2];

        $times=1;
        $p1=$times*$prizes[1];

        $prizes[1]=$p1;
        $prizes[2]=$p2;
        $prizes[3]=$p3;


        $diff=array_values(array_diff($this->lottery->position,$this->levels[1]['position']));
        $intersect=array_values(array_intersect($this->lottery->position,$this->levels[1]['position']));
        $positions=implode(",",$this->lottery->position);

        $x3=count($this->lottery->position)==3;

        //不同奖级的中奖金额
        $script= <<<LUA

x={0,1,2,3,4,5,6,7,8,9}
-- 一等奖
for _,$intersect[0] in pairs({{$codes[0]}}) do
for _,$intersect[1] in pairs({{$codes[1]}}) do
for _,$intersect[2] in pairs({{$codes[2]}}) do

LUA;
        if($x3){
            $script.= <<<LUA
        cmd('zincrby','{$plan}',{$prizes[1]},table.concat({{$positions}}, ''))

LUA;
        }else{
            $script.= <<<LUA

        for _,$diff[0] in pairs({0,1,2,3,4,5,6,7,8,9}) do
        for _,$diff[1] in pairs({0,1,2,3,4,5,6,7,8,9}) do
                cmd('zincrby','{$plan}',{$prizes[1]},table.concat({{$positions}}, ''))
        end
        end

LUA;
        }

        $script.= <<<LUA

end
end
end

-- 二等奖
for _,$intersect[0] in pairs(x) do
for _,$intersect[1] in pairs({{$codes[1]}}) do
for _,$intersect[2] in pairs({{$codes[2]}}) do

LUA;
        if($x3){
            $script.= <<<LUA
        cmd('zincrby','{$plan}',{$prizes[2]},table.concat({{$positions}}, ''))

LUA;
        }else{
            $script.= <<<LUA

        for _,$diff[0] in pairs({0,1,2,3,4,5,6,7,8,9}) do
        for _,$diff[1] in pairs({0,1,2,3,4,5,6,7,8,9}) do
                cmd('zincrby','{$plan}',{$prizes[2]},table.concat({{$positions}}, ''))
        end
        end

LUA;
        }

        $script.= <<<LUA

end
end
end

-- 三等奖
for _,$intersect[0] in pairs(x) do
for _,$intersect[1] in pairs(x) do
for _,$intersect[2] in pairs({{$codes[2]}}) do

LUA;
        if($x3){
            $script.= <<<LUA
        cmd('zincrby','{$plan}',{$prizes[3]},table.concat({{$positions}}, ''))

LUA;
        }else{
            $script.= <<<LUA

        for _,$diff[0] in pairs({0,1,2,3,4,5,6,7,8,9}) do
        for _,$diff[1] in pairs({0,1,2,3,4,5,6,7,8,9}) do
                cmd('zincrby','{$plan}',{$prizes[3]},table.concat({{$positions}}, ''))
        end
        end

LUA;
        }

        $script.= <<<LUA

end
end
end

LUA;
        return $script;
    }

}
