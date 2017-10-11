<?php
namespace PriWare\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use PriWare\Controller\Controller;
use PriWare\Model\Entity\User;
use PriWare\Model\Dao\UserDao;


class AuthController extends Controller
{
    public function loginProcess(Request $request, Response $response, array $args)
    {
        $userDao = $this->app->get(UserDao::class);
        
        $parameters = $request->getParsedBody();
        
        $user_id = $parameters['user_id'];
        $user_pw = $parameters['user_pw'];
        
        $authenticated = $userDao->authenticate($user_id, $user_pw);
        
        if($authenticated) {
            $_SESSION['user_info'] = $authenticated;
            //success
            $response->getBody()->write($this->renderer->display('index.tpl'));
        } else {
            //failed
            $response->getBody()->write($this->renderer->display('login/index.tpl'));
        }
        return $response;
    }
    
    public function logoutProcess(Request $request, Response $response, array $args)
    {
        unset($_SESSION['user_info']);
        $response->getBody()->write($this->renderer->display('login/index.tpl'));
        return $response;
    }
    
    public function userRegistration(Request $request, Response $response, array $args)
    {
        $userDao = $this->app->get(UserDao::class);
        //TODO parameters validation check
        $parameters = $request->getParsedBody();
        
        $user = new User([
            'id' => $parameters['user_id'],
            'name' => $parameters['name'],
            'password' => $parameters['password'],
            'type' => $parameters['type'],
            'status' => User::STATUS_READY,
            'regdate' => (new \DateTimeImmutable())->format('Y-m-d H:i:s')
        ]);
        
        $result = $userDao->createUser($user);
    }
}