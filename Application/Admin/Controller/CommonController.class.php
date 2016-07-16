<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Page;
use Think\Upload; //上传类
use Think\Image; //缩略图类
class CommonController extends Controller {
   //-----------------------------------
   protected function _initialize(){
        header("Content-Type: text/html;charset=utf-8");
   }
   //-----------------------------------后台公共搜索
   public function mapSearch($model_name,$map,$orderby,$field) {
              $d_model = D($model_name);
              $count = $d_model->where($map)->count();
              if($count > 0) {
                 $pk = $d_model->getPk();
                 $orderby = $orderby == ''?$pk.' DESC':$orderby;
                 $listRows = I('numPerPage') != ''?I('numPerPage'):C('PAGE_LISTROWS');
                 $page = new Page($count,$listRows);
                 $currentPage = I(C('VAR_PAGE')) != ''?I(C('VAR_PAGE')):1;
                 $list = $d_model->relation(true)->where($map)->field($field)->order($orderby)->page($currentPage.','.$listRows)->select();
                 $show = $page->show();
                 $res['list'] = $list;
                 $res['page'] = $show;
                 $res['totalCount'] = $count;
                 $res['numPerPage'] = $listRows;
                 $res['currentPage'] = $currentPage;
                 $this->assign('list',$res['list']);
                 $this->assign('page',$res['page']);
                 $this->assign('totalCount',$res['totalCount']);
                 $this->assign('numPerPage',$res['numPerPage']);
                 $this->assign('currentPage',$res['currentPage']);
              }
    } 
   //-----------------------------------后台批量删除
   public function foreverdelete() {
      $name = CONTROLLER_NAME;
      $model = M($name);
      if(!empty($model)) {
         $pk = $model->getPk();
         $id = $_REQUEST[$pk]; //存放要删除的ID，可以多个
         if(isset($id)) {
            $condition = array($pk => array('in',explode(',',$id))); //删除条件，形如 id in 1,2,5,6....
            if($model->where($condition)->delete()) {
               $this->success('删除成功！');
            } else {
               $this->error('删除失败！');
            }
         }
      } else {
         $this->error('非法操作');
      }
   }
   //----------------------------------后台上传图片
   public function upload(){            
		 $config = C('UPLOAD_BASE_CONFIG');
         $config['savePath'] = DIRECTORY_SEPARATOR.strtolower(CONTROLLER_NAME).DIRECTORY_SEPARATOR;
         $this->upload_config=$config;
         $upload = new Upload($this->upload_config);
         $info=$upload->uploadOne($_FILES[__CONTROLLER__]);
 		 if($info){
 		    if(I('get.cut')==1){
 		     $width = (int)I('get.w')?(int)I('get.w'):0;
             $heigh = (int)I('get.h')?(int)I('get.h'):0;
             if($width > 0 && $heigh > 0){
    			$fname=$upload->rootPath.$info['savepath'].$info['savename'];
    			$image=new Image();
                $image->Open($fname);
    	        $image->thumb($width,$heigh,Image::IMAGE_THUMB_FILLED)->save($fname);
             }
 		    } 
            echo $info['savepath'].$info['savename'];
  	     }else{
                echo $upload->getError();
                return false;
  	     }
	} 
//------------------------------------------------------
}

?>