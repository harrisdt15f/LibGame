<?php namespace App\Lib\Game\Method\Ssc\EX;

use App\Lib\Game\Method\Ssc\Base;

// 组选包胆
class HZU2BD extends Base
{
    //1
    public $all_count =90;
    public static $filterArr = array(0 => 9, 1 => 9, 2 => 9, 3 => 9, 4 => 9, 5 => 9, 6 => 9, 7 => 9, 8 => 9, 9 => 9);

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

        $aCodes = explode('&', $sCodes);

        //不包含对子
        if ($numbers[0] != $numbers[1]) {
            foreach ($aCodes as $code) {
                if (in_array($code, $numbers)) {
                    return 1;
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

        $codes=[];
        for ($i=0;$i<=9;$i++) {
            for ($j=0;$j<=9;$j++) {
                if($i!=$c && $j!=$c) continue;
                if($i==$j) continue; //不含对子号
                $codes[$this->strOrder($i.$j)]=1;
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
        //0
        //组三或组六

        $c = trim($sCodes);

        $z=[];
        for ($i=0;$i<=9;$i++) {
            for ($j=0;$j<=9;$j++) {
                if($i!=$c && $j!=$c) continue;
                if($i==$j) continue; //不含对子号
                $z[$i.$j]=1;
            }
        }

        if(count($z)>0){
            $codes="'".implode("','",array_keys($z))."'";
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
            $script.=<<<LUA
        for _,{$diff[0]} in pairs({0,1,2,3,4,5,6,7,8,9}) do
            cmd('zincrby','{$plan}',{$prizes[1]},table.concat({{$positions}}))
        end

LUA;
        }else{
            $script.=<<<LUA
        for _,{$diff[0]} in pairs({0,1,2,3,4,5,6,7,8,9}) do
        for _,{$diff[1]} in pairs({0,1,2,3,4,5,6,7,8,9}) do
        for _,{$diff[2]} in pairs({0,1,2,3,4,5,6,7,8,9}) do
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
