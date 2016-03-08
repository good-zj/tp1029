<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/7 0007
 * Time: 16:36
 */

namespace Admin\Model;


use Think\Model;
use Think\Page;

class SupplierModel extends Model{
    protected $_viladate = array(
        array('name','require','供应商名不能为空'),
        array('name','','供应商名已经存在',self::EXISTS_VALIDATE,'unique'),
    );
    /**
     * 获取列表
     * 分页
     * 搜索
     * 排序sort
     * 过滤已删除
     */
    public function getPageResult(array $cond=array(),$page=1){
        //获取总条数
        $count = $this->where($cond)->where('status<>-1')->count();
        $rows =$this->where($cond)->where('status<>-1')->order('sort asc')->page($page,C('PAGE_SIZE'))->select();
        $page =new Page($count,C('PAGE_SIZE'));
        $page->setConfig('theme','%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $page_html=$page->show();
        return array('page_html'=>$page_html,'rows'=>$rows);
    }

    public function changeStatus($id,$status){
        $data=array(
            'id'=>$id,
            'name'=>array('exp',"concat(`name`,'_del')"),
            'status'=>$status,
        );
        if($status!=-1){
            unset($data['name']);
        }
        return $this->save($data);
    }
}