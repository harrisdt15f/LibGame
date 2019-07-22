<?php
namespace App\Lib\Game\Method;

// pk10 定位单胆
trait BasePk10DWD
{
    public $positionsTpl = ['1' => '冠军', '2' => '亚军', '3' => '季军', '4' => '第四名', '5' => '第五名'];
    public $supportExpand = true;

    public function bingoCode(array $numbers)
    {
        $result = [];
        $arr = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];

        foreach ($numbers as $pos => $code) {
            $tmp = [];
            foreach ($arr as $_code) {
                $tmp[] = (int) ($code == $_code);
            }
            $result[] = $tmp;
        }

        return $result;
    }

    // 供测试用 生成随机投注
    public function randomCodes(&$poss = array())
    {
        $positions = array('w' => '', 'q' => '', 'b' => '', 's' => '', 'g' => '');
        if (!$this->pos) {
            // 集合
            $pos = (array) array_rand($positions, rand(1, count($positions)));
        } else {
            $pos = str_split($this->pos);
        }

        // 按为生成随机
        foreach ($pos as $k => $v) {
            $positions[$v] = parent::randomCodes();
        }

        return implode('|', $positions);
    }

    public function expand($sCodes, $pos = null)
    {
        $result = [];
        $aCodes = explode('|', $sCodes);
        foreach ($aCodes as $index => $code) {
            if (trim($code) === '') {
                continue;
            }

            switch ($index) {
                case 0:
                    $methodId = $this->id . "_1";
                    break;
                case 1:
                    $methodId = $this->id . "_2";
                    break;
                case 2:
                    $methodId = $this->id . "_3";
                    break;
                case 3:
                    $methodId = $this->id . "_4";
                    break;
                case 4:
                    $methodId = $this->id . "_5";
                    break;
                default:
                    $methodId = "";
            }

            if (!$methodId) {
                continue;
            }

            $result[] = array(
                'method_id' => $methodId,
                'codes' => $code,
                'count' => count(explode('&', $code)),
            );
        }

        return $result;
    }
}
