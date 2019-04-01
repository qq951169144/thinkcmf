<?php
/**
 * Created by PhpStorm.
 * User: 山岚烟雨
 * Date: 2019/3/28
 * Time: 10:49
 */
namespace app\admin\controller;
use cmf\controller\AdminBaseController;
use think\Db;
use app\admin\model\AdminMenuModel;
use think\Exception;

class AlbumController extends AdminBaseController {

    public function index(){
        $content = hook_one('user_admin_asset_index_view');

        if (!empty($content)) {
            return $content;
        }

        $join   = [
            ['__USER__ u', 'a.user_id = u.id'],
            ['__ALBUM__ al', 'a.id = al.asset_id', 'left']
        ];
        $result = Db::name('asset')->field('a.*,al.id as alid,u.user_login,u.user_email,u.user_nickname, al.status as al_status')
            ->alias('a')->join($join)
            ->order('id')
            ->paginate(10);
        $this->assign('assets', $result->items());
        $this->assign('page', $result->render());
        return $this->fetch();
    }

    public function doadd(){
        $params = input();
        $i = 0;
        foreach ($params['ids'] as $key=>$value){
            // 先验证唯一性
            $is_has = db('Album')->where(['asset_id'=>$value, 'status'=>1])->count();
            if(empty($is_has)){
                $data = [
                    'asset_id'=>$value,
                    'create_time'=>time(),
                    'update_time'=>time()
                ];
                $res = db('Album')->insert($data);
                if(!empty($res)){
                    $i++;
                }
            }
        }
        if($i == 0){
            $this->error('添加失败');
        }else{
            $this->success("成功添加{$i}条记录");
        }
    }

    public function dodel(){
        // 换成硬删除
        $params = input();
        $ids = '';
        foreach ($params['ids'] as $key=>$value){
            if($key == 0){
                $ids = $value;
            }else{
                $ids.=",".$value;
            }
        }
        $map = ['asset_id'=> ['in', $ids], 'status'=>1];
        $res = db('Album')->where($map)->delete();
        if(!empty($res)){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
}