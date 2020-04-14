<?php
//
// codestat项目
//
// codestat项目
// @Description: 
// @Version: V1.0.1
// @Author: simonsun
// @Mail: 4664919@qq.com
// @Last Modified: 2017-12-28 14:16
// @Filename: BaseModel.php
//
//

class BaseModel extends \SDK\Sql\BaseModel{


    public function initialize(){
        parent::initialize();
    }

    public static function C($sql){
        $info = self::Fetch($sql);
        $num = array_shift($info);

        return $num;

    }


}
