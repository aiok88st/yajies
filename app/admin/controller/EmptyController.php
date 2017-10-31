<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use clt\Form;
class EmptyController extends Common{
    protected  $dao,$fields;
    public function _initialize()
    {
        parent::_initialize();
        $this->moduleid = $this->mod[MODULE_NAME];


        $this->dao = db(MODULE_NAME);

        $fields = F($this->moduleid.'_Field');

        foreach($fields as $key => $res){
            $res['setup']=string2array($res['setup']);
            $this->fields[$key]=$res;
        }

        unset($fields);
        unset($res);
        $this->assign ('fields',$this->fields);
    }
    public function index(){
        $request = Request::instance();
        $modelname = strtolower($request->controller());
        if(request()->isPost()){
            $model = db($modelname);
            $keyword=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $order = "listorder asc,id desc";
            if (input('post.catid')) {
                $catids = db('category')->where('parentid',input('post.catid'))->column('id');
                if($catids){
                    $catid = input('post.catid').','.implode(',',$catids);
                }else{
                    $catid = input('post.catid');
                }
            }
            if(!empty($keyword) ){
                $map['title']=array('like','%'.$keyword.'%');
            }
            $prefix=config('database.prefix');
            $Fields=Db::getFields($prefix.$modelname);
            foreach ($Fields as $k=>$v){
                $field[$k] = $k;
            }
            if(in_array('catid',$field)){
                if(input('post.catid')){
                    $map['catid']=array('in',$catid);
                }
            }
            $list = $model
                ->where($map)
                ->order($order)
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            //echo $model->getLastSql();
            $rsult['code'] = 0;
            $rsult['msg'] = "获取成功";
            $lists = $list['data'];
            foreach ($lists as $k=>$v ){
                $lists[$k]['createtime'] = date('Y-m-d H:i:s',$v['createtime']);
            }
            $rsult['data'] = $lists;
            $rsult['count'] = $list['total'];
            $rsult['rel'] = 1;
            return $rsult;
        }else{
            $module = db('module');
            $tem = $module->where('name',$modelname)->field('template')->find();
            return $this->fetch("{$tem['template']}");
        }
    }

    public function edit(){
        $id = input('id');
        $request = Request::instance();
        $controllerName = $request->controller();
        if($controllerName=='Page'){
            $p = $this->dao->where('id',$id)->find();

            if(empty($p)){
                $data['id']=$id;
                $cat = db('category')->where('id',$id)->field('catname,keywords')->find();
                $data['title'] = $cat['catname'];//$this->categorys[$id]['catname'];
                $data['keywords'] = $cat['keywords'];//$this->categorys[$id]['keywords'];

                $this->dao->insert($data);
            }

        }

        $info = $this->dao->where('id',$id)->find();

        $catid = input('catid');
        $lang = db("category")->where(array('id'=>$catid))->value('lang');

        $form=new Form($info);
        $returnData['vo'] = $info;
        $returnData['form'] = $form;
        $this->assign ('info', $info );
        $this->assign ( 'form', $form );
        $this->assign ( 'title', '编辑内容' );
        $this->assign ( 'lang', $lang );
        $this->assign ( 'controllerName', $controllerName );
        return $this->fetch('content/edit');
    }
    function update(){
        $purl = $_SERVER['HTTP_REFERER'];
        $request = Request::instance();
        $controllerName = $request->controller();
        $model = $this->dao;
        $fields = $this->fields;
        $data = $this->checkfield($fields,input('post.'));
        if($data['code']=="0"){
            $result['msg'] = $data['msg'];
            $result['code'] = 0;
            return $result;
        }
        if(isset($fields['updatetime'])) {
            $data['userid'] = session('aid');
        }

        if(isset($fields['updatetime'])) {
            $data['updatetime'] = time();
        }

        $title_style ='';
        if (isset($data['style_color'])) {
            $title_style .= 'color:' . $data['style_color'].';';
            unset($data['style_color']);
        }else{
            $title_style .= 'color:#222;';
        }
        if (isset($data['style_bold'])) {
            $title_style .= 'font-weight:' . $data['style_bold'].';';
            unset($data['style_bold']);
        }else{
            $title_style .= 'font-weight:normal;';
        }
        if($fields['title']['setup']['style']==1) {
            $data['title_style'] = $title_style;
        }
        if($controllerName!='Page') {
            $data['updatetime'] = time();
        }
        unset($data['aid']);
        unset($data['pics_name']);
        //编辑多图和多文件
        foreach ($fields as $k=>$v){
            if($v['type']=='files' or $v['type']=='images'){
                if(!$data[$k]){
                    $data[$k]='';
                }
            }
        }
        $list=$model->update($data);
        $lang = db("category")->where(array('id'=>$data['catid']))->value('lang');
        if (false !== $list) {
            if($controllerName=='Page'){
                $result['url'] = $purl;//url("admin/category/index");
            }elseif($controllerName=='Article' && $lang == 1){
                $result['url'] = url("admin/Articles/lists",array('catid'=>$data['catid']));
            }elseif($controllerName=='Enarticle' && $lang == 2){
                $result['url'] = url("admin/Articles/lists2",array('catid'=>$data['catid']));
            }elseif($controllerName=='Product'){
                $result['url'] = url("admin/product/index",array('catid'=>$data['catid']));
            }elseif($controllerName=='Enproduct'){
                $result['url'] = url("admin/enproduct/index",array('catid'=>$data['catid']));
            }else{
                $result['url'] = url("admin/".$controllerName."/index",array('catid'=>$data['catid']));
            }
            $result['msg'] = '修改成功!';
            $result['code'] = 1;
            return $result;
        } else {
            $result['msg'] = '修改失败!';
            $result['code'] = 0;
            return $result;
        }
    }
    public function set_categorys($categorys = array()) {
        if (is_array($categorys) && !empty($categorys)) {
            foreach ($categorys as $id => $c) {
                $this->categorys[$c['id']] = $c;
                $r = db('category')->where("parentid = $c[id]")->order('listorder ASC,id ASC')->select();
                $this->set_categorys($r);
            }
        }
        return true;
    }
    function checkfield($fields,$post){
        foreach ( $post as $key => $val ) {
            if(isset($fields[$key])){
                $setup=$fields[$key]['setup'];
                if(!empty($fields[$key]['required']) && empty($post[$key])){
                    $result['msg'] = $fields[$key]['errormsg']?$fields[$key]['errormsg']:'缺少必要参数！';
                    $result['code'] = 0;
                    return $result;
                }
                if(isset($setup['multiple'])){
                    if(is_array($post[$key])){
                        $post[$key] = implode(',',$post[$key]);
                    }
                }
                if(isset($setup['inputtype'])){
                    if($setup['inputtype']=='checkbox'){
                        $post[$key] = implode(',',$post[$key]);
                    }
                }
                if(isset($setup['type'])){
                    if($fields[$key]['type']=='checkbox'){
                        $post[$key] = implode(',',$post[$key]);
                    }
                }
                if($fields[$key]['type']=='datetime'){
                    $post[$key] =strtotime($post[$key]);
                }elseif($fields[$key]['type']=='textarea'){
                    $post[$key]=addslashes($post[$key]);
                }elseif($fields[$key]['type']=='editor'){
                    if(isset($post['add_description']) && $post['description'] == '' && isset($post['content'])) {
                        $content = stripslashes($post['content']);
                        $description_length = intval($post['description_length']);
                        $post['description'] = str_cut(str_replace(array("\r\n","\t",'[page]','[/page]','&ldquo;','&rdquo;'), '', strip_tags($content)),$description_length);
                        $post['description'] = addslashes($post['description']);
                    }
                    if(isset($post['auto_thumb']) && $post['thumb'] == '' && isset($post['content'])) {
                        $content = $content ? $content : stripslashes($post['content']);
                        $auto_thumb_no = intval($post['auto_thumb_no']) * 3;
                        if(preg_match_all("/(src)=([\"|']?)([^ \"'>]+\.(gif|jpg|jpeg|bmp|png))\\2/i", $content, $matches)) {
                            $post['thumb'] = $matches[$auto_thumb_no][0];
                        }
                    }
                }
            }
        }
        return $post;
    }

    public function add(){
        $catid = input('catid');
        $lang = db("category")->where(array('id'=>$catid))->value('lang');
        $request = Request::instance();
        $controllerName = $request->controller();
        $form=new Form();
        $this->assign ( 'form', $form );
        $this->assign ( 'title', '添加内容' );
        $this->assign ( 'lang', $lang );
        if($controllerName == 'Network'){
            return $this->fetch('networks/add');
        }
        $this->assign ( 'controllerName', $controllerName );
        return $this->fetch('content/edit');
    }
    public function insert(){
        $request = Request::instance();
        $controllerName = $request->controller();
        $model = $this->dao;
        $fields = $this->fields;
        $data = $this->checkfield($fields,input('post.'));
        if(isset($data['code']) && $data['code']==0){
            return $data;
        }
        if($fields['createtime']  && empty($data['createtime']) ){
            $data['createtime'] = time();
        }
        if($fields['updatetime']  && empty($data['updatetime']) ) {
            $data['updatetime'] = time();
        }
        if($controllerName!='Page') {
            if ($fields['updatetime']){
                $data['updatetime'] = $data['createtime'];
            }
        }
        $data['userid'] = session('aid');
        $data['username'] = session('username');

        $title_style ='';
        if (isset($data['style_color'])) {
            $title_style .= 'color:' . $data['style_color'].';';
            unset($data['style_color']);
        }else{
            $title_style .= 'color:#222;';
        }
        if (isset($data['style_bold'])) {
            $title_style .= 'font-weight:' . $data['style_bold'].';';
            unset($data['style_bold']);
        }else{
            $title_style .= 'font-weight:normal;';
        }
        if($fields['title']['setup']['style']==1) {
            $data['title_style'] = $title_style;
        }

        $aid = $data['aid'];
        unset($data['style_color']);
        unset($data['style_bold']);
        unset($data['aid']);
        unset($data['pics_name']);
        $id= $model->insertGetId($data);
        $lang = db("category")->where(array('id'=>$data['catid']))->value('lang');
        if ($id !==false) {
            $catid = $controllerName =='page' ? $id : $data['catid'];

            if($aid) {
                $Attachment =db('attachment');
                $aids =  implode(',',$aid);
                $data2['id']=$id;
                $data2['catid']= $catid;
                $data2['status']= '1';
                $Attachment->where("aid in (".$aids.")")->update($data2);
            }

            if($controllerName=='Page'){
                $result['url'] = url("admin/category/index");
            }elseif($controllerName=='Article' && $lang == 1){
                $result['url'] = url("admin/articles/lists",array('catid'=>$data['catid']));
            }elseif($controllerName=='Enarticle' && $lang == 2){
                $result['url'] = url("admin/articles/lists2",array('catid'=>$data['catid']));
            }elseif($controllerName=='Product'){
                $result['url'] = url("admin/product/index",array('catid'=>$data['catid']));
            }elseif($controllerName=='Enproduct'){
                $result['url'] = url("admin/enproduct/index",array('catid'=>$data['catid']));
            }else{
                $result['url'] = url("admin/".$controllerName."/index",array('catid'=>$data['catid']));
            }
            $result['msg'] = '添加成功!';
            $result['code'] = 1;
            return $result;
        } else {
            $result['msg'] = '添加失败!';
            $result['code'] = 0;
            return $result;
        }

    }


    public function listDel(){
        $id = input('post.id');
        $model = $this->dao;
        $model->where(array('id'=>$id))->delete();//转入回收站
        return ['code'=>1,'msg'=>'删除成功！'];
    }

    public function delAll(){
        $map['id'] =array('in',input('param.ids/a'));
        $model = $this->dao;
        $model->where($map)->delete();
        $result['code'] = 1;
        $result['msg'] = '删除成功！';
        $result['url'] = url('index',array('catid'=>input('post.catid')));
        return $result;
    }
    public function listorder(){
        $model = $this->dao;
        $catid = input('catid');
        $data = input('post.');
        $model->update($data);
        $result = ['msg' => '排序成功！','url'=>url('index',array('catid'=>$catid)), 'code' => 1];
        return $result;
    }
    public function delImg(){
        $file = ROOT_PATH.__PUBLIC__.input('post.url');
        if(file_exists($file)){
            is_dir($file) ? dir_delete($file) : unlink($file);
        }
        if(input('post.id')){
            $picurl = input('post.picurl');
            $picurlArr = explode(':',$picurl);
            $pics = substr(implode(":::",$picurlArr),0,-3);
            $model = $this->dao;
            $map['id'] =input('post.id');
            $model->where($map)->update(array('pics'=>$pics));
        }
        $result['msg'] = '删除成功!';
        $result['code'] = 1;
        return $result;
    }

    //加入首页推荐
    public function addHots(){
        $id = input('post.id');
        $model = $this->dao;
        $status = $model->where(array('id'=>$id))->value('status');//判断当前状态情况
        if($status == 2){
            $data['status'] = 1;
            $model->where(array('id'=>$id))->setField($data);
        }else{
            $data['status'] = 2;
            $model->where(array('id'=>$id))->setField($data);
        }
        $data['code'] = 1;
        return $data;

    }

    //省市区三级联动
    public function getAddrs(){
        $region = db('region');
        $pid=input('pid');
        $arr=$region->where("pid=$pid")->select();
        foreach($arr as $v){
            echo "<option value='{$v['id']}'>{$v['name']}</option>";
        }
    }


}