<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/8 0008
 * Time: 9:39
 */

namespace Admin\Controller;


use Think\Controller;

class ArticleController extends Controller
{
    private $_model = null;

    protected function _initialize()
    {
        $meta_titles = array(
            'index' => '文章管理',
            'add' => '文章添加',
            'edit' => '文章修改',
            'delete' => '删除文章',
        );
        $meta_title = isset($meta_titles[ACTION_NAME]) ? $meta_titles[ACTION_NAME] : '供应商管理';
        $this->assign('meta_title', $meta_title);
        $this->_model = D('Article');
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
            if ($this->_model->add_Article() === false) {
                $this->error($this->_model->getError());
            }
            $this->success('添加文章成功！', U('index'));
        } else {
            $category =M('ArticleCategory')->select();
            $this->assign("category", $category);
            $this->display();
        }
    }

    public function edit($id)
    {
        if (IS_POST) {
            if ($this->_model->edit_Article() === false) {
                $this->error($this->_model->getError());
            }
            $this->success('修改文章成功！', U('index'));
        } else {
            $article = $this->_model->where(array('id'=>$id))->find();
            $content=M('ArticleContent')->where(array('article_id'=>$article['id']))->find();
            $category =M('ArticleCategory')->select();
            $this->assign("category", $category);
            $this->assign("article", $article);
            $this->assign("content", $content);
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