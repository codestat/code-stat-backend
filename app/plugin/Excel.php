<?php
/**
 *
 * codestat项目 
 * @date2016-01-15 15:49:11
 * @author simonsun
 * @email 4664919@qq.com
 */

 class Excel {
    /**
     *  $fileName = "test_excel";
     *  $headArr = array("第一列","第二列","第三列");
     *  $data = array(array(1,2),array(1,3),array(5,7));
     * @param string $fileName
     * @param array $headArr
     * @param array $data
     */
    public static function exportExcel($fileName, $headArr, $data, $m_exportType = "Excel2007") {
        if(is_array($headArr) && is_array($data) && !empty($fileName)){
             //创建新的PHPExcel对象
            $objPHPExcel = new PHPExcel();
            $objProps = $objPHPExcel->getProperties();
            //
            //设置表头
            $fixkey = $key = ord("A");
            $prefix = '';
            foreach($headArr as $v){
                $colum = chr($key);
                $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($prefix.$colum.'1', $v);
                if($colum == 'Z') {
                    $key = ord("@");
                    $prefix = chr($fixkey);
                    $fixkey ++;
                }
                $key += 1;
            }
            $column = 2;
            $objActSheet = $objPHPExcel->getActiveSheet();
            foreach($data as $key => $rows){ //行写入
                $fixkey = $span = ord("A");
                $prefix = '';
                foreach($rows as $keyName=>$value){// 列写入
                    $j = chr($span);
                    $objActSheet->setCellValueExplicit($prefix.$j.$column, $value);
                    if($j == 'Z') {
                        $span = ord("@");
                        $prefix = chr($fixkey);
                        $fixkey ++;
                    }
                    $span++;
                }
                $column++;
            }
            //设置活动单指数到第一个表,所以Excel打开这是第一个表
            $objPHPExcel->setActiveSheetIndex(0);
            
            if($m_exportType == "Excel2007"){
                //将输出重定向到一个客户端web浏览器(Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header("Content-Disposition: attachment; filename=\"$fileName.xls\"");
                header('Cache-Control: max-age=0');
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('php://output'); //文件通过浏览器下载
            }elseif($m_exportType == "Excel5"){
                header('Content-Type: application/vnd.ms-excel');  
                header("Content-Disposition: attachment; filename=\"$fileName.xls\"");
                header('Cache-Control: max-age=0'); 
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');
            }
        }else{
            return false;
        }
    }
 }

