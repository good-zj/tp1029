<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/7 0007
 * Time: 16:35
 */

namespace Admin\Controller;


use Think\Controller;

class SupplierController extends Controller
{
    private $_model = null;

    protected function _initialize()
    {
        $meta_titles = array(
            'index' => '供应商管理',
            'add' => '供应商添加',
            'edit' => '供应商修改',
            'delete' => '删除供应商',
        );
        $meta_title = isset($meta_titles[ACTION_NAME]) ? $meta_titles[ACTION_NAME] : '供应商管理';
        $this->assign('meta_title', $meta_title);
        $this->_model = D('Supplier');
    }

    public function index()
    {
        $keyword = I('get.keyword','');
        $cond=array(
            'name'=>array('like','%'.$keyword.'%'),
        );
        $this->assign($this->_model->getPageResult($cond,$page));
        $this->assign('keyword',$keyword);
        $this->display();
    }

    /**
     * 修改状态和逻辑删除
     */
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

    /**
     * 添加供应商
     */
    public function add()
    {
        if (IS_POST) {
            if (($this->_model->create()) === false) {
                $this->error($this->_model->getError());
            }
            if ($this->_model->add() === false) {
                $this->error($this->_model->getError());
            }
            $this->success('添加成功', U('index'));
        } else {
            $this->display();
        }
    }

    /**
     * 修改供应商
     */
    public function edit($id)
    {
        if (IS_POST) {

            if($this->_model->create()===false){
                $this->error($this->_model->getError());
            }
            if($this->_model->save()===false) {
                $this->error($this->_model->getError());
            }
            $this->success('修改成功！',U('index'));
        }else{
            $rows=$this->_model->find($id);
            $this->assign('rows',$rows);
            $this->display('add');
        }
    }
}