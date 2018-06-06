<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class BankController extends Controller
{
    //
    public function qu()
    {
        //参数mid。num.
        $input = Input::all();
        $rules = [
            'mid' => 'required|integer',
            'qu' => 'required|integer',
            'pwd' => 'required|digits:6'
        ];
        $message = [
            'mid.required' => 'mid不能为空',
            'mid.integer' => 'mid必须为整型',
            'qu.required' => '金额不能为空',
            'qu.integer' => '金额必须为整型',
            'pwd.required' => '密码不能为空',
            'pwd.integer' => '密码必须为6位'
        ];
        $validator = validator($input, $rules, $message);
        if($validator->passes()){
            $midInfo = DB::table('member')
                ->select('id', 'num', 'banknum', 'bankpwd')
                ->where('id', $input['mid'])
                ->first();
            //dd($midInfo);
            if(!$midInfo) return ['status' => 0, 'msg' => '查无此人'];
            if($input['pwd'] != $midInfo->bankpwd) return ['status' => 0, 'msg' => '密码错误'];
            if($input['qu'] > $midInfo->banknum) return ['status' => 0, 'msg' => '取出金额大于银行内金额'];
            $nowNum = $midInfo->num + $input['qu'];
            $nowBanknum = $midInfo->banknum - $input['qu'];
            //更新表
            DB::beginTransaction();
            $result = DB::table('member')
                ->where('id', $input['mid'])
                ->select('num', 'banknum')
                ->update(['num' => $nowNum, 'banknum' => $nowBanknum]);
            $log = DB::table('member_log')->
               insert([
               'mid' => $input['mid'],
               'gid' => 0,
               'num' => $input['qu'],
               'type' => 11,
               'created_at' => time(),
               'card_id' => 0
               ]
           );
            if($result && $log){
                DB::commit();
                return ['status' => 1, 'num' => $nowNum, 'banknum' => $nowBanknum];
            }else{
                //取款失败
                DB::rollBack();
                return ['status' => 0, 'msg' => '取款失败'];
            }
        }else{
            return [
                'status' => 0,
                'msg' => $validator->errors()->first()
            ];
        }
    }


    public function cun()
    {
        //参数mid。num.
        $input = Input::all();
        $rules = [
            'mid' => 'required|integer',
            'cun' => 'required|integer'
        ];
        $message = [
            'mid.required' => 'mid不能为空',
            'mid.integer' => 'mid必须为整型',
            'cun.required' => '金额不能为空',
            'cun.integer' => '金额必须为整型'
        ];
        $validator = validator($input, $rules, $message);
        if($validator->passes()){
            $midInfo = DB::table('member')
                ->select('id', 'num', 'banknum')
                ->where('id', $input['mid'])
                ->first();
            if(!$midInfo) return ['status' => 0, 'msg' => '查无此人'];
            if($input['cun'] > $midInfo->num) return ['status' => 0, 'msg' => '存入金额大于用户当前金额'];
            $nowNum = $midInfo->num - $input['cun'];
            $nowBanknum = $midInfo->banknum + $input['cun'];
            DB::beginTransaction();
            $result = DB::table('member')
                ->where('id', $input['mid'])
                ->select('num', 'banknum')
                ->update(['num' => $nowNum, 'banknum' => $nowBanknum]);
            $log = DB::table('member_log')->
            insert([
                    'mid' => $input['mid'],
                    'gid' => 0,
                    'num' => -$input['cun'],
                    'type' => 12,//11是取，12是存
                    'created_at' => time(),
                    'card_id' => 0
                ]
            );
            if($result && $log){
                DB::commit();
                return ['status' => 1, 'num' => $nowNum, 'banknum' => $nowBanknum];
            }else{
                //取款失败
                DB::rollBack();
                return ['status' => 0, 'msg' => '取款失败'];
            }
        }else{
            return [
                'status' => 0,
                'msg' => $validator->errors()->first()
            ];
        }
    }

    
    public function openBank()
    {
        $input = Input::all();
        if(isset($input['mid']) && !empty($input['mid'])){
            $midInfo = DB::table('member')
                ->select('bankpwd')
                ->where('id', $input['mid'])
                ->first();
            if(!$midInfo) return ['status' => 2, 'msg' => '查无此人'];
            if($midInfo->bankpwd == 0){
                return [
                    'status' => 0,
                    'msg' => '还未设置密码'
                ];
            }else{
                return [
                    'status' => 1,
                    'msg' => '进入银行界面'
                ];
            }
        }else{
            return [
                'status' => 2,
                'msg' => '参数错误'
            ];
        }
    }


    public function setBankpwd()
    {
        $input = Input::all();
        //验证
        $rules = [
            'mid' => 'required|integer',
            'pwd' => 'required|digits:6'
        ];
        $message = [
            'mid.required' => 'mid不能为空',
            'mid.integer' => 'mid必须为整型',
            'pwd.required' => '密码不能为空',
            'pwd.digits' => '密码必须为6位'
        ];
        $validator = validator($input, $rules, $message);
        if($validator->passes()){
            //可以加入数据库
            $res = DB::table('member')
                ->where('id', $input['mid'])
                ->update(['bankpwd' => $input['pwd']]);
            if($res){
                return [
                    'status' => 1,
                    'msg' => 'ok'
                ];
            }else{
                return [
                    'status' => 0,
                    'msg' => '设置失败稍后再试'
                ];
            }
        }else{
            return [
                'status' => 0,
                'msg' => $validator->errors()->first()
            ];
        }
    }

    public function give()
    {
        //赠送
        $input = Input::all();
        $rules = [
            'mid' => 'required|integer',
            'pwd' => 'required|digits:6',
            'give' => 'required|integer',
            'givemid' => 'required|integer'
        ];
        $message = [
            'mid.required' => 'mid不能为空',
            'mid.integer' => 'mid必须为整型',
            'pwd.required' => '密码不能为空',
            'pwd.digits' => '密码必须为6位',
            'give.required' => '金额不能为空',
            'give.integer' => '金额必须为整型',
            'givemid.required' => 'mid不能为空',
            'givemid.integer' => 'mid必须为整型'
        ];
        $validator = validator($input, $rules, $message);
        if($validator->passes()){
            $midInfo = DB::table('member')
                ->where('id', $input['mid'])
                ->select('num', 'bankpwd', 'banknum')
                ->first();
            $giveMidInfo = DB::table('member')
                ->where('id', $input['givemid'])
                ->select('num', 'bankpwd', 'banknum')
                ->first();
            if(!$midInfo) return ['status' => 0, 'msg' => '当前用户id不存在'];
            if(!$giveMidInfo) return ['status' => 0, 'msg' => '赠送id不存在'];
            if($giveMidInfo->bankpwd == 0) return ['status' => 0, 'msg' => '赠送id尚未开通银行'];
            if($midInfo->bankpwd != $input['pwd']) return ['status' => 0, 'msg' => '银行密码错误'];
            if($midInfo->banknum < $input['give']) return ['status' => 0, 'msg' => '当前用户银行金额小于要赠送的金额'];
            //可以转了
            $midBankNum = $midInfo->banknum - $input['give'];
            $giveMidBanknum = $giveMidInfo->banknum + $input['give'];
            DB::beginTransaction();
            $res1 = DB::table('member')
                ->where('id', $input['mid'])
                ->update(['banknum' => $midBankNum]);
            $res2 = DB::table('member')
                ->where('id', $input['givemid'])
                ->update(['banknum' => $giveMidBanknum]);
            $res3 = DB::table('bankinfo')
                ->insert(['outmid' => $input['mid'], 'inmid' => $input['givemid'], 'num' => $input['give'], 'time' => time()]);
            if($res1 && $res2 && $res3){
                DB::commit();
                return [
                    'status' => 1,
                    'data' => [
                        'midnum' => $midInfo->num,
                        'midbanknum' => $midBankNum,
                        'outmidnum' => $giveMidInfo->num,
                        'outbanknum' => $giveMidBanknum
                    ]
                ];
            }else{
                DB::rollback();
                return [
                    'status' => 0,
                    'msg' => '转出失败'
                ];
            }
        }else{
            return [
                'status' => 0,
                'msg' => $validator->errors()->first()
            ];
        }

    }

    //转出
    public function outRec($input)
    {
        //$input = Input::all();
        if(isset($input['mid']) && !empty($input['mid'])){
            $res = DB::table('bankinfo as b')
                ->leftjoin('member as m', 'b.inmid', '=', 'm.id')
                ->select('m.nickname', 'm.id as mid', 'b.id as lsh', 'b.num', 'b.time')
                ->where('outmid', $input['mid'])
                ->limit(20)
                ->orderBy('time', 'desc')
                ->get();
            if(!$res) return ['status' => 0, 'msg' => '无记录'];
            foreach ($res as $v){
                $arr[] = [
                    'lsh' => $v->lsh,
                    'nickname' => $v->nickname,
                    'id' => $v->mid,
                    'num' => $v->num,
                    'time' => date('Y-m-d H:i:s', $v->time),
                    'type' => 2
                ];
            }
            return [
                'status' => 1,
                'data' => $arr
            ];
        }else{
            return ['status' => 0, 'msg' => '参数错误'];
        }


    }

    //转入
    public function inRec($input)
    {
        //$input = Input::all();
        if(isset($input['mid']) && !empty($input['mid'])){
            $res = DB::table('bankinfo as b')
                ->leftjoin('member as m', 'b.outmid', '=', 'm.id')
                ->select('m.nickname', 'm.id as mid', 'b.id as lsh', 'b.num', 'b.time')
                ->where('inmid', $input['mid'])
                ->limit(20)
                ->orderBy('time', 'desc')
                ->get();
            //dd($res);
            if(!$res) return ['status' => 0, 'msg' => '无记录'];
            foreach ($res as $v){
                $arr[] = [
                    'lsh' => $v->lsh,
                    'nickname' => $v->nickname,
                    'id' => $v->mid,
                    'num' => $v->num,
                    'time' => date('Y-m-d H:i:s', $v->time),
                    'type'=> 1
                ];
            }
            return [
                'status' => 1,
                'data' => $arr
            ];
        }else{
            return ['status' => 0, 'msg' => '参数错误'];
        }
    }


    public function rec()
    {
        $input = Input::all();
        //转入
        $resultIn = $this->inRec($input);
        //转出
        $resultOut = $this->outRec($input);
        if($resultIn['status'] == 0 && $resultOut['status'] == 0){
            return ['status' => 0, 'msg' => '无记录'];
        }else{
            if($resultIn['status'] == 1 && $resultOut['status'] == 0){
                return $resultIn;
            }elseif($resultIn['status'] == 0 && $resultOut['status'] == 1){
                return $resultOut;
            }elseif($resultIn['status'] == 1 && $resultOut['status'] == 1){
                $result = array_merge($resultIn['data'], $resultOut['data']);
                return [
                    'status' => 1,
                    'data' => $result
                ];
            }
        }
    }
}
