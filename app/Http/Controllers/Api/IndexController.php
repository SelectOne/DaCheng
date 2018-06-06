<?php

namespace App\Http\Controllers\API;

use App\Models\GameSet;
use App\Models\Member;
use App\Models\Sign;
use App\Models\Checkreward;
use Illuminate\Http\Request;
use Route;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;


class IndexController extends Controller
{



    public function cleanr()
    {
       Redis::select(6);
       $a = Redis::flushdb();
       echo 'ok';

    }

    /**
     * 登入
     */
    public function login()
    {

        $i = Input::all();
        $ip = $_SERVER["REMOTE_ADDR"];
        if(!isset($i['machine'])){
            return json_encode(['status' => 0, 'msg' => '缺少参数']);
        }
        $machine_ip = $i['machine'];

        if(isset($i['name'])){
            if (!isset($i['name']) || !isset($i['password']) || !isset($i['version'])) {
                return json_encode(['status' => 0, 'msg' => '缺少参数']);
            }
            $member = DB::table('member')
                ->where('name', $i['name'])
                ->where('password',md5(md5($i['password'])))
                ->first();
            $token = md5(time());
            if ($member) {
                if ($member->status == 1) {
                    return json_encode(['status' => 0, 'msg' => '当前用户被拉黑，请联系管理员!']);
                }
                $check = $this->login_check($member->id);
                if($check){
                    return json_encode(['status' => 0, 'msg' => '账号被限制登录!']);
                }
                $num = $this->login_loading($member->id,$token);
                if(!$num){
                    $num = $member->num;
                }

                //$headimgurl = getImageFromWX($i['headimgurl'], $i['openid']);
                DB::table('member')
                    ->where('id', $member->id)
                    ->update([
                        'token' => $token,
                        'login_time' => date('Y-m-d H:i:s',time()),
                        'ip' => $ip,
                        'machine_ip'=>$machine_ip,
                        'version' => $i['version']
                    ]);
                $data = [
                    'mid' => $member->id,
                    'name'=>$member->name,
                    'num'=>$num,
                    'token' => $token,
                    'nickname'=>$member->nickname,
                    'phone'=>$member->phone,
                    'sex'=>$member->sex,
                    'headimgurl'=>$member->headimgurl
                ];
                return json_encode(['status' => 1, 'data' => $data]);
            }else{
                return json_encode(['status' => 0, 'msg' => '账号或密码错误!']);
            }
        }elseif(isset($i['phone'])){
            if (!isset($i['phone']) || !isset($i['password']) || !isset($i['version'])) {
                return ['status' => 0, 'msg' => '缺少参数'];
            }
            //$ip = $_SERVER["REMOTE_ADDR"];
            $token = md5(time());
            $member = DB::table('member')
                ->where('phone', $i['phone'])
                ->first();
            if($member){
                if($member->password == md5($i['password'])){
                    if ($member->status == 1) {
                        return ['status' => 0, 'msg' => '当前用户被拉黑，请联系管理员!'];
                    }
                    $check = $this->login_check($member->id);
                    if($check){
                        return json_encode(['status' => 0, 'msg' => '账号被限制登录!']);
                    }
                    $num = $this->login_loading($member->id,$token);
                    if(!$num){
                        $num = $member->num;
                    }
                    DB::table('member')
                        ->where('id', $member->id)
                        ->update([
                            'token' => $token,
                            'login_time' => date('Y-m-d H:i:s',time()),
                            'ip' => $ip,
                            'machine_ip'=>$machine_ip,
                            'version' => $i['version'],
                        ]);
                    return [
                        'status' => 1,
                        'data' => [
                            'mid' => $member->id,
                            'name'=>$member->name,
                            'num'=>$num,
                            'token' => $token,
                            'nickname'=>$member->nickname,
                            'phone'=>$member->phone,
                            'sex'=>$member->sex,
                            'headimgurl'=>$member->headimgurl
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
        }
        if (!isset($i['openid'])  || !isset($i['nickname']) || !isset($i['version'])) {
            return json_encode(['status' => 0, 'msg' => '缺少参数']);
        }
        //$ip = $_SERVER["REMOTE_ADDR"];
        $member = DB::table('member')
            ->where('openid', $i['openid'])
            ->first();
        $token = md5(time());
        if ($member) {
            if ($member->status == 1) {
                return json_encode(['status' => 0, 'msg' => '当前用户被拉黑，请联系管理员!']);
            }
            $check = $this->login_check($member->id);
            if($check){
                return json_encode(['status' => 0, 'msg' => '账号被限制登录!']);
            }
            $num = $this->login_loading($member->id,$token);
            if(!$num){
                $num = $member->num;
            }

            DB::table('member')
                ->where('id', $member->id)
                ->update([
                    'token' => $token,
                    'login_time' => date('Y-m-d H:i:s',time()),
                    'ip' => $ip,
                    'machine_ip'=>$machine_ip,
                    'version' => $i['version']
                ]);
            $data = [
                'mid' => $member->id,
                'num' => $num,
                'token' => $token,
                'headimgurl'=>$member->headimgurl,
                'sex'=>$member->sex,
                'phone'=>$member->phone,
                'nickname'=>$member->nickname
            ];
            return json_encode(['status' => 1, 'data' => $data]);
        } else {
            $rele = $this->create_check($ip,$machine_ip);
            if($rele){
                return json_encode(['status' => 0, 'msg' => '手机或IP被限制注册!']);
            }
            $num = DB::table('settings')->where('id',1)->value('param2');
            $headimgurl = $i['headimgurl'];
            if (!$headimgurl) {
                $headimgurl = 'http://' . $_SERVER['SERVER_NAME'] . '/users/0.png';
            } else {
                //保存微信头像
                $name = md5($i['openid']);
                $re = getImage($headimgurl, 'users', "$name.jpg", 1);
                if ($re['error'] == 0) {
                    $headimgurl = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $re['save_path'];
                } else {
                    $headimgurl = 'http://' . $_SERVER['SERVER_NAME'] . '/users/0.png';
                }
            }
            $mid = DB::table('member')
                ->insertGetId([
                    'openid' => $i['openid'],
                    'nickname' => $i['nickname'],
                    'headimgurl' => $headimgurl,
                    'ip' => $ip,
                    'machine_ip'=>$machine_ip,
                    'login_time' => date('Y-m-d H:i:s',time()),
                    'created_at' => date('Y-m-d H:i:s',time()),
                    'version' => $i['version'],
                    'sex' => $i['sex'],
                    'token' => $token,
                    'num' => $num,                               //用户注册初始奖励金币
                ]);
            DB::table('member_ip')->insert([
                    'ip' => $ip,
                    'machine_ip'=>$machine_ip,
                    'mid'=>$mid,
                    'created_at'=>date('Y-m-d H:i:s',time())
                ]);
            if ($mid) {
                $data = [
                    'mid' => $mid,
                    'num' => $num,
                    'token' => $token,
                    'sex' => $i['sex'],
                    'headimgurl'=>$headimgurl,
                    'nickname'=>$member->nickname,
                    'phone'=>0
                ];
                return json_encode(['status' => 1, 'data' => $data]);
            } else {
                return json_encode(['status' => 0, 'msg' => '请稍后重试!']);
            }
        }
    }




    public function login_create(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'password' => 'required',
            'nickname' => 'required',
            'machine' => 'required'
        ],[
            'name.required'=>'请填写用户名！',
             'password.required'=>'请填写密码！',
             'nickname.required'=>'请填写昵称！',
             'machine.required'=>'缺少机器码！'
        ]);
        $ip = $_SERVER["REMOTE_ADDR"];
        $name = $request->input('name');
        $password = $request->input('password');
        $nickname = $request->input('nickname');
        $machine_ip = $request->input('machine');

        $rele = $this->create_check($ip,$machine_ip);
        if($rele){
            return json_encode(['status' => 0, 'msg' => '手机或IP被限制注册!']);
        }

        $re1 = DB::table('member')->where('name',$name)->first();
        $re2 = DB::table('member')->where('nickname',$nickname)->first();
        if(!empty($re1)){
            return json_encode(['status' => 0, 'msg' => '用户名已存在!']);
        }
        if(!empty($re2)){
            return json_encode(['status' => 0, 'msg' => '昵称已存在!']);
        }
        $password = md5(md5($password));
        $ip = $_SERVER["REMOTE_ADDR"];
        $token = md5(time());
        $a = rand(0,6);
        $headimgurl = 'http://'.$_SERVER['SERVER_NAME'].'/users/'. $a . '.png';
        $num = DB::table('settings')->where('id',1)->value('param2');
        $mid = DB::table('member')
                ->insertGetId([
                    'name' => $name,
                    'password' => $password,
                    'openid' => 0,
                    'nickname' => $nickname,
                    'headimgurl' => $headimgurl,
                    'ip' => $ip,
                    'machine_ip'=>$machine_ip,
                    'login_time' => date('Y-m-d H:i:s',time()),
                    'created_at' => date('Y-m-d H:i:s',time()),
                    'version' => 1,
                    'sex' => 1,
                    'num'=>$num,
                    'token' => $token,
                    'status' => 0
                ]);
            DB::table('member_ip')->insert([
                    'ip' => $ip,
                    'machine_ip'=>$machine_ip,
                    'mid'=>$mid,
                    'created_at'=>date('Y-m-d H:i:s',time())
                ]);
            if ($mid) {
                $data = [
                    'name' => $name,
                    'mid' => $mid,
                    'token' => $token,
                    'nickname'=>$nickname,
                    'phone'=>0,
                    'sex'=>1,
                    'num'=>$num,
                    'headimgurl'=>$headimgurl

                ];
                return json_encode(['status' => 1, 'data' => $data]);
            }
    }




    public  function goods(){
        $data = DB::table('goods')
            ->select('num','money','id')
            ->where('is_show',0)
            ->orderBy('sort','desc')
            //->orderBy('id','desc')
            ->get();
        return json_encode(['status'=>1,'data'=>$data]);
    }

    public  function update_head()
    {
        $i = Input::all();
        $img = $i['img'];
        $mid = $i['mid'];
        $len = strlen($img);
        $len = ($len-($len/4));
        if($len > 500*1024){
            return ['status'=>0,'msg'=>'图片过大,上传失败!'];
        }
        $base64 = str_replace(' ', "+", $img);
       if(empty($base64)){
            return ['status'=>0,'msg'=>'请上传图片'];
       }
        $img = base64_decode($base64);
        $jpg = md5(time()).mt_rand(1000,9999).".jpg";
        $a = file_put_contents("./images/$jpg", $img);//返回的是字节数
        if($a){
            $url = 'http://dianwan.tumujinhua.com/images/'.$jpg;
            DB::table('member')->where('id',$mid)->update(['headimgurl'=>$url]);

            return ['status'=>1,'data'=>['msg'=>'上传成功','url'=>$url]];
        }else{
            return ['status'=>0,'msg'=>'上传失败'];
        }
    }

    public  function update_nickname()
    {
        $i = Input::all();
        $nickname = $i['nickname'];
        $mid = $i['mid'];
        if(!$nickname || !$mid){
            return ['status'=>0,'msg'=>'缺少参数!'];
        }
        $member = DB::table('member')->where('nickname',$nickname)->first();
        if($member){
            return ['status'=>0,'msg'=>'昵称已存在!'];
        }
        DB::table('member')->where('id',$mid)->update(['nickname'=>$nickname]);
        return ['status'=>1,'data'=>['msg'=>'修改成功!','nickname'=>$nickname]];

    }


    /**
     * 进入大厅
     */
    public function dating()
    {
        $mid = Input::get('mid');
        $member = DB::table('member')
            ->where('id', $mid)
            ->select('ip', 'pid', 'phone', 'num', 'bx_password','nickname','headimgurl')
            ->first();

        if ($member) {
            return json_encode(['status' => 1, 'data' => ['member' => $member]]);
        } else {
            return json_encode(['status' => 0, 'msg' => '无法获取该用户信息！']);
        }
    }



    public function get_qiandao(){
        $mid = Input::get('mid');
        $a = DB::table('member')->where('id',$mid)->first();
        if(!$a){
            return json_encode(['status'=>0,'msg'=>'用户不存在!']);
        }
        $jiangli = DB::table('checkin_reward')->get();
        $data = [
            'status'=>1,
            'data'=>[
                'type'=>1, // 0:已签到  1:未签到
                'ci'=>0,
                'jiangli'=>$jiangli
            ]
        ];
        $t = $a->qd_time;
        $ci = $a->qd_ci;
        $c = $ci;
        $t1 = date('Ymd',$t);
        $t2 = date('Ymd',$t+86400);
        $t0 = date('Ymd',time());
        if($t0 == $t1){
            $data['data']['type'] = 0;
        }
        if($data['data']['type'] == 0){
            $c = $ci;
        }else{
            if($t0 == $t2){
                $c = $ci+1;
                if($c>7){
                    $c = 1;
                }
            }else{
                $c = 1;
            }
        }
        $data['data']['ci'] = $c;
        return json_encode($data);
    }

    public function qiandao(){
        $mid = Input::get('mid');
        $a = DB::table('member')->where('id',$mid)->first();
        if(!$a){
            return json_encode(['status'=>0,'msg'=>'用户不存在!']);
        }
        $data = [
            'status'=>1,
            'data'=>[
                'type'=>1, // 0:已签到  1:未签到
                'ci'=>0,
            ]
        ];
        $t = $a->qd_time;
        $ci = $a->qd_ci;
        $c = $ci;
        $t1 = date('Ymd',$t);
        $t2 = date('Ymd',$t+86400);
        $t0 = date('Ymd',time());
        if($t0 == $t1){
            return json_encode(['status'=>0,'msg'=>'今日已签过!']);
        }
        if($t0 == $t2){
            $c = $ci+1;
            if($c>7){
                $c = 1;
            }
        }else{
            $c = 1;
        }
        $jiang = DB::table('checkin_reward')->where('days',$c)->value('reward');
        DB::beginTransaction();
        $re = DB::table('member')->where('id',$mid)->increment('num',$jiang);
        if($re){
            $rel = DB::table('member')->where('id',$mid)->update(['qd_time'=>time(),'qd_ci'=>$c]);
            DB::commit();
            $data = [
                'status'=>1,
                'data'=>[
                    'date'=>$c,
                    'money'=>$jiang
                ]
            ];
            return json_encode($data);
        }else{
            DB::rollBack();
            return json_encode(['status'=>0,'msg'=>'稍后再试']);
        }
    }


    public  function fankui(){
        $i = Input::all();
        //表单验证
        $rules = array(                                     //定义验证规则
            'phone' => 'required|digits:11',
            'content'=>'required',
            'mid'=>'required'
        );
        $message = array(                                   //定义错误提示信息
            'phone.required' => '手机号不能为空',
            'phone.digits' =>  '手机号格式不正确',
            'content.required' => '评论内容不能为空',
            'mid.required' => 'mid不能为空'
        );
        $validator = validator($i,$rules,$message);          //传递参数,进行验证
        if($validator->passes()) {
            $re = DB::table('feedback')
                ->insert([
                    'phone'=> $i['phone'],
                    'content'=>$i['content'],
                    'mid'=>$i['mid']
                ]);
            if($re){
                return json_encode(['status'=>1,'msg'=>'感谢您的反馈,我们会尽快解决您反馈的问题！']);
            }else{
                return json_encode(['status'=>0,'msg'=>'请稍后重试']);
            }
        }else{
            return json_encode(['status'=>0,'msg'=>$validator->errors()->first()]);
        }

    }


    /**
     * 排行榜
     */
    public  function  paihang(){
        $data =  DB::table('member')
                ->select('id','nickname','headimgurl','num')
                ->orderBy('num','desc')
                ->take(10)
                ->get();
        return json_encode(['status'=>1,'data'=>$data]);
    }
   /**
    * 保险箱
    */
    public function baoxianxiang() {
        $mid = Input::get('mid');
        $member = DB::table('member')
                ->where('id',$mid)
                ->first();
        if ($member){
            $data = [
               'xianjin'=>$member->num,
               'cunkuan'=>$member->num1
            ];
            return json_encode(['status'=>1,'data'=>$data]);
        } else {
            return json_encode(['status'=>0,'msg'=>'用户参数不合法']);
        }
    }
    /*
     * 存入
     */
    public function cunru(){
        $mid = Input::get('mid');
        //$password = Input::get('bx_password');
        $money =  Input::get('num');
        $member = DB::table('member')
                ->where('id',$mid)
                ->first();
        if ($member) {
            //if ($member->bx_password && md5($password)==$member->bx_password){
                $num = $member->num -$money;
                $num1 = $member->num1 +$money;
                if ($num <0){
                    return json_encode(['status'=>0,'msg'=>'金币不足']);
                }
                $re = DB::table('member')
                    ->where('id',$mid)
                    ->update([
                        'num'=>$num,
                        'num1'=>$num1
                    ]);
                $data = [
                    'xianjian'=>$num,
                    'cunkuan'=>$num1,
                ];
                if ($re) {
                    return json_encode(['status'=>1,'data'=>$data]);
                } else{
                    return json_encode(['status'=>0,'msg'=>'系统出错']);
                }
            /*} else {
                return json_encode(['status'=>0,'msg'=>'保险箱密码不正确']);
            }*/
        } else {
            return json_encode(['status'=>0,'msg'=>'用户参数不合法']);
        }
    }
    /*
    * 转出
    */
    public function zhuanchu(){
        $mid = Input::get('mid');
        $password = Input::get('password');
        $money =  Input::get('num');
        $member = DB::table('member')
            ->where('id',$mid)
            ->first();
        if ($member) {
            if ($member->bx_password && md5($password)==$member->bx_password){
                $num = $member->num +$money;
                $num1 = $member->num1-$money;
                if ($num1<0){
                    return json_encode(['status'=>0,'msg'=>'金币不足']);
                }
                $re = DB::table('member')
                    ->where('id',$mid)
                    ->update([
                        'num'=>$num,
                        'num1'=>$num1
                    ]);
                $data = [
                   'xianjian'=>$num,
                    'cunkuan'=>$num1
                ];
                if ($re) {
                    return json_encode(['status'=>1,'data'=>$data]);
                } else{
                    return json_encode(['status'=>0,'msg'=>'系统出错']);
                }
            } else {
                return json_encode(['status'=>0,'msg'=>'保险箱密码不正确']);
            }
        } else {
            return json_encode(['status'=>0,'msg'=>'用户参数不合法']);
        }
    }
    /*
     * 保险箱设置密码
     *
     */
    public  function bx_password()
    {
        $mid = Input::get('mid');
        $bx_password = Input::get('bx_password');
        $bx_password = md5($bx_password);
        $member = DB::table('member')
                ->where('id',$mid)
                ->first();
        if ($member){
            $re = DB::table('member')
                 ->where('id',$mid)
                 ->update(['bx_password'=>$bx_password]);
            if ($re) {
                return json_encode(['status'=>1,'msg'=>'密码设置成功']);
            } else {
                return json_encode(['status'=>0,'msg'=>'密码设置失败']);
            }

        } else {
            return json_encode(['status'=>0,'msg'=>'用户参数不合法']);
        }
    }

    /**
     *修改交易密码
     */
    public function xg_password(){
        $mid = Input::get('mid');
        $old_password = Input::get('old_password');
        $new_password = Input::get('new_password');
        $member = DB::table('member')
            ->where('id',$mid)
            ->first();
        if($member){
            if(md5($old_password) == $member->bx_password){
                $re = DB::table('member')
                    ->where('id',$mid)
                    ->update(['bx_password'=>md5($new_password)]);
                if($re){
                    return json_encode(['status'=>1,'msg'=>'交易密码修改成功']);
                }else{
                    return json_encode(['status'=>0,'msg'=>'请稍后再试']);
                }
            }else{
                return json_encode(['status'=>0,'msg'=>'原交易密码不正确']);
            }
        }else {
            return json_encode(['status'=>0,'msg'=>'查询不到该用户']);
        }

    }

    public  function bindPhone(){
        $i = Input::all();
        //表单验证
        $rules = array(                                     //定义验证规则
            'phone' => 'required|digits:11',
            'mid'=>'required',
            'code'=>'required'
        );
        $message = array(                                   //定义错误提示信息
            'phone.required' => '手机号不能为空',
            'phone.digits' =>   '手机号格式不正确',
            'mid.required' =>   '用户id不能为空',
            'code.required' =>  '验证码不能为空'
        );
        $validator = validator($i,$rules,$message);          //传递参数,进行验证
        if($validator->passes()) {
            $re = DB::table('verification')->where(['phone'=>$i['phone'],'code'=>$i['code']])->first();
            if(!$re){
                return json_encode(['status'=>0,'msg'=>'验证码不正确']);
            }
            $ree = DB::table('member')
                ->where('id',$i['mid'])
                ->update([
                    'phone'=>$i['phone']
                ]);
            if($ree){
                /*$set = DB::table('settings')->where('id',1)->value('syst');
                $set = unserialize($set);
                $num = $set['param5'];*/
                //DB::table('member')->where('id',$i['mid'])->increment('num',$num);
                //DB::table('mnum_info')->insert(['mid'=>$i['mid'],'num'=>$num,'title'=>'绑定奖励','type'=>1]);
                return json_encode(['status'=>1,'msg'=>'手机号绑定成功']);
            }else{
                return json_encode(['status'=>0,'msg'=>'请稍后重试']);
            }

        }else{
            return json_encode(['status'=>0,'msg'=>$validator->errors()->first()]);
        }

    }


    public function login_check($mid){
        $member = DB::table('member_ip')->where('mid',$mid)->first();
        if($member){
            $ip = $member->ip;
            $machine_ip = $member->machine_ip;
            $re1 = DB::table('restrict')->where(['ip'=>$ip,'type'=>0])->first();
            if($re1){
                if($re1->limit_login){
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
                if($re2->limit_login){
                    if($re2->limit_time){
                        if(time() < $re2->limit_time){
                            return true;
                        }
                    }else{
                        return true;
                    }
                }
            }
        }
        return false;
    }

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




    public function login_loading($mid,$token){
        Redis::select(1);
        Redis::set('zaixian-'.$mid,$token);
        Redis::select(2);
        Redis::set('zaixian-'.$mid,$token);
        Redis::select(3);
        Redis::set('zaixian-'.$mid,$token);
        Redis::select(4);
        Redis::set('zaixian-'.$mid,$token);
        Redis::select(5);
        Redis::set('zaixian-'.$mid,$token);
        Redis::select(6);
        Redis::set('zaixian-'.$mid,$token);
        $num = 0;
        Redis::select(1);
        if(Redis::exists('sg-'.$mid)){
            $num = $this->chuli_sg($mid);
        }
        Redis::select(2);
        if(Redis::hexists('fishm-'.$mid,'info')){
            $num = $this->chuli_fish($mid);
        }
        Redis::select(3);
        if(Redis::exists('nn-'.$mid)){
            $num = $this->chuli_nn($mid);
        }
        Redis::select(4);
        if(Redis::exists('shz_'.$mid)){
            $num = $this->chuli_shz($mid);
        }
        Redis::select(5);
        if(Redis::exists('xm_'.$mid)){
            $num = $this->chuli_xm($mid);
        }
        Redis::select(6);
        if(Redis::exists('jbnnm-'.$mid)){
            $num = $this->chuli_jbnn($mid);
        }


    }

    public function chuli_jbnn($uid){
        Redis::select(6);
        $info = Redis::hget('jbnnm-'.$uid,'info');
        $info = unserialize($info);
        $num = $info['num'];
        $gid = $info['gid'];
        if($gid){
            $y = $num - $info['num0'];
            DB::table('member')
                ->where('id', $uid)
                ->update(['num' => $num,'room_id'=>0]);
            $arr = [
                'mid'=>$uid,
                'type'=>1,
                'start_coin'=>$info['num0'],
                'change_coin'=>$y,
                'end_coin'=>$num,
                'created_at'=>date('Y-m-d H:i:s',time()),
                'room_id'=>$gid
            ];
            DB::table('coin_change')->insert($arr);
        }
        $room_id = $info['room_id'];
        Redis::select(6);
        $roomInfo = Redis::hget('jbnn-'.$room_id,'roomInfo');
        $roomInfo = unserialize($roomInfo);
        Redis::select(6);
        $userInfo = Redis::hget('jbnn-'.$room_id,'userInfo');
        $userInfo = unserialize($userInfo);
        if($roomInfo['status'] == 0){
            unset($userInfo[$uid]);
            unset($roomInfo['zf'][$uid]);
            $k = array_search($uid,$roomInfo['users']);
            unset($roomInfo['users'][$k]);
            Redis::select(6);
            Redis::hset('jbnn-'.$room_id,'roomInfo',serialize($roomInfo));
            Redis::select(6);
            Redis::hset('jbnn-'.$room_id,'userInfo',serialize($userInfo));
        }
        Redis::select(6);
        Redis::srem('jbnn-'.$room_id.'-members',$uid);
        Redis::select(6);
        Redis::del('jbnnm-'.$uid);
        return $num;
    }



    public function chuli_shz($uid){
        Redis::select(4);
        $info = Redis::hget('shz_'.$uid,'info');
        $info = unserialize($info);
        $num = $info['num'];
        $level = $info['level'];
        if($level){
            $y = $num - $info['num0'];
            DB::table('member')
                ->where('id', $uid)
                ->update(['num' => $num,'room_id'=>0]);
            $arr = [
                'mid'=>$uid,
                'type'=>1,
                'start_coin'=>$info['num0'],
                'change_coin'=>$y,
                'end_coin'=>$num,
                'created_at'=>date('Y-m-d H:i:s',time()),
                'room_id'=>$info['room_id']
            ];
            DB::table('coin_change')->insert($arr);
        }
        Redis::select(4);
        Redis::del('shz_'.$uid);
        return $num;
    }

    public function chuli_xm($uid){
        Redis::select(5);
        $info = Redis::hget('xm_'.$uid,'info');
        $info = unserialize($info);
        $num = $info['num'];
        $level = $info['level'];
        if($info){
            if($level){
                $y = $num - $info['num0'];
                DB::table('member')
                    ->where('id', $uid)
                    ->update(['num' => $num,'room_id'=>0]);
                $arr = [
                    'mid'=>$uid,
                    'type'=>1,
                    'start_coin'=>$info['num0'],
                    'change_coin'=>$y,
                    'end_coin'=>$num,
                    'created_at'=>date('Y-m-d H:i:s',time()),
                    'room_id'=>$info['room_id']
                ];
                DB::table('coin_change')->insert($arr);
            }
        }
        Redis::select(5);
        Redis::del('xm_'.$uid);
        return $num;
    }

    public function chuli_fish($uid){
            $num = DB::table('member')->where('id',$uid)->value('num');
            Redis::select(2);
            $info = Redis::hget('fishm-'.$uid, 'info');
            $info = unserialize($info);
            if(isset($info['room_id'])){
                $room_id = $info['room_id'];
                Redis::select(2);
                $userInfo = Redis::hget('fang_'.$room_id,'userInfo');
                $userInfo = unserialize($userInfo);
                Redis::select(2);
                $users = Redis::hget('fang_'.$room_id,'users');
                $users = unserialize($users);

                $rate = 100;
                if($info['level'] == 3){
                    $rate = 10;
                }
                if($info['level'] == 4){
                    $rate = 1;
                }

                    $num = floor($info['fraction']/$rate);
                    DB::table('member')->where('id',$uid)->update(['num'=>$num,'room_id'=>0]);
                    $y = $num - $info['num0'];
                    $arr = [
                        'mid'=>$uid,
                        'type'=>1,
                        'start_coin'=>$info['num0'],
                        'change_coin'=>$y,
                        'end_coin'=>$num,
                        'created_at'=>date('Y-m-d H:i:s',time()),
                        'room_id'=>$info['room_id0']
                    ];
                    DB::table('coin_change')->insert($arr);

                $user = Redis::smembers($room_id.'-m');
                unset($userInfo[$uid]);
                $key = array_keys($users,$uid);
                if($key){
                    foreach ($key as $v) {
                        unset($users[$v]);
                    }
                }
                //unset($users[$k]);
                Redis::select(2);
                Redis::hset('fang_'.$room_id,'userInfo',serialize($userInfo));
                Redis::hset('fang_'.$room_id,'users',serialize($users));
                Redis::del('fishm-'.$uid);
                Redis::sRem($room_id.'-m', $uid);
                Redis::sRem($room_id.'-z', $uid);
                $renshu = Redis::sCard($room_id.'-m');
                if(!$renshu){
                    Redis::select(2);
                    Redis::del($room_id . '-fishesidset');
                    Redis::set($room_id . '-fishesid', 0);
                    Redis::del('fish-'.$room_id.'-begin');
                    Redis::del('fish-'.$room_id.'-beginj');
                    Redis::del($room_id.'-yuchao');
                }
            }
            return $num;
    }

    public function chuli_nn($uid){
        Redis::select(3);
            $num = Redis::hget('nn-'.$uid,'num');
            $level = Redis::hget('nn-'.$uid,'level');
            $info = Redis::hget('nn-'.$uid,'info');
            $info = unserialize($info);
            $zhuang = unserialize(Redis::hget('nn-level-'.$level,'zhuang'));
            if($zhuang['mid'] == $uid){
                $zhuang['mid'] = 0;
                $zhuang['num'] = 100000000;
                $zhuang['nickname'] = '官方';
                $zhuang['ci'] = 0;
                $zhuang['headimgurl'] = 'http://dianwan.tumujinhua.com/users/z.png';
                Redis::hset('nn-level-'.$level,'zhuang',serialize($zhuang));
            }

            $shangzhuang = unserialize(Redis::hget('nn-level-'.$level,'shangzhuang'));
            if($shangzhuang){
                if(array_key_exists($uid, $shangzhuang)){
                    unset($shangzhuang[$uid]);
                    Redis::hset('nn-level-'.$level,'shangzhuang',serialize($shangzhuang));
                }
            }

            if($level){
                DB::table('member')
                    ->where('id', $uid)
                    ->update(['num' => $num,'room_id'=>0]);
                $y = $num - $info['num0'];
                $arr = [
                    'mid'=>$uid,
                    'type'=>1,
                    'start_coin'=>$info['num0'],
                    'change_coin'=>$y,
                    'end_coin'=>$num,
                    'created_at'=>date('Y-m-d H:i:s',time()),
                    'room_id'=>$info['room_id']
                ];
                DB::table('coin_change')->insert($arr);
            }
            Redis::del('nn-'.$uid);
            Redis::srem('nn-level-' . $level . '-r',$uid);
            Redis::srem('nn-level-' . $level . '-z',$uid);
            return $num;
    }

}
