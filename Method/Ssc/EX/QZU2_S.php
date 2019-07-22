<?php namespace App\Lib\Game\Method\Ssc\EX;

use App\Lib\Game\Method\Ssc\Base;

class QZU2_S extends Base
{
    // 12,21,31
    public $all_count =45;
    public static $dzArr = array('00'=>1, '11'=>1, '22'=>1, '33'=>1, '44'=>1, '55'=>1, '66'=>1, '77'=>1, '88'=>1, '99'=>1);

    public static $filterArr = array(0=>1,1=>1,2=>1,3=>1,4=>1,5=>1,6=>1,7=>1,8=>1,9=>1);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $rand=2;
        return implode('',(array)array_rand(self::$filterArr,$rand));
    }

    public function fromOld($codes)
    {
        //12|23|34
        return implode(',',explode('|',$codes));
    }

    public function regexp($sCodes)
    {
        // 格式
        if (!preg_match("/^(([0-9]{2}\,)*[0-9]{2})$/", $sCodes)) {
            return false;
        }

        // 重复号码
        $temp =explode(",",$sCodes);
        $i = count(array_filter(array_unique($temp)));
        if($i != count($temp)) return false;

        // 对子号
        $exists=[];
        $dzArr = self::$dzArr;
        foreach($temp as $c){
            if(isset($dzArr[$c])){
                // 不包含对子号
                return false;
            }

            //组选不能重复号码
            $vv=$this->strOrder($c);
            if(isset($exists[$vv])) return false;
            $exists[$vv]=1;
        }

        return true;
    }

    public function count($sCodes)
    {
        return count(explode(",",$sCodes));
    }

    // 判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $str1 = $this->strOrder(implode('', $numbers));
        $str2 = $this->strOrder(implode('', $numbers),true);

        $aCodes = explode(',', $sCodes);

        if ($numbers[0] != $numbers[1]) {
            foreach ($aCodes as $code) {
                if ($code === $str1 || $code === $str2) {
                    return 1;
                }
            }
        }
    }


    // 检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        $aCodes = explode(',', $sCodes);
        $codes=[];
        foreach($aCodes as $code){
            $codes[$this->strOrder($code)]=1;
        }
        $codes="'".implode("','",array_keys($codes))."'";

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
        $aCodes = explode(',', $sCodes);
        $codes=[];
        foreach($aCodes as $v1){
            $codes[]=$v1[0].$v1[1];
            $codes[]=$v1[1].$v1[0];
        }
        $codes="'".implode("','",$codes)."'";

        $diff=array_values(array_diff($this->lottery->position,$this->levels[1]['position']));
        $intersect=array_values(array_intersect($this->lottery->position,$this->levels[1]['position']));
        $positions=implode(",",$this->lottery->position);
        $x3=count($this->lottery->position)==3;

        //不同奖级的中奖金额
        $script= <<<LUA

codes={{$codes}}
for _,code in pairs(codes) do
    mix={}
    code:gsub(".",function(c) table.insert(mix,c) end)
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
