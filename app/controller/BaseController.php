<?php


class BaseController extends \Phalcon\Mvc\Controller {

    public $unique_id = NULL;
    public $nick_name = NULL;

    public function initialize(){

        if(IS_DEBUG == true){
            $this->view->disable();
        }else{
             $this->view->setVar('_seo_html_title_name', \Phalcon\DI::getDefault()->get("config")->app->name);  
        }
        $user = NULL;
        $controller = strtolower($this->router->getControllerName());
        $action = strtolower($this->router->getActionName());
        \SDK\Package\Log::begin("{$controller}_{$action}","pms_admin");
        $session = \Phalcon\DI::getDefault()->get("session");
        $u = $session->get('user');
        $req = $_REQUEST;
        unset($req["_url"]);
        $req["gold_user"] = isset($u['unique_id']) ? $u['unique_id'] : "nobody";
        $req["gold_token"] = "simon_debug_ece3db145bc2804d776d3bbf8c21fae13382306c23596b9010e01c135a743dcdb";
        $uri = parse_url($_SERVER['REQUEST_URI']);
        $uri = DOMAIN."{$uri['path']}?".http_build_query($req);
        \SDK\Package\Log::debug("{$uri}",1);

        //step 初始化
        //自动登录功能：
        if(isset($_GET['gold_user']) && isset($_GET['gold_token'])){
            if($_GET['gold_token'] == "simon_debug_ece3db145bc2804d776d3bbf8c21fae13382306c23596b9010e01c135a743dcdb"){
                $user = \User::Get($_GET['gold_user']);
                if($user){
                    $user = $user->toArray();
                }
                if(isset($_GET['auto_login'])){
                    \LoginService::UserLogin($user);
                }
            }
        }

        if($controller == 'login'){
            //controller login 不进行余下的权限验证，
            return true;
        }

        if($user == NULL){
            $user = \LoginService::LoginInfo();
        }
        if($user == NULL){
            //到登陆页面
            return $this->Redirect("/login/index");
        }
        $this->unique_id = $user['unique_id'];
        $this->nick_name = $user['nick'];
        $this->avatar = $user['avatar'];
        if(IS_DEBUG == true){
            $this->view->disable();
        }else{
             $this->view->setVar('user_unique_id', $this->unique_id);  
             $this->view->setVar('user_nick_name', $this->nick_name);  
             $this->view->setVar('user_avatar', $this->avatar);  
        }

    }

    public function NotFound(){
        $this->dispatcher->forward(array(
            'controller' => 'index',
            'action' => 'notfound'
        ));
        return;
    }
    public function Redirect($url,$code = 302){
        if(IS_DEBUG == true){
            \SDK\Package\Log::debug("Redirect URL:{$url},http code:{$code}");
        }else{
            $this->response->redirect($url,true,$code);
            $this->response->send();
        }
        exit; //todo
        return;
    }
    public function ResError($codeOrService,$is_force_json = false){
        $code = null;
        $msg = null;
        $data = null;
        if(is_array($codeOrService)){
            //传递的是errorServiceData
            $code = $codeOrService[0];
            $msg = $codeOrService[1];
            $data = $codeOrService[2];
        }else{
            list($code,$msg) = \ErrorService::Get($codeOrService); 
        }
        \SDK\Package\Log::debug("提示用户操作失败：code：{$code},msg:{$msg} data:".print_r($data,1));
        if($this->request->isAjax() == true || $is_force_json == true){
            $callback = $this->request->getQuery("callback");
            if($callback != ""){
                SDK\Package\Output\Jsonp::$callback_name = $callback;
                SDK\Package\Output\Jsonp::outputError($code,$msg,$data);
            }
            SDK\Package\Output\Json::outputError($code,$msg,$data);
        }else{
            $redirect = "/{$this->router->getControllerName()}";
            $this->view->setVar('code',$code);
            $this->view->setVar('msg',$msg);
            $this->view->setVar('res',$data);
            $this->view->setVar('redirect',$redirect);
            $this->view->pick('layout/msg_err');
        }
    }
    public function Res( & $res = null,$is_force_json = false){
        \SDK\Package\Log::debug("提示用户操作成功：res:".print_r($res,1));
        if(IS_DEBUG == true){
            echo "<pre>";
            print_r($res);
        }
        if($this->request->isAjax() == true || $is_force_json == true){
            $callback = $this->request->getQuery("callback");
            if($callback != ""){
                SDK\Package\Output\Jsonp::$callback_name = $callback;
                SDK\Package\Output\Jsonp::outputSuccess($res);
            }
            if(isset($res['page'])){
                $res['page'] = "this filed is closed";
            }
            SDK\Package\Output\Json::outputSuccess($res);
        }else{
            $msg = "操作成功";
            $redirect = "/{$this->router->getControllerName()}";
            $this->view->setVar('res',$res);
            $this->view->setVar('msg',$msg);
            $this->view->setVar('redirect',$redirect);
            //$this->view->setMainView('layout/msg_success');
            $this->view->pick("layout/msg_success");
        }
    }

    // js 加载
    // 放到页面最底部
    // todo 目前只支持单个js引入
    // 以后可以考虑加入多个js引入
    public function LoadJs($js){
        $info = array();
        $info[] = $js;
        $this->view->setVar ('load_js', $info);
    }

    public function ShowPage($total,$page,$limit){
        
        $pageShow = new \SDK\Package\MongoPage();
        $pageShow->total = $total;
        $pageShow->split = $limit;
        $pageShow->now = $page;
        $pageShow->show_elements = 10;
        $pageShow->format_next = '<li class="paginate_button next"><a page="%s" href="?page=">下一页</a></li>';
        $pageShow->format_prev = '<li class="paginate_button previous"><a page="%s" href="?page=">上一页</a></li>';
        $pageShow->format_split = '<li class="paginate_button "><a  page="%s" href="?page=">%s</a></li>';
        $pageShow->format_active = '<li class="paginate_button active"><a  page="" href="#">%s</a></li>';

        $page_html = $pageShow->show();
        $this->view->setVar ('page', $page);
        $this->view->setVar ('page_html', $page_html);
        $this->view->setVar ('page_num', $pageShow->page_count);
    }

    public function GetDefaultTime()
    {
        $res = [

        ];
        $time = NULL;

        $begin_date = $this->request->get('begin_date');
        $end_date = $this->request->get('end_date');
        list($time, $err) = \TimeService::NewTimeRange($begin_date, $end_date);
        if ($err === NULL) {
            return [$time, $err];
        }

        list($time, $err) = \TimeService::NewTime(
            date("Y-m-d", strtotime(date('Y-m-01')) - 10), "month");
//        if ($err !== NULL) {
//            return [$time, $err];
//        }
        return [$time, $err];

    }

}
