<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class GoodsPrivateModel extends RelationModel {
    //-------------------------------------------   
    public function addItem($multi,$gid){  
            if($multi){
                $dataArr = array();
                //---------------------------
                $attr  = I('attr');
                $attr_match  = I('attr_match');
                $stock = I('stock');
                $price = I('price');
                $goods_code = I('goods_code');
                $area_code = I('area_code');
                $abbr = I('abbr');
                $weight = I('weight');
                $default = I('default');
                $img = I('img');
                
                foreach($attr as $k=>$v){
                    $dataArr[] = array(
                                 'gid'=>$gid,
                                 'attr'=>$attr[$k],
                                 'attr_match'=>$attr_match[$k],
                                 'stock'=>(int)$stock[$k],
                                 'price'=>(float)$price[$k],
                                 'goods_code'=> $this->genGoodsCode($gid,$k),
                                 'area_code'=>$area_code[$k],
                                 'abbr'=>$abbr[$k],
                                 'weight'=>(float)$weight[$k],
                                 'default'=>$k==$default?1:0,
                                 'img'=>$img[$k]==''?'':$img[$k],
                     ); 
                }
                $gp_ids = $this->addAll($dataArr);  
            }else{
               $data = array(
                             'gid'=>$gid,
                             'attr'=>'',
                             'stock'=>I('stock/d'),
                             'price'=>I('price/f'),
                             'goods_code'=>$this->genGoodsCode($gid,1),
                             'area_code'=>I('area_code'),
                             'abbr'=>I('abbr'),
                             'weight'=>I('weight/f'),
                             'default'=>1
                             );
               $gp_ids = $this->add($data); 
            }
            return $gp_ids;
    } 
    //-------------------------------------------
    public function updateItem($multi,$gid){
                if($multi){
                    $gpid  = I('gpid');
                    $attr  = I('attr');
                    $stock = I('stock');
                    $price = I('price');
                    $goods_code = I('goods_code');
                    $area_code = I('area_code');
                    $abbr = I('abbr');
                    $weight = I('weight');
                    $default = I('default');
                    $img = I('img');
                    
                    foreach($attr as $k=>$v){
                        $data = array(
                                     'gpid'=>$gpid[$k],
                                     'gid'=>$gid,
                                     'attr'=>$attr[$k],
                                     'stock'=>(int)$stock[$k],
                                     'price'=>(float)$price[$k],
                                     'goods_code'=> $goods_code[$k],
                                     'area_code'=>$area_code[$k],
                                     'abbr'=>$abbr[$k],
                                     'weight'=>(float)$weight[$k],
                                     'default'=>$k==$default?1:0,
                                     'img'=>$img[$k]
                         );
                         $this->save($data);
                    }
                }else{
                    $data = array(
                             'gpid'=>I('gpid'),
                             'gid'=>$gid,
                             'attr'=>'',
                             'stock'=>I('stock/d'),
                             'price'=>I('price/f'),
                             'goods_code'=> I('goods_code'),
                             'area_code'=>I('area_code'),
                             'abbr'=>I('abbr'),
                             'weight'=>I('weight/f'),
                             'default'=>1
                    );
                    $this->save($data);
                    
                }
                return true;
    }
    //------------------ÉÌÆ·±àÂë-------------------------
    public function genGoodsCode($gid,$k){
       $uid = I('c_user')!=''?get_uid(I('c_user')):current_uid();
       return $uid.'-'.$gid.'-'.'00'.$k;
    } 
//--------------------------------------------------------------------------------
}

?>