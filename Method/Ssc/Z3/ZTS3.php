<?php namespace App\Lib\Game\Method\Ssc\Z3;

use App\Lib\Game\Method\Ssc\Base;
use Illuminate\Support\Facades\Validator;

//3星特殊
class ZTS3 extends Base
{
    //b&d&s
    public $all_count =3;
    static public $bds = array(
        'b' => '豹子',
        's' => '顺子',
        'd' => '对子',
    );

    static public $bz=array('000','111','222','333','444','555','666','777','888','999');
    static public $sz= ["012","021","102","120","123","132","201","210","213","231","234","243","312","321","324","342","345","354","423","432","435","453","456","465","534","543","546","564","567","576","645","654","657","675","678","687","756","765","768","786","789","798","867","876","879","897","978","987" , "890","809","089","980","908","098" ,"091","019","910","901","190","109"];
    static public $dz= ["001","002","003","004","005","006","007","008","009","010","011","020","022","030","033","040","044","050","055","060","066","070","077","080","088","090","099","100","101","110","112","113","114","115","116","117","118","119","121","122","131","133","141","144","151","155","161","166","171","177","181","188","191","199","200","202","211","212","220","221","223","224","225","226","227","228","229","232","233","242","244","252","255","262","266","272","277","282","288","292","299","300","303","311","313","322","323","330","331","332","334","335","336","337","338","339","343","344","353","355","363","366","373","377","383","388","393","399","400","404","411","414","422","424","433","434","440","441","442","443","445","446","447","448","449","454","455","464","466","474","477","484","488","494","499","500","505","511","515","522","525","533","535","544","545","550","551","552","553","554","556","557","558","559","565","566","575","577","585","588","595","599","600","606","611","616","622","626","633","636","644","646","655","656","660","661","662","663","664","665","667","668","669","676","677","686","688","696","699","700","707","711","717","722","727","733","737","744","747","755","757","766","767","770","771","772","773","774","775","776","778","779","787","788","797","799","800","808","811","818","822","828","833","838","844","848","855","858","866","868","877","878","880","881","882","883","884","885","886","887","889","898","899","900","909","911","919","922","929","933","939","944","949","955","959","966","969","977","979","988","989","990","991","992","993","994","995","996","997","998"];

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $rand=rand(1,3);
        return implode('&',(array)array_rand(array_flip(self::$bds),$rand));
    }

    public function fromOld($codes)
    {
        //0|1|2
        $codes = str_replace(array('0','1','2'),array('b','s','d'),$codes);
        return implode('&',explode('|',$codes));
    }

    public function fromHh($codes)
    {
        //012
        $codes = str_replace(array('0','1','2'),array('b','s','d'),$codes);
        return implode('&',str_split($codes));
    }

    //格式解析
    public function resolve($codes)
    {
        return strtr($codes,array_flip(self::$bds));
    }

    //还原格式
    public function unresolve($codes)
    {
        return strtr($codes,self::$bds);
    }

    public function regexp($sCodes)
    {
        $data['code'] = $sCodes;
        $validator = Validator::make($data, [
            'code' => ['regex:/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-2&]{1,5}?)$/'],//0&1&2 豹子|顺子|对子
        ]);
        if ($validator->fails()) {
            return false;
        }
        return true;
    }

    public function count($sCodes)
    {
        return count(explode("&",$sCodes));
    }

    public function bingoCode(Array $numbers)
    {
        $result=[];

        //豹子?
        $result[]= intval(count(array_count_values($numbers))==1);
        //对子?
        $result[]= intval(count(array_count_values($numbers))==2);
        //顺子?
        sort($numbers);
        $result[]= intval( count(array_count_values($numbers))==3 && ( abs($numbers[0]-$numbers[1])==1 && abs($numbers[1]-$numbers[2])==1 ) );

        return [$result];
    }

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        sort($numbers);

        $aCodes = explode("&", $sCodes);

        if ($levelId == "1" && $numbers[0] == $numbers[1] && $numbers[1] == $numbers[2]) {
            //bz
            if (in_array('b', $aCodes)) {
                return 1;
            }
        } elseif ($levelId == "2") {
            $_code=implode('',$numbers);
            $_sz=array_flip(self::$sz);

            //sz
            if (in_array('s', $aCodes) && isset($_sz[$_code])) {
                return 1;
            }
        } elseif ($levelId == "3" && ($numbers[0] == $numbers[1] || $numbers[1] == $numbers[2] || $numbers[0] == $numbers[2])
            && !($numbers[0] == $numbers[1] && $numbers[1] == $numbers[2])
        ) {
            //dz 非豹子号
            if (in_array('d', $aCodes)) {
                return 1;
            }
        }
    }


    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        //b&s&d

        $bz_codes=$sz_codes=$dz_codes=[];

        $aCodes = explode('&', $sCodes);
        if(in_array('b',$aCodes)){
            $bz_codes = self::$bz;
        }

        if(in_array('s',$aCodes)){
            $sz_codes = self::$sz;
        }

        if(in_array('d',$aCodes)){
            $dz_codes = self::$dz;
        }

        if(count($bz_codes)>0){
            $bz_codes="'".implode("','",$bz_codes)."'";
        }else{
            $bz_codes='';
        }

        if(count($sz_codes)>0){
            $sz_codes="'".implode("','",$sz_codes)."'";
        }else{
            $sz_codes='';
        }

        if(count($dz_codes)>0){
            $dz_codes="'".implode("','",$dz_codes)."'";
        }else{
            $dz_codes='';
        }

        $pos=array_keys(array_intersect($this->lottery->position,$this->levels[1]['position']));
        array_walk($pos,function(&$v){$v++;});

        $script=
            <<<LUA

LUA;

        $max1=$lockvalue-$prizes[1];
        $max2=$lockvalue-$prizes[2];
        $max3=$lockvalue-$prizes[3];

        $script.= <<<LUA

exists=cmd('exists','{$plan}')

if exists==0 and {$max1}<0 then
    do return 0 end
end

ret=cmd('zrangebyscore','{$plan}',{$max1},'+inf')

if (#ret==0) then
    do return 1 end
end

-- 豹子
codes={{$bz_codes}}
_codes={}

for _,str in pairs(ret) do
    _codes={}
    str:gsub(".",function(c) table.insert(_codes,c) end)
    _codes={_codes[{$pos[0]}],_codes[{$pos[1]}],_codes[{$pos[2]}]}
    table.sort(_codes)
    _code=table.concat(_codes)

    for _,code in pairs(codes) do
        if code==_code then
            do return 0 end
        end
    end
end

-- 顺子
if exists==0 and {$max2}<0 then
    do return 0 end
end

ret=cmd('zrangebyscore','{$plan}',{$max2},'+inf')

if (#ret==0) then
    do return 1 end
end

codes={{$sz_codes}}
_codes={}

for _,str in pairs(ret) do
    _codes={}
    str:gsub(".",function(c) table.insert(_codes,c) end)
    _codes={_codes[{$pos[0]}],_codes[{$pos[1]}],_codes[{$pos[2]}]}
    table.sort(_codes)
    _code=table.concat(_codes)

    for _,code in pairs(codes) do
        if code==_code then
            do return 0 end
        end
    end
end

-- 对子
if exists==0 and {$max3}<0 then
    do return 0 end
end

ret=cmd('zrangebyscore','{$plan}',{$max3},'+inf')

if (#ret==0) then
    do return 1 end
end

codes={{$dz_codes}}
_codes={}

for _,str in pairs(ret) do
    _codes={}
    str:gsub(".",function(c) table.insert(_codes,c) end)
    _codes={_codes[{$pos[0]}],_codes[{$pos[1]}],_codes[{$pos[2]}]}
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
        //b&s&d

        $bz_codes=$sz_codes=$dz_codes=[];

        $aCodes = explode('&', $sCodes);
        if(in_array('b',$aCodes)){
            $bz_codes = self::$bz;
        }

        if(in_array('s',$aCodes)){
            $sz_codes = self::$sz;
        }

        if(in_array('d',$aCodes)){
            $dz_codes = self::$dz;
        }

        if(count($bz_codes)>0){
            $bz_codes="'".implode("','",$bz_codes)."'";
        }else{
            $bz_codes='';
        }

        if(count($sz_codes)>0){
            $sz_codes="'".implode("','",$sz_codes)."'";
        }else{
            $sz_codes='';
        }

        if(count($dz_codes)>0){
            $dz_codes="'".implode("','",$dz_codes)."'";
        }else{
            $dz_codes='';
        }


        $diff=array_values(array_diff($this->lottery->position,$this->levels[1]['position']));
        $intersect=array_values(array_intersect($this->lottery->position,$this->levels[1]['position']));
        $positions=implode(",",$this->lottery->position);

        $x3=count($this->lottery->position)==3;

        $script='';
        //不同奖级的中奖金额
        $script.= <<<LUA

bz_codes={{$bz_codes}}
sz_codes={{$sz_codes}}
dz_codes={{$dz_codes}}


-- 豹子
for _,_code in pairs(bz_codes) do
    mix={}
    _code:gsub(".",function(c) table.insert(mix,c) end)
    $intersect[0]=mix[1]
    $intersect[1]=mix[2]
    $intersect[2]=mix[3]

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


-- 顺子
for _,_code in pairs(sz_codes) do
    mix={}
    _code:gsub(".",function(c) table.insert(mix,c) end)
    $intersect[0]=mix[1]
    $intersect[1]=mix[2]
    $intersect[2]=mix[3]

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


-- 对子
for _,_code in pairs(dz_codes) do
    mix={}
    _code:gsub(".",function(c) table.insert(mix,c) end)
    $intersect[0]=mix[1]
    $intersect[1]=mix[2]
    $intersect[2]=mix[3]

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




LUA;

        return $script;
    }

}
