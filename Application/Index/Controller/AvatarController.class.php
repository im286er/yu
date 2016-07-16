<?php
namespace Index\Controller;
use Think\Controller;
class AvatarController extends CommonController {
   //--------------------------------------------
   protected function _initialize() {
      if(!isLogin()) {
         return false;
      }
   }
   //上传头像
   public function upload() {
      define('ALLOW_UPLOAD_IMAGE_TYPES','jpg, jpeg, png, gif'); // 允许上传的图片类型
      define('MAX_UPLOAD_SIZE',1048576); // 最大允许上传的图片大小(1M = 1024 * 1024 = 1048576)
      // ===========================头像裁切尺寸
      define('AVATAR_WIDTH',90);
      define('AVATAR_HEIGHT',90);
      // ===========================最大图片尺寸，过大时将自动按比例缩小，防止超大图片撑破页面
      define('AVATAR_MAX_WIDTH',500);
      define('AVATAR_MAX_HEIGHT',1000);

      import("Common.Vendor.AvatarUpload.uploader",dirname(COMMON_PATH),".php");
      $uploader = new \uploader(explode(', ',ALLOW_UPLOAD_IMAGE_TYPES),
         MAX_UPLOAD_SIZE);
      $result = $uploader->upload(C('AVATAR_PATH')); // 先保存到临时文件夹
      if(isset($result['success']) && $result['success']) {
         import("Common.Vendor.AvatarUpload.gd",dirname(COMMON_PATH),".php");
         $src_path = C('AVATAR_PATH').$uploader->get_real_name();
         $gd = new \gd();
         $gd->open($src_path);
         if($gd->is_image()) {
            $reponse->success = true;
            $reponse->tmp_avatar = $uploader->get_real_name();
            if($gd->get_width() < AVATAR_WIDTH) {
               $reponse->success = false; // 传递给 file-uploader 表示服务器端已处理
               $reponse->description = '您上传的图片宽度('.$gd->get_width().'像素)过小！最小需要'.AVATAR_WIDTH.
                  '像素。';
            }
            if($gd->get_height() < AVATAR_HEIGHT) {
               $reponse->success = false; // 传递给 file-uploader 表示服务器端已处理
               $reponse->description = '您上传的图片高度('.$gd->get_height().'像素)过小！最小需要'.
                  AVATAR_HEIGHT.'像素。';
            }
            if($gd->get_width() > AVATAR_MAX_WIDTH || $gd->get_height() > AVATAR_MAX_HEIGHT) {
               // 图片过大时按比例缩小，防止超大图片撑破页面
               $gd->resize_to(AVATAR_MAX_WIDTH,AVATAR_MAX_HEIGHT,'scale');
               $gd->save_to($src_path);
            }
         }
      }elseif(isset($result['error'])) {
         $reponse->success = false;
         $reponse->description = $result['error'];
      }
      header('Content-type: application/json');
      echo json_encode($reponse);
   }
   //账号-----------------头像设置-----------------------------
   //裁切头像
   public function cropUpload() {
      // ===========================头像裁切尺寸
      define('AVATAR_WIDTH',90);
      define('AVATAR_HEIGHT',90);
      $x1 = I('x1');$x2 = I('x2');$y1 = I('y1');$y2 = I('y2');$w = I('w');$h = I('h'); //获取参数
      $tmp_avatar = I('tmp_avatar');
      $src_path = C('AVATAR_PATH').$tmp_avatar; //获取原图像
      if(!file_exists($src_path)) {
         $reponse->success = false;
         $reponse->description = '未找到图片文件';
      } else {
         import("Common.Vendor.AvatarUpload.gd",dirname(COMMON_PATH),".php");
         $gd = new \gd();
         $gd->open($src_path);
         if($gd->is_image()) {
            //----------------------------------
            if(!is_dir(C('AVATAR_DATE_PATH')))
               mkdir(C('AVATAR_DATE_PATH')); //创建头像文件夹
            if($gd->get_type() == 'gif') {
               $avatar_target = C('AVATAR_DATE_PATH').md5(session('uid').time()).'.'.$gd->get_type();
            } else {
               $gd->crop($x1,$y1,$w,$h);
               $gd->resize_to(AVATAR_WIDTH,AVATAR_HEIGHT,'scale_fill');
               $avatar_name = md5(session('uid').time()).'.'.$gd->get_type();
               $avatar_target = C('AVATAR_DATE_PATH').$avatar_name;
               //$gd->save_to($avatar_target);
            }
            copy($src_path,$avatar_target);
            //----------------------------------
            $old_avatar_path = './Uploads';
            $old_avatar_path .= M('User')->where("id=".session('uid'))->getField('avatar');
            if(file_exists($old_avatar_path)) unlink($old_avatar_path); //删除原头像
            
            //保存到数据库
            $user = M('User');
            $data['id'] = session('uid');
            $data['avatar'] = substr($avatar_target,9);
            $user->save($data);
            //--------------------
            $reponse->success = true;
            $reponse->avatar = substr($avatar_target,1);
            $reponse->description = '';
            unlink($src_path);
         } else {
            $reponse->success = false;
            $reponse->description = '该图片文件不是有效的图片';
         }
      }
      header('Content-type: application/json');
      echo json_encode($reponse);
   }
//-------------------------------------------------------------------------
}

?>