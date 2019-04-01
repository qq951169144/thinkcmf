<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2019 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\portal\controller;

use cmf\controller\HomeBaseController;
use think\Db;
class IndexController extends HomeBaseController
{
    public function index()
    {
        // 获取相册资源
        $join   = [
            ['__USER__ u', 'a.user_id = u.id'],
            ['__ALBUM__ al', 'a.id = al.asset_id', 'left']
        ];
        $result = Db::name('asset')->field('a.*,al.id as alid,u.user_login,u.user_email,u.user_nickname, al.status as al_status')
            ->alias('a')->join($join)
            ->order('id')
            ->paginate(8);
        $this->assign('assets', $result->items());
        $this->assign('page', $result->render());
        return $this->fetch(':index');
    }
}
