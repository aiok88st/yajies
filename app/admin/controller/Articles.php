<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Controller;
class Articles extends Common
{
    public function _initialize()
    {
        parent::_initialize();
    }
    //获取导航分类
    public function index(){
        $category=db('category');
        $ismenu = "ismenu=1 or ismenu=2";
        $volist=$category->where($ismenu)->where('lang',1)->field("catname as name,id,parentid,child,module")->order('parentid asc,listorder asc')->select();
        $fhref="";
        $title="";
        foreach($volist as $key=>$value){
            if(!$value['child']){
                if($value['module']=='page'){
                    $url=url('page/edit', array('id'=>$value['id']));
                }elseif($value['module']=='product'){
                    $url=url('product/index', array('catid'=>$value['id']));
                }elseif($value['module']=='picture'){
                    $url=url('picture/index', array('catid'=>$value['id']));
                }else{
                    $url=url('Articles/lists',['catid'=>$value['id']]);
                }
                if($fhref==""){
                    $fhref=$url;
                }
                if($title==""){
                    $title=$value['name'];
                }
                $volist[$key]['href']=$url;
            }
        }
        $list=TreeShape($volist,0);
        $this->assign('fhref',$fhref);
        $this->assign('title',$title);
        $this->assign('TreeShape',json_encode($list));

        return $this->fetch();
    }
    //列表页
    public function lists(){
        if(request()->isPost()) {
            $catid=input('catid',0);
            if($catid){
                $catwhere="a.catid=$catid";
            }else{
                $catwhere="a.catid>0";
            }
            $key = input('post.key');
            $this->assign('testkey', $key);
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list = Db::table(config('database.prefix') . 'article')->alias('a')
                ->join(config('database.prefix') . 'category at', 'a.catid = at.id', 'left')
                ->field('a.*,at.catname as typename')
                ->where('a.title', 'like', "%" . $key . "%")
                ->where($catwhere)
                ->order('a.id desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            foreach ($list['data'] as $k=>$v){
                $list['data'][$k]['createtime'] = date('Y-m-d H:i:s',$v['createtime']);
            }
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        $category=db('category');
        $catid=input('catid',false);
        if($catid){
            $catname=$category->where('id',$catid)->value('catname');
        }
        $catname=$catname?$catname:"内容管理";
        $this->assign('mTitle',$catname);
        $this->assign('catid',$catid);
        return $this->fetch();
    }

    //禁用、开启
    public function editState(){
        $id=input('post.id');
        $open=db('article')->where(array('id'=>$id))->value('open');//判断当前状态情况
        if($open==1){
            $data['open'] = 0;
            db('article')->where(array('id'=>$id))->setField($data);
            $data['status'] = 1;
        }else{
            $data['open'] = 1;
            db('article')->where(array('id'=>$id))->setField($data);
            $data['status'] = 1;
        }
        cache('articleList', NULL);
        return $data;
    }

}
