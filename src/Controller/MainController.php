<?php
namespace PriWare\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use PriWare\Controller\Controller;
use FastRoute;

class MainController extends Controller
{
    public function index(Request $request, Response $response, array $args)
    {
        $this->renderer->assign([
                'test' => '메인페이지입니다.',
        ]);
        
        $response->getBody()->write($this->renderer->display('index.tpl'));
        
        return $response;
    }
    
}
