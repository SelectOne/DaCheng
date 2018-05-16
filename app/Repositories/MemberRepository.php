<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/7
 * Time: 10:34
 */

namespace App\Repositories;

use  App\Repositories\Contracts\RepositoryInterface;
use  App\Repositories\Eloquent\Repository;


class MemberRepository extends Repository
{

    function model()
    {
        return 'App\Models\Member';
    }

    // 分页查询
    public function limit($arr)
    {
        $id = array_key_exists("id", $arr)?$arr['id']:"";
        $pid = array_key_exists("pid", $arr)?$arr['pid']:"";
        $nickname = array_key_exists("nickname", $arr)?$arr['nickname']:"";
        $time = array_key_exists("time", $arr)?$arr['time']:"";
        $ip = array_key_exists("ip", $arr)?$arr['ip']:"";
        $machine_ip = array_key_exists("machine_ip", $arr)?$arr['machine_ip']:"";

        $not = true;
        if($time){
            $tt = explode(' -- ',$time);
            $startime=strtotime("{$tt[0]} 00:00:01");
            $endtime=strtotime("{$tt[1]} 23:59:59");
            $tt = [$startime,$endtime];
            $not = false;
        }else{
            $tt = ['',''];
        }
        $offset = ( $arr['page']-1 ) * $arr['limit'];
        if (array_key_exists('field', $arr) && array_key_exists('order', $arr))
        {
            $field = $arr['field'];
            $type  = $arr['order'];
        } else {
            $field = 'id';
            $type  = 'desc';
        }

        $data = $this->model->where('nickname', 'like', "$nickname%")
                        ->whereBetween('login_time', $tt,'and',$not)
                        ->offset($offset)
                        ->limit($arr['limit'])
                        ->orderBy($field, $type);
        if (!empty($id)) {
            $data = $data->where('id', $id);
        }
        if (!empty($pid)) {
            $data = $data->where('pid', $pid);
        }
        if (!empty($ip)) {
            $data = $data->where('ip', $ip);
        }
        if (!empty($machine_ip)) {
            $data = $data->where('machine_ip', $machine_ip);
        }
        $data = $data->get();
        foreach ($data as $v) {
            /*if ($v['sex']) {
                $v['sex'] = "男";
            } else {
                $v['sex'] = "女";
            }

            if ($v['member_level'] == 1) {
                $v['member_level'] = "普通会员";
            } elseif ($v['member_level'] == 2) {
                $v['member_level'] = "中级会员";
            } elseif ($v['member_level'] == 3) {
                $v['member_level'] = "高级会员";
            }

            if ($v['manage_level'] == 1) {
                $v['manage_level'] = "普通会员";
            } elseif ($v['manage_level'] == 2) {
                $v['manage_level'] = "中级会员";
            } elseif ($v['manage_level'] == 3) {
                $v['manage_level'] = "高级会员";
            }

            $v['status'] = $v['status']?"已冻结":"正常";*/

            $v['login_time'] = date("Y-m-d H:i:s", $v['login_time']);

        }
        return $data;
    }

    // 获取所有记录总数
    public function getCount($arr)
    {
        $id = array_key_exists("id", $arr)?$arr['id']:"";
        $pid = array_key_exists("pid", $arr)?$arr['pid']:"";
        $nickname = array_key_exists("nickname", $arr)?$arr['nickname']:"";
        $time = array_key_exists("time", $arr)?$arr['time']:"";
        $ip = array_key_exists("ip", $arr)?$arr['ip']:"";
        $machine_ip = array_key_exists("machine_ip", $arr)?$arr['machine_ip']:"";
        $not = true;
        if($time){
            $tt = explode(' -- ',$time);
            $startime=strtotime("{$tt[0]} 00:00:01");
            $endtime=strtotime("{$tt[1]} 23:59:59");
            $tt = [$startime,$endtime];
            $not = false;
        }else{
            $tt = ['',''];
        }
        $count = $this->model->where('nickname', 'like', "$nickname%")
                        ->whereBetween('login_time', $tt,'and',$not);
        if (!empty($id)) {
            $count = $count->where('id', $id);
        }
        if (!empty($pid)) {
            $count = $count->where('pid', $pid);
        }
        if (!empty($ip)) {
            $count = $count->where('ip', $ip);
        }
        if (!empty($machine_ip)) {
            $count = $count->where('machine_ip', $machine_ip);
        }
        $count = $count->count();

        return $count;
    }

    public function recharge($id, $num)
    {
        $rs = $this->model()::where("id", $id)->increment('num', $num);
        return $rs;
    }

}