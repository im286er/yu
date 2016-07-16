<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Upload;
class OrderController extends CommonController{
    //------------------------------------------
    public function index(){
        if(IS_POST){
        if(I('status'))      $map['status'] = I('status');
        if(I('user'))        $map['uid'] = get_uid(I('user'));
        if(I('uid'))         $map['uid'] = I('uid');
        if(I('order_sn'))    $map['order_sn'] = I('order_sn');
        if(I('start_time') && I('stop_time')){
        $start = strtotime(I('start_time'));
        $stop = strtotime(I('stop_time'));
        $map['add_time']  = array('between',array($start,$stop));
        }else{
        if(I('start_time'))  $map['add_time'] = array('EGT',strtotime(I('start_time')));
        if(I('stop_time'))   $map['add_time'] = array('ELT',strtotime(I('stop_time')));
        }
        }
        $this->mapSearch('Order',$map);
        $this->display(); 
    }
    //------------------------------------------
    public function detail(){
        $order_id = I('get.orderId/d');
        $this->assign('vo',M('Order')->find($order_id));
        $this->display(); 
    }
    //------------------------------------------
    public function goods(){
        $order_id = I('get.orderId/d');
        $this->assign('vo',D('Order')->relation('order_goods')->find($order_id));
        $this->display(); 
    }
    //------------------------------------------
    public function edit(){
        $this->vo = D('Order')->relation('order_goods')->find(I('orderId'));
        $this->display(); 
    }
    //------------------------------------------
    public function update(){
        $order = D('Order');
        if($order->create()){
          if($order->status == C('ORDER_STATUS_VAL.assort')){
            $order->assort_time = time();
          }else{
            $order->assort_time = '';
          }
          if($order->save()){ 
             $this->success('订单编辑成功!');
          } else {
             $this->error('订单编辑失败!');
          }
        }else{
          $this->error($order->getError());  
        }
    }
    //------------------------------------------
    public function doImport(){
         $config = C('UPLOAD_BASE_CONFIG');
         $folder = 'excel';
         $config['savePath'] = DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR;
         $config['exts'] = 'xls';
         $upload = new Upload($config);
         $info=$upload->uploadOne($_FILES['excelData']);
         $filename = './Uploads'.$info['savepath'].$info['savename'];
         $exts = $info['ext'];
         $this->doExcelImport($filename,$exts);
    }
    //------------------------------------------
    protected function doExcelImport($filename, $exts='xls'){
        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
        import("Org.Util.PHPExcel");
        //创建PHPExcel对象，注意，不能少了\
        $PHPExcel=new \PHPExcel();
        //如果excel文件后缀名为.xls，导入这个类
        if($exts == 'xls'){
            import("Org.Util.PHPExcel.Reader.Excel5");
            $PHPReader=new \PHPExcel_Reader_Excel5();
        }else if($exts == 'xlsx'){
            import("Org.Util.PHPExcel.Reader.Excel2007");
            $PHPReader=new \PHPExcel_Reader_Excel2007();
        }
        //---------------------------
        $PHPExcel = $PHPReader->load($filename); // 载入excel文件
        $sheet = $PHPExcel->getSheet(0);  //读取第一個工作表
        $highestRow = $sheet->getHighestRow();  //取得总行数
        $highestColumm = $sheet->getHighestColumn();  //取得总列数
         
        /** 循环读取每个单元格的数据 */
        for ($row = 2; $row <= $highestRow; $row++){//行数是以第1行开始
            for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始
                $dataset[$row][$column] = $sheet->getCell($column.$row)->getValue();
                //echo $column.$row.":".$sheet->getCell($column.$row)->getValue();
            }
        }
        $order = M('Order');
        foreach($dataset as $k=>$v){
            $map['order_sn'] = $v['A'];
            foreach(C('EXPRESS_COM') as $kk=>$vv){
               if($vv==$v['K']) $data['express_com'] = $kk;
            }
            $data['express_num'] = $v['L'];
            $data['send_time_real'] = time();
            $data['status'] = C('ORDER_STATUS_VAL.send');
            $order->where($map)->save($data);
            $data = array();
        }
        echo '导入成功!';
    }
    //------------------------------------------
    public function doExport(){
        $res = array();
        $map['status']  = C('ORDER_STATUS_VAL.hasPay');
        $map['send_time'] = array('elt',time());
        $list = D('Order')->relation('order_goods')->where($map)->select();
        if(empty($list)) return $this->error('暂时没有待发货的订单!');
        
        foreach($list as $k=>$v){
            foreach($v['order_goods'] as $kk=>$vv){
                //订单信息
                $order_sn = $v['order_sn'];                     //订单编号
                $remark = $v['remark'];                         //卖家备注
                $add_time = date('Y-m-d H:i:s',$v['add_time']); //添加时间
                $pay_time = date('Y-m-d H:i:s',$v['pay_time']); //支付时间
                //每件商品信息
                $gp_info     = M('GoodsPrivate')->where('gpid ='.$vv['gpid'].' AND gid ='.$vv['gid'])->find();
                $goods_title = gidToField($vv['gid'],'goods_name').' '.$gp_info['attr_match']; //商品标题(含配搭)
                $abbr        = $gp_info['abbr'];                                               //商品缩写
                $goods_price = $gp_info['price'];                                              //商品单价
                $goods_num = $vv['num'];                                                       //商品数量
                $goods_pay = $goods_num*$goods_price;                                          //商品实付
                //订单收货信息
                $address = M('Address')->where('id ='.$v['address_id'])->find();
                //-----------------------------
                $res[] = array($order_sn,$remark,$add_time,$pay_time,$address['realname'],areaName($address['province']),
                               areaName($address['city']),areaName($address['district']),$address['street'],$address['mobile'],'','',
                               $goods_title,$abbr,$goods_price,$goods_num,$goods_pay,'');
            }
            //更新订单状态
            $data['status'] = C('ORDER_STATUS_VAL.assort');
            $data['assort_time'] = time();
            M('Order')->where('id='.$v['id'])->save($data);
        }
        $this->doExcelExport($res);
    }
    //----------------------------- 
    public function doExcelExport($data){
        import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.IOFactory.php");
        import("Org.Util.PHPExcel.CELL.DataType.php");
        $objPHPExcel=new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator('http://www.jb51.net')
                ->setLastModifiedBy('http://www.jb51.net')
                ->setTitle('Office 2007 XLSX Document')
                ->setSubject('Office 2007 XLSX Document')
                ->setDescription('Document for Office 2007 XLSX, generated using PHP classes.')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('Result file');
                    
        //$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true); 
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20); 
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20); 
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1','订单编号')
                ->setCellValue('B1','卖家备注')
                ->setCellValue('C1','成交时间')
                ->setCellValue('D1','付款时间')
                ->setCellValue('E1','买家姓名')
                ->setCellValue('F1','买家省份')
                ->setCellValue('G1','买家城市')
                ->setCellValue('H1','买家地区')
                ->setCellValue('I1','详细地址')
                ->setCellValue('J1','买家手机')
                ->setCellValue('K1','快递类型')
                ->setCellValue('L1','快递单号')
                ->setCellValue('M1','商品标题')
                ->setCellValue('N1','商家编码')
                ->setCellValue('O1','商品单价')
                ->setCellValue('P1','商品数量')
                ->setCellValue('Q1','实付金额')
                ->setCellValue('R1','分摊运费');
    
        $i=2;   
        foreach($data as $k=>$v){
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueExplicit('A'.$i,$v[0],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('B'.$i,$v[1],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('C'.$i,$v[2],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('D'.$i,$v[3],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('E'.$i,$v[4],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('F'.$i,$v[5],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('G'.$i,$v[6],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('H'.$i,$v[7],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('I'.$i,$v[8],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('J'.$i,$v[9],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('K'.$i,$v[10],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('L'.$i,$v[11],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('M'.$i,$v[12],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('N'.$i,$v[13],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('O'.$i,$v[14],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('P'.$i,$v[15],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('Q'.$i,$v[16],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('R'.$i,$v[17],\PHPExcel_Cell_DataType::TYPE_STRING);
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('待发货订单表'.'_'.date('Y-m-d'));
        $objPHPExcel->setActiveSheetIndex(0);
        $filename = 'send_queue'.'_'.date('Y-m-d').'_'.rand(10,99);
    
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;  
    }
//------------------------------------------------
}

?>