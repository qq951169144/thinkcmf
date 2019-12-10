<?php
namespace app\admin\controller;
use cmf\controller\AdminBaseController;
use think\Db;
class DigimonController extends AdminBaseController{

    public $digimon;

    public function initialize()
    {
        // TODO: 以及列表, 以及编辑状态
        parent::initialize();
        $this->digimon = model('Digimon');
    }

    public function index(){
        $list = $this->digimon->order('generations')->select();
        $this->assign('list', $list);
        // var_dump($list);exit();
        return $this->fetch();
    }

    public function addview(){
        return $this->fetch();
    }

    public function add(){
        $data = input();

        $arr = ['evolution', 'degenerate'];

        foreach ($arr as $key=>$value){
            $data["{$value}_list"] = [];
            foreach ($data["{$value}_ids"] as $k=>$v){
                $name = $this->digimon->where(['id'=>$v])->value('name');
                $data["{$value}_list"][] = ['id'=>$v, 'name'=>$name];
            }
            $data["{$value}_ids"] = implode(",", $data["{$value}_ids"]);
            $data["{$value}_list"] = json_encode($data["{$value}_list"], JSON_UNESCAPED_UNICODE);
        }

        $res = $this->digimon->insert($data);
        // TODO: 这里不能只单纯针对当前数据进行操作，还要对涉及到进化和退化的数据进行操作
        if(!empty($res)){
            return $this->success('添加成功');
        }
    }

    function getList(){
        $generations = input('generations');

        $evolution_generations = $generations + 1;
        $degenerate_generations = $generations - 1;

        $evolution_str = $this->generations_list($evolution_generations);
        $degenerate_str = $this->generations_list($degenerate_generations);

        $str = ['evolution'=>$evolution_str, 'degenerate'=>$degenerate_str];

        return $this->success('获取成功', '', $str);
    }

    public function generations_list($generations){
        $list = $this->digimon->getGenerationsList($generations);
        $this->assign('list', $list);
        return $this->fetch('generations_list');
    }

}