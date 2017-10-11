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
        if($_SESSION['user_id']) {
            $response->getBody()->write($this->renderer->display('index.tpl'));
        } else {
            $response->getBody()->write($this->renderer->display('login/index.tpl'));
        }
        return $response;
    }
}
