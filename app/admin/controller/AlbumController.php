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
class AlbumController extends AdminBaseController {

    public function index(){
        return $this->fetch();
    }

    public function add(){
        return $this->fetch();
    }
}