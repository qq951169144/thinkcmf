<?php
namespace app\admin\controller;
use cmf\controller\AdminBaseController;
use think\Db;
class DigimonController extends AdminBaseController{

    public $digimon;

    public function initialize()
    {
        // TODO: 以及编辑状态
        parent::initialize();
        $this->digimon = model('Digimon');
    }

    public function index(){
        $list = $this->digimon->order('generations')->select();
        $this->assign('list', $list);
        return $this->fetch();
    }

    public function index_list(){
        $generations = input('generations');
        if(!empty($generations)){
            $map = ['generations'=>$generations];
            $list = $this->digimon->where($map)->order('generations')->select();
        }else{
            $list = $this->digimon->order('generations')->select();
        }

        $this->assign('list', $list);
        $str = $this->fetch('index_list');
        return $this->success('获取成功', '', $str);
    }

    public function addview(){
        return $this->fetch();
    }

    public function add(){
        $data = input();

        $arr = ['evolution', 'degenerate'];

        foreach ($arr as $key=>$value){
            if(!empty($data["{$value}_ids"])){
                $data["{$value}_list"] = [];
                foreach ($data["{$value}_ids"] as $k=>$v){
                    $name = $this->digimon->where(['id'=>$v])->value('name');
                    $data["{$value}_list"][] = ['id'=>$v, 'name'=>$name];
                }
                $data["{$value}_ids"] = implode(",", $data["{$value}_ids"]);
                $data["{$value}_list"] = json_encode($data["{$value}_list"], JSON_UNESCAPED_UNICODE);
            }
        }
        $res = model('Digimon')->insertGetId($data);
        // 进化变退化
        if(!empty($data['evolution_ids'])){
            $data['evolution_list'] = json_decode($data['evolution_list'], true);
            foreach ($data['evolution_list'] as $key=>$value){
                $degenerate = model('Digimon')->where(['id'=>$value['id']])->field('degenerate_list, degenerate_ids')->find()->toArray();

                $degenerate['degenerate_list'] = json_decode($degenerate['degenerate_list'], true);
                $degenerate['degenerate_list'][] = ['id'=>$res, 'name'=>$data['name']];
                $degenerate['degenerate_list'] = json_encode($degenerate['degenerate_list'],JSON_UNESCAPED_UNICODE);

                if(!empty($degenerate['degenerate_ids'])){
                    $degenerate['degenerate_ids'] = explode(",", $degenerate['degenerate_ids']);
                }
                $degenerate['degenerate_ids'][] = $res;
                $degenerate['degenerate_ids'] = implode(",", $degenerate['degenerate_ids']);

                model('Digimon')->save($degenerate, ['id'=>$value['id']]);
            }
        }

        // 退化变进化
        if(!empty($data['degenerate_ids'])){
            $data['degenerate_list'] = json_decode($data['degenerate_list'], true);
            foreach ($data['degenerate_list'] as $key=>$value){

                $evolution = model('Digimon')->where(['id'=>$value['id']])->field('evolution_ids, evolution_list')->find()->toArray();

                $evolution['evolution_list'] = json_decode($evolution['evolution_list'], true);
                $evolution['evolution_list'][] = ['id'=>$res, 'name'=>$data['name']];
                $evolution['evolution_list'] = json_encode($evolution['evolution_list'],JSON_UNESCAPED_UNICODE);

                if(!empty($evolution['evolution_ids'])){
                    $evolution['evolution_ids'] = explode(",", $evolution['evolution_ids']);
                }

                $evolution['evolution_ids'][] = $res;
                $evolution['evolution_ids'] = implode(",", $evolution['evolution_ids']);

                model('Digimon')->save($evolution, ['id'=>$value['id']]);
            }
        }

        if(!empty($res)){
            return $this->success('添加成功');
        }else{
            return $this->error('添加失败');
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