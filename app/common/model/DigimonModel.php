<?php
namespace app\common\model;
use think\Model;
class DigimonModel extends Model{

    /**
     * @param string $generations
     * @return bool|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getGenerationsList($generations = ''){
        if(empty($generations)){
            return false;
        }
        $map = ['generations'=>$generations];
        $list = $this->where($map)->field('id, name, img')->select();
        return $list;
    }
}