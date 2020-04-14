<?php

//
// 用户
// @date 2017-12-28
//
class User extends \BaseModel{

    public function initialize(){
        //$this->setConnectionService( 'db' );
        //$this->setReadConnectionService( 'db' );
        //$this->setWriteConnectionService( 'db' );
        parent::initialize();
        $this->skipAttributes(array('modify'));
    }

    public function getSource() {
        return "user";
    }

   public static function Get($name){
       $data = \User::findFirst("unique_id='{$name}'");
       \SDK\Package\Log::debug("model::User::Get(   unique_id='{$name}' result:".(bool)$data);
       return $data;
   }


    //
    // 主键ID
    // @length 11
    // @type   uint
    //
    public $pkid = 0;

    //
    // 唯一ID
    // @length 255
    // @type   string
    //
    public $unique_id = '';

    //
    // 昵称
    // @length 255
    // @type   string
    //
    public $nick = '';

    //
    // 邮箱
    // @length 255
    // @type   string
    //
    public $mail = '';

    //
    // 注释
    // @length 255
    // @type   string
    //
    public $desc = '';

    //
    // 密码
    // @length 32
    // @type   string
    //
    public $pwd = '';

    //
    // 创建时间
    // @length 11
    // @type   int
    //
    public $ctime = 0;

    //
    // 更新时间
    // @length 11
    // @type   time
    //
    public $modify = '';

    //
    // 头像
    // @length 128
    // @type   string
    //
    public $avatar = '';

    //
    // 状态
    // @length 3
    // @type   tinyint
    //
    public $status = 0;



    public function ToArr(){

        $user = array(
            'pkid' => $this->pkid,
            'unique_id' => $this->unique_id,
            'nick' => $this->nick,
            'mail' => $this->mail,
            'desc' => $this->desc,
            'pwd' => $this->pwd,
            'ctime' => $this->ctime,
            'modify' => $this->modify,
            'avatar' => $this->avatar,
            'status' => $this->status,
            
        );
        return $user;
    }

    public static function ToObj($array){

        $user = new \EntityUser();
        $user->pkid = isset($array['pkid']) ? $array['pkid'] : 0 ;
        $user->unique_id = isset($array['unique_id']) ? $array['unique_id'] : '' ;
        $user->nick = isset($array['nick']) ? $array['nick'] : '' ;
        $user->mail = isset($array['mail']) ? $array['mail'] : '' ;
        $user->desc = isset($array['desc']) ? $array['desc'] : '' ;
        $user->pwd = isset($array['pwd']) ? $array['pwd'] : '' ;
        $user->ctime = isset($array['ctime']) ? $array['ctime'] : 0 ;
        $user->modify = isset($array['modify']) ? $array['modify'] : '' ;
        $user->avatar = isset($array['avatar']) ? $array['avatar'] : '' ;
        $user->status = isset($array['status']) ? $array['status'] : 0 ;

        return $user;
    }

}
