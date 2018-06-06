<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\ServerBag;

class LoginController extends Controller
{
    public function create_check($ip,$machine_ip){
        $re1 = DB::table('restrict')->where(['ip'=>$ip,'type'=>0])->first();
        if($re1){
            if($re1->limit_regist){
                if($re1->limit_time){
                    if(time() < $re1->limit_time){
                        return true;
                    }
                }else{
                    return true;
                }
            }
        }
        $re2 = DB::table('restrict')->where(['ip'=>$machine_ip,'type'=>1])->first();
        if($re2){
            if($re2->limit_regist){
                if($re2->limit_time){
                    if(time() < $re2->limit_time){
                        return true;
                    }
                }else{
                    return true;
                }
            }
        }
        $set = DB::table('settings')->where('id',1)->first();
        $ip_s = DB::table('member_ip')->where('ip',$ip)->count();
        $machine_s = DB::table('member_ip')->where('machine_ip',$machine_ip)->count();
        if($set->param3){
            if($machine_s >= $set->param3){
                return true;
            }
        }
        if($set->param4){
            if($ip_s >= $set->param4){
                return true;
            }
        }
        return false;
    }
    //
    public function register()
    {
        //nickname、pwd、phone
        $input = Input::all();
        $rules = [
            'nickname' => 'required',
            'password' => 'required',
            'phone' => 'required|digits:11',
            'code' => 'required',
            'machine' => 'required'
        ];
        $message = [
            'nickname.required' => '昵称不能为空',
            'password.required' => '密码不能为空',
            'phone.required' => '手机号不能为空',
            'phone.digits' => '手机号格式不正确',
            'code.required' => '验证码不能为空',
             'machine.required'=>'缺少机器码！'
        ];
        $validator = validator($input, $rules, $message);
        if($validator->passes()){
            $ip = $_SERVER["REMOTE_ADDR"];
            $machine_ip = $input['machine'];
            $rele = $this->create_check($ip,$machine_ip);
            if($rele){
                return json_encode(['status' => 0, 'msg' => '手机或IP被限制注册!']);
            }

            $existsPhone = DB::table('member')
                ->select('phone')
                ->where('phone', $input['phone'])
                ->first();

            $existsnickname = DB::table('member')
                ->select('nickname')
                ->where('nickname', $input['nickname'])
                ->first();
            if($existsPhone){
                return [
                    'status' => 0,
                    'msg' => '手机号已存在'
                ];
            }elseif($existsnickname){
                return [
                    'status' => 0,
                    'msg' => '昵称已存在'
                ];
            }else{
                //判断验证码是否正确
                $code = DB::table('verification')
                    ->where('phone', $input['phone'])
                    ->select('code')
                    ->first();
                if($code){
                    if($code->code != $input['code']){
                        return [
                            'status' => 0,
                            'msg' => '验证码错误'
                        ];
                    }
                }else{
                    return [
                        'status' => 0,
                        'msg' => '验证码错误'
                    ];
                }

                $http = 'http://' . $_SERVER['SERVER_NAME'] .'/users/';
                $token = md5(time());
                $a = rand(0,6);
                $headimgurl = $http . $a . '.png';
                $sex = $input['sex'];
                $num = DB::table('settings')->where('id',1)->value('param2');
                $insertData = [
                    'phone' => $input['phone'],
                    'password' => md5($input['password']),
                    'nickname' => $input['nickname'],
                    'sex' => rand(1,2),
                    'headimgurl' => $headimgurl,
                    'token' => $token,
                    'ip' => $_SERVER["REMOTE_ADDR"],
                    'machine_ip'=>$machine_ip,
                    'login_time' => date('Y-m-d H:i:s',time()),
                    'created_at' => date('Y-m-d H:i:s',time()),
                    'version' => 1,
                    'num' => $num
                ];
                $mid = DB::table('member')
                    ->insertGetId($insertData);
                DB::table('member_ip')->insert([
                    'ip' => $ip,
                    'machine_ip'=>$machine_ip,
                    'mid'=>$mid,
                    'created_at'=>date('Y-m-d H:i:s',time())
                ]);
                return [
                    'status' => 1,
                    'data' => [
                        'mid' => $mid,
                        'token' => $token,
                        'headimgurl' => $headimgurl,
                        'sex' => $sex,
                        'nickname' => $input['nickname'],
                        'num' => $num
                    ]
                ];
            }
        }else{
            return [
                'status' => 0,
                'msg' => $validator->errors()->first()
            ];
        }
    }

    public function recode()
    {
        //nickname、pwd、phone
        $input = Input::all();
        $rules = [
            'phone' => 'required|digits:11',
            'code' => 'required'
        ];
        $message = [
            'phone.required' => '手机号不能为空',
            'phone.digits' => '手机号格式不正确',
            'code.required' => '验证码不能为空'
        ];
        $validator = validator($input, $rules, $message);
        if($validator->passes()){
            $code = DB::table('verification')
                    ->where('phone', $input['phone'])
                    ->select('code')
                    ->first();
                if($code){
                    if($code->code != $input['code']){
                        return [
                            'status' => 0,
                            'msg' => '验证码错误'
                        ];
                    }
                }else{
                    return [
                        'status' => 0,
                        'msg' => '验证码错误'
                    ];
                }
            return [
                        'status' => 1,
                        'msg' => '验证码正确'
                    ];
        }else{
            return [
                'status' => 0,
                'msg' => $validator->errors()->first()
            ];
        }
    }

    /*public function login()
    {
        $input = Input::all();
        if (!isset($input['phone']) || !isset($input['password']) || !isset($input['version'])) {
            return ['status' => 0, 'msg' => '缺少参数'];
        }
        $ip = $_SERVER["REMOTE_ADDR"];
        $token = md5(time());
        $member = DB::table('member')
            ->where('phone', $input['phone'])
            ->first();
        $shouchong = DB::table('shouchong')->where('id',1)->first();
        if($member){
            if($member->password == md5($input['password'])){
                if ($member->is_black == 1) {
                    return ['status' => 0, 'msg' => '当前用户被拉黑，请联系管理员!'];
                }
                if(Redis::sismember('zaixian-fish',$member->id) || Redis::sismember('zaixian-hongzhong',$member->id) || Redis::sismember('zaixian-jbddz',$member->id) || Redis::sismember('zaixian-jdddz',$member->id) || Redis::sismember('zaixian-jinhua',$member->id)){
                    return ['status' => 0, 'msg' => '玩家已在游戏中,不能重复登录!'];
                }

                $num = $member->num;
                if(Redis::hexists('fish-'.$member->id,'num')){
                    $num = $this->chuli_fish($member->id,$num);
                }
                DB::table('member')
                    ->where('id', $member->id)
                    ->update([
                        'token' => $token,
                        'time' => time(),
                        'ip' => $ip,
                        'version' => $input['version'],
                        'status' => 1
                    ]);
                return [
                    'status' => 1,
                    'data' => [
                        'mid' => $member->id,
                        'name'=>$member->name,
                        'token' => $token,
                        'room_id'=>$member->room_id,
                        'gid'=>$member->gid,
                        'nickname'=>$member->nickname,
                        'phone'=>$member->phone,
                        'agency_phone'=>$member->agency_phone,
                        'sex'=>$member->sex,
                        'headimgurl'=>$member->headimgurl,
                        'num' => $num,
                        'shouchong'=>$member->shouchong
                    ]

                ];
            }else{
                return [
                    'status' => 0,
                    'msg' => '密码错误'
                ];
            }
        }else{
            return [
                'status' => 0,
                'msg' => '尚未注册'
            ];
        }
    }*/

    public function resetpwd()
    {
        $input = Input::all();
        $rules = [
            'phone' => 'required|digits:11',
            'code' => 'required',
            'password' => 'required',
            'pwdRepeat' => 'required'
        ];
        $message = [
            'phone.required' => '手机号不能为空',
            'phone.digits' => '手机号格式不正确',
            'code.required' => '验证码不能为空',
            'password.required' => '密码不能为空',
            'pwdRepeat.required' => '第二遍密码不能为空',
        ];
        $validator = validator($input, $rules, $message);
        if($validator->passes()){
            if($input['password'] != $input['pwdRepeat']){
                return [
                    'status' => 0,
                    'msg' => '两遍密码不一样'
                ];
            }
            $oldpwd = DB::table('member')
                ->where('phone', $input['phone'])
                ->select('password')
                ->first();
            $code = DB::table('verification')
                ->select('code')
                ->where('phone', $input['phone'])
                ->first();
            if($code){
                if($code->code != $input['code']){
                    return [
                        'status' => 0,
                        'msg' => '验证码错误'
                    ];
                }else{
                    if(md5($input['password']) == $oldpwd->password){
                        return [
                            'status' => 1,
                            'msg' => '密码重置成功'
                        ];
                    }
                    //可以把密码入库了
                    $result = DB::table('member')
                        ->where('phone', $input['phone'])
                        ->update(['password' => md5($input['password'])]);
                    if($result){
                        return [
                            'status' => 1,
                            'msg' => '密码重置成功'
                        ];
                    }else{
                        return [
                            'status' => 0,
                            'msg' => '密码重置失败'
                        ];
                    }
                }
            }else{
                return [
                    'status' => 0,
                    'msg' => '验证码错误'
                ];
            }
        }else{
            return [
                'status' => 0,
                'msg' => $validator->errors()->first()
            ];
        }
    }

    public function sendcode()
    {
        $input = Input::all();
        if(isset($input['phone']) && !empty($input['phone'])){
            $result = send($input['phone']);
            if($result == 1){

                return [
                    'status' => 1,
                    'msg' => 'ok'
                ];
            }else{
                return [
                    'status' => 0,
                    'msg' => '网络异常，请稍后再试'
                ];
            }
        }else{
            return [
                'status' => 0,
                'msg' => '参数不能为空'
            ];
        }
    }


}
