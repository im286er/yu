<?php
namespace Index\Controller;
use Think\Controller;
class ArticleController extends CommonController{
   private $create_cat_id = 3;   //创作区分类id
   private $anli_cat_id = 15;    //安利区分类id  
   //---------------投稿页--------------------
   public function contribute(){
      if(session('uid')){
         switch (I('get.t/s')){
            case 'create':            //创作区
               $this->assign('catlist',D('ArticleCategory')->subCat($this->create_cat_id));
               $tpl = 'add_create';
               break;
            case 'anli':              //安利区
               $this->assign('catlist',D('ArticleCategory')->subCat($this->anli_cat_id));
               $tpl = 'add_anli';
               break;
            default:
               $this->assign('catlist',D('ArticleCategory')->subCat($this->create_cat_id));
               $tpl = 'add_create';
               break;
         }
         $this->display('Article:'.$tpl);
      }else{
          redirect(U('Index/Login/index'));
          return false;
      }
   }
   //-------------投稿处理----------------------
   public function doContribute(){
      $article = D('Article');
      if($article->create()){      
          $aid = $article->add();
          if($aid){ 
             M('User')->where('id='.session('uid'))->setInc('article_num',1);
             $this->yktSuccess('文章提交成功!',U('Index/Home/contribute'));
          }else{
             $this->yktError('文章提交失败!');
          }
      }else{
        $this->yktError($article->getError());  
      }
   }
   //-------------文章详细----------------------
   public function detail(){  
      $vo = D('Article')->relation('cat')->find(I('get.id/d')); 
      if($vo['status']==2){
        $vo['cat_info'] = D('ArticleCategory')->catLink($vo['cat_id']);
        $this->assign('vo',$vo); 
        $this->page_title = $vo['title'].'_'.$vo['cat_name'].'_'.$vo['cat_info']['cat_area']; 
        $this->display();
      }else{
        $this->yktError('文章已经移除或未通过审核!');
        return false;
      }
   } 
   //-------------文章收藏----------------------
   public function doCollect(){  
        if(IS_AJAX && session('uid') && I('aid/d')){
            $ac = M('ArticleCollect');$aid = I('aid/d'); 
            if(I('type')=='+'){
                $map['aid'] = $aid;$map['uid'] = session('uid'); 
                if($ac->where($map)->count()){ //已经收藏过了!
                    $msg['code'] = 2;
                }else{
                    $data = array('uid'=>session('uid'),'aid'=>$aid);
                    if($ac->add($data)){  
                      M('Article')->where('id='.$aid)->setInc('collect_num',1);
                    }
                    $msg['code'] = 1; 
                }
            }else if(I('type')=='-'){
                $map['uid'] = session('uid');
                $map['aid'] = I('aid');
                $ac->where($map)->delete();
                $msg['code'] = 1;
            }else{
                $msg['code'] = '-1';
            }
            $this->ajaxReturn($msg);
        }
   } 
   //-------------文章点赞----------------------
   public function doLike(){  
     if(IS_AJAX){
         M('Article')->where('id='.I('aid/d'))->setInc('like_num',1);
     }
   }
   //-------------文章阅读数----------------------
   public function doView(){  
     if(IS_AJAX){
         M('Article')->where('id='.I('get.aid/d'))->setInc('views',1);
     }
   }
//----------------------------------------------
}

?>