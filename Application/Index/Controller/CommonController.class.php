<?php
namespace Index\Controller;
use Think\Controller;
use Think\Upload;                                 //上传类
use Think\Image;                                  //缩略图类
use Index\Util\Page;                             //改进分页类
class CommonController extends Controller{
	protected function _initialize(){
		header("Content-Type:text/html;charset=utf-8");
	}
    //-----------------------------------前台公共搜索
    public function mapSearch($model_name,$map,$relation=true,$field='',$orderby='',$listRows='',$cache=array()){
                 //----------缓存设置
                 $cache_name = $cache['cache_name']==''?false:$cache['cache_name'];
                 $cache_time = $cache['cache_time']==''?60:$cache['cache_time'];
                 $cache_type = C('DATA_CACHE_TYPE');
                 //---------------------------
                 $currentPage = I(C('VAR_PAGE')) != ''?I(C('VAR_PAGE')):1;  //当前页数
                 $d_model = D($model_name);
                 $count = $d_model->where($map)->count();
                 if($count > 0) {
                     $pk = $d_model->getPk();
                     $orderby = $orderby == ''?$pk.' DESC':$orderby;
                     $listRows = $listRows!= ''?$listRows:C('PAGE_LISTROWS'); //每页记录数
                     $page = new Page($count,$listRows);
                     $list = $d_model->cache($cache_name,$cache_time,$cache_type)
                                     ->relation($relation)
                                     ->where($map)->field($field)
                                     ->order($orderby)
                                     ->page($currentPage.','.$listRows)
                                     ->select();
                     //-----------------------------分配参数
                     $show = $page->show();
                     $this->assign('list',$list);
                     $this->assign('page',$show);
                     $this->assign('totalCount',$count);
                     $this->assign('numPerPage',$listRows);
                     $this->assign('currentPage',$currentPage);
                     $this->assign('totalPage',ceil($count/$listRows));
                 }
              
    }
    //-----------------------------------公用上传模块
    public function upload(){    
		 $config = C('UPLOAD_BASE_CONFIG');
         $folder = I('get.folder')!=''?I('get.folder'):strtolower(CONTROLLER_NAME);
         $config['savePath'] = DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR;
         $upload = new Upload($config);
         $info=$upload->uploadOne($_FILES[__CONTROLLER__]);
 		 if($info){
              if(I('get.cut')==1){
                  $width = (int)I('get.w')?(int)I('get.w'):0;
                  $heigh = (int)I('get.h')?(int)I('get.h'):0;
     		      if($width > 0 && $heigh > 0){
     		         $fname=$upload->rootPath.$info['savepath'].$info['savename'];
        			 $image=new Image();
                     $image->Open($fname);
        	         $image->thumb($width,$heigh,Image::IMAGE_THUMB_CENTER)->save($fname); 
     		      }
              }
    		  echo $info['savepath'].$info['savename'];
  	     }else{
                echo $upload->getError();
                return false;
  	     }
	} 
    //-----------------------------------公用分页赋值
    public function yktSuccess($msg,$jumpUrl,$sec,$link){    
       if($sec=='') $sec = 5;
       $this->assign('msg',$msg);
       $this->assign('jumpUrl',$jumpUrl);
       $this->assign('sec',$sec);
       $this->assign('link',$link);
       $this->display('include:success');
	}
    //-----------------------------------公用分页赋值
    public function yktError($msg,$jumpUrl,$sec){ 
       if($sec=='') $sec = 5;
       $this->assign('msg',$msg);
       $this->assign('jumpUrl',$jumpUrl);
       $this->assign('sec',$sec);
       $this->display('include:error');
	}
    //-----------------------------------空操作
    public function _empty($name){
        $this->display('include:404');
    }
//-----------------------------------------------
}

?>