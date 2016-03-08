<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/8 0008
 * Time: 14:17
 */

namespace Admin\Controller;


use Think\Controller;

class ArticleCategoryController extends Controller{
    private $_model = null;

    protected function _initialize()
    {
        $meta_titles = array(
            'index' => '分类管理',
            'add' => '添加分类',
            'edit' => '修改分类',
            'delete' => '删除分类',
        );
        $meta_title = isset($meta_titles[ACTION_NAME]) ? $meta_titles[ACTION_NAME] : '分类管理';
        $this->assign('meta_title', $meta_title);
        $this->_model = D('ArticleCategory');
    }
    public function index()
    {
        $keyword = I('get.keyword');
        $cond = array(
            'name' => array('like', '%' . $keyword . '%')
        );
        $page = I('get.p', 1);
        $this->assign($this->_model->getPageResult($cond, $page));
        $this->assign('keyword', $keyword);
        $this->display();
    }

    public function add()
    {
        if (IS_POST) {
            if ($this->_model->create() === false) {
                $this->error($this->_model->getError());
            }
            if($this->_model->add()===false){
                $this->error($this->_model->getError());
            }
            $this->success('添加分类成功！', U('index'));
        } else {
            $this->display();
        }
    }

    public function edit($id)
    {
        if (IS_POST) {
            if ($this->_model->create() === false) {
                $this->error($this->_model->getError());
            }
            if($this->_model->save() === false){
                $this->error($this->_model->getError());
            }
            $this->success('修改分类成功！', U('index'));
        } else {
            $article = $this->_model->where(array('id'=>$id))->find();
            $this->assign("article", $article);
            $this->display('add');
        }
    }

    public function changeStatus($id, $status = -1)
    {
        if ($this->_model->changeStatus($id, $status) === false) {
            $this->error($this->_model->getError());
        } else {
            if ($status == -1) {
                $msg = "删除成功！";
            } else {
                $msg = "修改成功!";
            }
            $this->success($msg, U('index'));
        }

    }
}