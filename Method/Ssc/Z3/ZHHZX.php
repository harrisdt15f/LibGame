<?php namespace App\Lib\Game\Method\Ssc\Z3;
use App\Lib\Game\Method\Ssc\Base;

//混合组选
class ZHHZX extends Base
{
    //123,531,534,534,123
    public $all_count =270;
    public static $filterArr = array(0=>1,1=>1,2=>1,3=>1,4=>1,5=>1,6=>1,7=>1,8=>1,9=>1);

    //是否复式
    public function isMulti()
    {
        return false;
    }

    //供测试用 生成随机投注
    public function randomCodes()
    {
        return implode('',(array)array_rand(self::$filterArr,3));
    }

    public function fromOld($codes)
    {
        //122|232|342
        return implode(',',explode('|',$codes));
    }

    public function parse64($codes)
    {
        if(strpos($codes,'base64:')!==false){
            $ex=explode('base64:',$codes);
            $codes=$this->_parse64($ex[1],3);
            if(is_array($codes)){
                $codes=implode(',',$codes);
            }
        }
        return $codes;
    }

    public function encode64($codes)
    {
        return $this->_encode64(explode(',',$codes));
    }

    public function regexp($sCodes)
    {
        //校验
        $regexp = '/^(([0-9]{3}\,)*[0-9]{3})$/';
        if( !preg_match($regexp,$sCodes) ) return false;

        $temp = explode(",", $sCodes);
        $iNums = count(array_filter(array_unique($temp)));

        if($iNums != count($temp)) return false;

        //排除豹子号
        foreach($temp as $c){
            if($c[0] == $c[1] && $c[1]==$c[2]){
                return false;
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
        $str = $this->strOrder(implode('',$numbers));

        $aCode = explode(',', $sCodes);

        if ($levelId == '1') {
            $flip = array_filter(array_count_values($numbers), function ($v) {
                return $v == 2;
            });

            //组三
            if (count($flip) == 1) {
                foreach ($aCode as $code) {
                    if ($str === $this->strOrder($code)) {
                        return 1;
                    }
                }
            }
        } elseif ($levelId == '2') {
            $flip = array_filter(array_count_values($numbers), function ($v) {
                return $v >= 2;
            });

            //组六
            if (count($flip) == 0) {
                foreach ($aCode as $code) {
                    if ($str === $this->strOrder($code)) {
                        return 1;
                    }
                }
            }
        }
    }


    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        $aCodes = explode(',', $sCodes);
        $z3=[];
        $z6=[];
        foreach($aCodes as $v1){
            if($v1[0]==$v1[1] && $v1[1]==$v1[2]) continue;

            if($v1[0]==$v1[1] || $v1[1]==$v1[2] || $v1[0]==$v1[2]){
                //组三
                if($v1[0]==$v1[1]){
                    $n1=$v1[0];
                    $n2=$v1[2];
                }elseif($v1[1]==$v1[2]){
                    $n1=$v1[1];
                    $n2=$v1[0];
                }else{
                    $n1=$v1[0];
                    $n2=$v1[1];
                }

                $z3[$this->strOrder($n1.$n1.$n2)]=1;
            }else{
                $z6[$this->strOrder($v1[0].$v1[1].$v1[2])]=1;
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
_codes={}

-- 一等奖
ret=cmd('zrangebyscore','{$plan}',{$max1},'+inf')

if (#ret==0) then
    do return 1 end
end

for _,str in pairs(ret) do
    str:gsub(".",function(c) table.insert(_codes,c) end)
    _codes={_codes[{$pos[0]}],_codes[{$pos[1]}],_codes[{$pos[2]}]}
    table.sort(_codes)
    _code=table.concat(_codes)

    for _,code in pairs(z3) do
        if (code==_code) then
            do return 0 end
        end
    end
end

-- 二等奖
ret=cmd('zrangebyscore','{$plan}',{$max2},'+inf')

if (#ret==0) then
    do return 1 end
end

for _,str in pairs(ret) do
    str:gsub(".",function(c) table.insert(_codes,c) end)
    _codes={_codes[{$pos[0]}],_codes[{$pos[1]}],_codes[{$pos[2]}]}
    table.sort(_codes)
    _code=table.concat(_codes)

    for _,code in pairs(z6) do
        if (code==_code) then
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
        $aCodes = explode(',', $sCodes);
        $z3=$z6=[];
        foreach($aCodes as $v1){
            if($v1[0]==$v1[1] && $v1[1]==$v1[2]) continue;

            if($v1[0]==$v1[1] || $v1[1]==$v1[2] || $v1[0]==$v1[2]){
                //组三
                if($v1[0]==$v1[1]){
                    $n1=$v1[0];
                    $n2=$v1[2];
                }elseif($v1[1]==$v1[2]){
                    $n1=$v1[1];
                    $n2=$v1[0];
                }else{
                    $n1=$v1[0];
                    $n2=$v1[1];
                }

                $z3[$n1.$n1.$n2]=1;
                $z3[$n1.$n2.$n1]=1;
                $z3[$n2.$n1.$n1]=1;
            }else{
                $z6[$v1[0].$v1[1].$v1[2]]=1;
                $z6[$v1[0].$v1[2].$v1[1]]=1;
                $z6[$v1[1].$v1[0].$v1[2]]=1;
                $z6[$v1[1].$v1[2].$v1[0]]=1;
                $z6[$v1[2].$v1[0].$v1[1]]=1;
                $z6[$v1[2].$v1[1].$v1[0]]=1;
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

        $diff=array_values(array_diff($this->lottery->position,$this->levels[1]['position']));
        $intersect=array_values(array_intersect($this->lottery->position,$this->levels[1]['position']));
        $positions=implode(",",$this->lottery->position);
        $x3=count($this->lottery->position)==3;

        //不同奖级的中奖金额
        $script= <<<LUA

codes={{$z3}}
for _,_code in pairs(codes) do
    mix={}
    _code:gsub(".",function(c) table.insert(mix,c) end)
    $intersect[0]=mix[1]
    $intersect[1]=mix[2]
    $intersect[2]=mix[3]

LUA;
        if($x3){
            $script.= <<<LUA
        cmd('zincrby','{$plan}',{$prizes[1]},table.concat({{$positions}}))

LUA;
        }else{
            $script.= <<<LUA
        for _,$diff[0] in pairs({0,1,2,3,4,5,6,7,8,9}) do
        for _,$diff[1] in pairs({0,1,2,3,4,5,6,7,8,9}) do
            cmd('zincrby','{$plan}',{$prizes[1]},table.concat({{$positions}}))
        end
        end

LUA;
        }

        $script.= <<<LUA

end

codes={{$z6}}
for _,_code in pairs(codes) do
    mix={}
    _code:gsub(".",function(c) table.insert(mix,c) end)
    $intersect[0]=mix[1]
    $intersect[1]=mix[2]
    $intersect[2]=mix[3]

LUA;
        if($x3){
            $script.= <<<LUA
        cmd('zincrby','{$plan}',{$prizes[2]},table.concat({{$positions}}))

LUA;
        }else{
            $script.= <<<LUA
        for _,$diff[0] in pairs({0,1,2,3,4,5,6,7,8,9}) do
        for _,$diff[1] in pairs({0,1,2,3,4,5,6,7,8,9}) do
            cmd('zincrby','{$plan}',{$prizes[2]},table.concat({{$positions}}))
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
