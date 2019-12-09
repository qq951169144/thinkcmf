<?php
namespace app\admin\controller;
use cmf\controller\AdminBaseController;
use think\Db;
class DigimonController extends AdminBaseController{
    public function initialize()
    {
        // TODO: 明天完善退化，以及列表, 以及编辑状态
        parent::initialize();
    }

    public function index(){
        return $this->fetch();
    }

    public function addview(){
        // 新增
        return $this->fetch();
    }

    public function add(){
        $data = input();

        $data['evolution_list'] = [];
        foreach ($data['evolution_ids'] as $key=>$value){
            $name = model('Digimon')->where(['id'=>$value])->value('name');
            $data['evolution_list'][] = ['id'=>$value, 'name'=>$name];
        }
        $data['evolution_ids'] = implode(",", $data['evolution_ids']);
        $data['evolution_list'] = json_encode($data['evolution_list'], JSON_UNESCAPED_UNICODE);

        $res = model('Digimon')->insert($data);
        if(!empty($res)){
            return $this->success('添加成功');
        }
    }

    function getList(){
        $generations = input('generations');

        $evolution_generations = $generations + 1;
        $degenerate_generations = $generations - 1;

        $evolution_list = model('Digimon')->getGenerationsList($evolution_generations);
        $degenerate_list = model('Digimon')->getGenerationsList($degenerate_generations);

        $this->assign('list', $evolution_list);
        $evolution_str = $this->fetch('generations_list');

        return $this->success('获取成功', '', $evolution_str);
    }

}