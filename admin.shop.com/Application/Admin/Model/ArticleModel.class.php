<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/8 0008
 * Time: 10:34
 */

namespace Admin\Model;


use Think\Model;
use Think\Page;

class ArticleModel extends Model
{
    /**
     * 获取列表
     * 分页
     * 搜索
     * 排序sort
     * 过滤已删除
     */
    public function getPageResult(array $cond, $page = 1)
    {
        $count = $this->where($cond)->where('status<>-1')->count();
        $rows = $this->where($cond)->where('status<>-1')->order('sort asc')->page($page, C('PAGE_SIZE'))->select();
        foreach($rows as $key=>$val){
            $id=$val['article_category_id'];
            $category_name=M('ArticleCategory')->getFieldById($id,'name');
            $rows[$key]['category_name']=$category_name;
        }
        $page = new Page($count, C('PAGE_SIZE'));
        $page->setConfig('theme', '%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $page_html = $page->show();
        return array('page_html' => $page_html, 'rows' => $rows);
    }

    public function add_Article()
    {
        $content = I('post.content');
        if ($this->create() === false) {
            return $this->error = '添加失败';
        }
        $this->inputtime = time();
        if ($id = $this->add()) {
            $data = array(
                'article_id' => $id,
                'content' => $content,
            );
            return M('ArticleContent')->add($data);
        }
        return false;
    }
    public function edit_Article(){
        $content= I('post.content');
        $id = I('post.id');

        if($this->create()=== false){
            return $this->error = '修改失败';
        }
        $this->inputtime = time();
        if ( $this->save()!==false) {
            $data = array(
                'article_id' => $id,
                'content' => $content,
            );
            return M('ArticleContent')->save($data);
        }
        return false;
    }
    public function changeStatus($id, $status)
    {
        $data = array(
            'id' => $id,
            'name' => array('exp',"concat(`name`, '_del')"),
            'status' => $status,
        );
        if($status !=-1){
            unset($data['name']);
        }
        return $this->save($data);
    }
}