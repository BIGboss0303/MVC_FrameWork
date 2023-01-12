<?php
namespace App\Core;

use App\Core\Db\DataBase;
class Application
{
    public Router $router;
    public string $layout='main';
    public string $userClass;
    public Response $response;
    public Database $db;
    public ?Controller $controller=null;
    public Request $request;
    public Session $session;
    public ?UserModel $user;
    public View $view;
    public static string $ROOT_DIR;
    public static Application $app;
    
    public function __construct($rootpath, array $config)
    {
        self::$ROOT_DIR=$rootpath;
        
        $this->request= new Request();
        $this->response= new Response();
        $this->session= new Session();
        $this->view= new View();
        $this->router = new Router($this->request,$this->response);
        $this->db= new Database($config['db']);
        $this->userClass=$config['userClass'];
        
    }
    public function run($app)
    {
        self::$app=$app;
        $primaryKey=$this->userClass::primaryKey();
        $primaryValue=$this->session->get('user');
        if ($primaryValue){
            $primaryKey=$this->userClass::primaryKey();
            $this->user=$this->userClass::findOne([$primaryKey=>$primaryValue]);
        }
        else{
            $this->user=null;
        }
        try{
            echo $this->router->resolve();
        }catch(\Exception $e){
            $this->response->setStatusCode($e->getCode());
            echo $this->view->renderView('_error',['exception'=>$e]);
        }
        
        
    }
    public function migrations($app)
    {
        self::$app=$app;
        $this->db->applyMigrations();
        
    }

    public function getController(): Controller
    {
        return $this->controller;
    }
    public function setController(Controller $controller): void
    {
        $this->controller=$controller;
    }
    public function login(UserModel $user)
    {
        $this->user=$user;
        $primaryKey=$this->user->primaryKey();
        $primaryValue=$user->{$primaryKey};
        $this->session->set('user',$primaryValue);
        return true;
    }
    public function logout()
    {
        $this->user=null;
        $this->session->remove('user');
    }
    public static function isGuest(){
        return !self::$app->user;
    }
}