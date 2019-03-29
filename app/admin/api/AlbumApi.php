<?php
/**
 * Created by PhpStorm.
 * User: 山岚烟雨
 * Date: 2019/3/29
 * Time: 10:30
 */
namespace app\admin\api;
use think\db\Query;

class AlbumApi{
    /**
     * @return bool
     */
    public function index(){
        $join = [
            ['__ASSET__ as', 'as.id = al.asset_id']
        ];
        $map = [
            'al.status'=>1,
            'as.status'=>1
        ];
        $field = "as.id ,as.filename as name, as.file_path";
        $data = db('Album')->alias('al')->join($join)->where($map)->field($field)->select();
        if(!empty($data)){
            return $data;
        }else{
            return false;
        }
    }
}