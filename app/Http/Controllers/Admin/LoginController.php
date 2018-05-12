<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\AdminRequest;
use Illuminate\Http\Request;
use DB;
use App\Models\Admin;
use App\Repositories\AdminRepository;
use Session;

class LoginController extends BaseController
{
    private $AdminRepository;

    public function  __construct(AdminRepository $AdminRepository){
        $this->AdminRepository = $AdminRepository;
    }

    public function index(Request $request){
           return view('admin.login');
    }

    //登入
    public function login(AdminRequest $request)
    {
        if($admin = $this->AdminRepository->checkLogin($request->input('name'),$request->input('password'))){
            Session::put('admin_id',$admin['admin_id']);
            Session::put('name',$admin['admin_name']);
            $ip = $_SERVER["REMOTE_ADDR"];
//            plog($admin['name'].'登入成功 登入IP:');
            return redirect("admin");
        }else{
            return back()->withErrors('用户名或密码不正确!');
       }
    }

    public function outLogin(Request $request){
//        plog($request->session()->get('name').'退出登入');
//        Admin::outLogin($request->session()->get('admin_id'));
        $request->session()->flush();
        return redirect('admin/login');
    }
}
