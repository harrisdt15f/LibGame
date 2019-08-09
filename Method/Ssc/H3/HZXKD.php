<?php namespace App\Lib\Game\Method\Ssc\H3;

use App\Lib\Game\Method\Ssc\Base;
use Illuminate\Support\Facades\Validator;

// 后三 直选跨度
class HZXKD extends Base
{
	public function regexp($sCodes)
	{
        $data['code'] = $sCodes;
        $validator = Validator::make($data, [
        	$data['code'] => 'regex:/^([0-9]&){0,9}[0-9]$/'
        ]);
        if ($validator->fails()) {
            return false;
        }
        return true;
	}
}