<?php namespace App\Lib\Game\Method\Ssc\Z3;

use App\Lib\Game\Method\Ssc\Base;

// 中三　组选包胆
class ZZU3BD extends Base
{
    //1
    public $all_count =486;
    public static $filterArr = array(0 => 54, 1 => 54, 2 => 54, 3 => 54, 4 => 54, 5 => 54, 6 => 54, 7 => 54, 8 => 54, 9 => 54);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        return array_rand(self::$filterArr,1);
    }

    public function regexp($sCodes)
    {
        return isset(self::$filterArr[$sCodes]);
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
        //豹子号
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
        $aCodes = explode('&', $sCodes);

        if ($levelId == '1') {
            $flip = array_filter(array_count_values($numbers), function ($v) {
                return $v == 2;
            });

            //组三
            if (count($flip) == 1) {
                foreach ($aCodes as $code) {
                    if (in_array($code, $numbers)) {
                        return 1;
                    }
                }
            }
        } elseif ($levelId == '2') {
            //排除组3组6
            $flip = array_filter(array_count_values($numbers), function ($v) {
                return $v >= 2;
            });

            //组六
            if (count($flip) == 0) {
                foreach ($aCodes as $code) {
                    if (in_array($code, $numbers)) {
                        return 1;
                    }
                }
            }
        }

    }


    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        //0
        //组三或组六

        $c = trim($sCodes);

        $z3=$z6=[];
        for ($i=0;$i<=9;$i++) {
            for ($j=0;$j<=9;$j++) {
                for ($k=0;$k<=9;$k++) {
                    if($i!=$c && $j!=$c && $k!=$c) continue;
                    if($i==$j && $i==$k) continue; //不含豹子号
                    if($i==$j || $i==$k || $j==$k){
                        //组三
                        $z3[$this->strOrder($i.$j.$k)]=1;
                    }else{
                        //组六
                        $z6[$this->strOrder($i.$j.$k)]=1;
                    }
                }
            }
        }

        if(count($z3)>0){
            $z3="'".implode("','",array_keys($z3))."'";
        }else{
            $z3="";
        }
        if(count($z6)>0){
            $z6="'".implode("','",array_keys($z6))."'";
        }else{
            $z6="";
        }

        $pos=array_keys(array_intersect($this->lottery->position,$this->levels[1]['position']));
        array_walk($pos,function(&$v){$v++;});

        $script=
            <<<LUA

LUA;

        $max1=$lockvalue-$prizes[1];
        $max2=$lockvalue-$prizes[2];
        $script.= <<<LUA

exists=cmd('exists','{$plan}')

if exists==0 and {$max1}<0 then
    do return 0 end
end

z3={{$z3}}
z6={{$z6}}

-- 组三
ret=cmd('zrangebyscore','{$plan}',{$max1},'+inf')
if (#ret==0) then
    do return 1 end
end

_codes={}
for _,str in pairs(ret) do
    _codes={}
    str:gsub(".",function(c) table.insert(_codes,c) end)
    _codes={_codes[{$pos[0]}],_codes[{$pos[1]}],_codes[{$pos[2]}]}
    table.sort(_codes)
    _code=table.concat(_codes)

    for _,code in pairs(z3) do
        if code==_code then
            do return 0 end
        end
    end
end

-- 组六
ret=cmd('zrangebyscore','{$plan}',{$max2},'+inf')
if (#ret==0) then
    do return 1 end
end

_codes={}
for _,str in pairs(ret) do
    _codes={}
    str:gsub(".",function(c) table.insert(_codes,c) end)
    _codes={_codes[{$pos[0]}],_codes[{$pos[1]}],_codes[{$pos[2]}]}
    table.sort(_codes)
    _code=table.concat(_codes)

    for _,code in pairs(z6) do
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
        //0
        //组三或组六

        $c = trim($sCodes);

        $z3=$z6=[];
        for ($i=0;$i<=9;$i++) {
            for ($j=0;$j<=9;$j++) {
                for ($k=0;$k<=9;$k++) {
                    //忽略没包含的号码
                    if($i!=$c && $j!=$c && $k!=$c) continue;
                    if($i==$j && $i==$k) continue;
                    if($i==$j || $i==$k || $j==$k){
                        //组三
                        $arr = [$i,$j,$k];
                        sort($arr);
                        $z3["{" . implode(',', $arr) . "}"] = 1;
                    }else{
                        //组六
                        $arr = [$i,$j,$k];
                        sort($arr);
                        $z6["{" . implode(',', $arr) . "}"] = 1;
                    }
                }
            }
        }

        $z3=implode(",",array_keys($z3));
        $z6=implode(",",array_keys($z6));

        $diff=array_values(array_diff($this->lottery->position,$this->levels[1]['position']));
        $intersect=array_values(array_intersect($this->lottery->position,$this->levels[1]['position']));
        $positions=implode(",",$this->lottery->position);
        $x3=count($this->lottery->position)==3;

        $script='';

        $script.= <<<LUA

codes={{$z3}}
_codes={}
for _,code in pairs(codes) do
    fun.P(code,3,_codes)
end

mix={}
for _code,_ in pairs(_codes) do
    mix={}
    _code:gsub(".",function(c) table.insert(mix,c) end)
    $intersect[0]=mix[1]
    $intersect[1]=mix[2]
    $intersect[2]=mix[3]

LUA;

        if($x3){
            $script.=<<<LUA
        cmd('zincrby','{$plan}',{$prizes[1]},table.concat({{$positions}}))

LUA;
        }else{
            $script.=<<<LUA
        for _,{$diff[0]} in pairs({0,1,2,3,4,5,6,7,8,9}) do
        for _,{$diff[1]} in pairs({0,1,2,3,4,5,6,7,8,9}) do
            cmd('zincrby','{$plan}',{$prizes[1]},table.concat({{$positions}}))
        end
        end

LUA;
        }
        $script.=<<<LUA

end


codes={{$z6}}
_codes={}
for _,code in pairs(codes) do
    fun.P(code,3,_codes)
end

for _code,_ in pairs(_codes) do
    mix={}
    _code:gsub(".",function(c) table.insert(mix,c) end)
    $intersect[0]=mix[1]
    $intersect[1]=mix[2]
    $intersect[2]=mix[3]

LUA;

        if($x3){
            $script.=<<<LUA
        cmd('zincrby','{$plan}',{$prizes[2]},table.concat({{$positions}}))

LUA;
        }else{
            $script.=<<<LUA
        for _,{$diff[0]} in pairs({0,1,2,3,4,5,6,7,8,9}) do
        for _,{$diff[1]} in pairs({0,1,2,3,4,5,6,7,8,9}) do
            cmd('zincrby','{$plan}',{$prizes[2]},table.concat({{$positions}}))
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
