<?php

//
// 数据别名
// @date 2017-12-28
//
class AliasService{

    public static function GetMenu(){
        $menu = array(
            'revision_path' => '版本号具体的提交路径',
    'revision' => '版本号',
    'user' => '用户',

        );
    
        return $menu;
    }


    
    // 
    // 别名获取函数
    // 路径的类型
    // @date 2017-12-28
    // 
    public static function GetRevisionPathKind(){
		return array(
			'file' => '文件',
			'dir' => '文件夹',
		);

	}

    // 
    // 别名获取函数
    // 提交的类型
    // @date 2017-12-28
    // 
    public static function GetRevisionPathAction(){
		return array(
			'A' => '创建',
			'D' => '删除',
			'M' => '修改',
			'R' => '还原',
		);

	}

    // 
    // 别名获取函数
    // 状态
    // @date 2017-12-28
    // 
    public static function GetRevisionPathStatus(){
		return array(
			'0' => '处理中',
			'1' => '显示',
			'-1' => '隐藏',
			'-10001' => '取行出错',
		);

	}

    // 
    // 别名获取函数
    // 状态
    // @date 2017-12-28
    // 
    public static function GetUserStatus(){
		return array(
			'0' => '禁用',
			'1' => '显示',
		);

	}


}