<?php

class LoginController extends BaseController {

    public function indexAction(){
        $this->view->setMainView('login/index');
    }

    public function login_outAction(){
        $session = \Phalcon\DI::getDefault()->get("session");
        $session->set("user",NULL);
        //todo cookie 的处理
        return $this->Redirect('/');

    }
    public function login_inAction(){
        $pwd = $this->request->get("pwd","string");
        $name = $this->request->get("name","string");
        if($pwd == NULL){
            return $this->ResError(10002);
        }
        if($name == NULL){
            return $this->ResError(10001);
        }
        $user = \User::Get($name);
        if( ! $user ){
            return $this->ResError(\ErrorService::Get(100000,"用户未找到"));
        }
        if($user->pwd !== \LoginService::PwdHash($pwd)){
            return $this->ResError(10018);
        }
        \LoginService::UserLogin($user->toArray());

        return $this->Redirect('/');
        //return $this->Res();
    }



}
