<?php
namespace PriWare\Controller;

use Interop\Container\ContainerInterface;

abstract class Controller
{
    public $app;
    public $auth;
    public $dao;
    public $renderer;
    
    public function __construct(ContainerInterface $app)
    {
        $this->app = $app;
        $this->renderer = $app->renderer;
        
        if (substr($app->request->getUri()->getPath(), 0, 4) == '/api') {
            $this->logger->info(__METHOD__.' : SSO canceled on API call');
            $assigns = [
                'id_tag' => '',
                'check_tag' =>  '',
                'hidden_tag' => '',
                'is_login' => false,
                'login_proc_url' => '#',
                'find_id_url' => '#',
                'find_pw_url' => '#',
                'join_url' => '#',
                'modify_url' => '#',
                'logout_url' => '#'
            ];
        } elseif (substr($app->request->getUri()->getPath(), 0, 14) == '/common_return') {
            // kcp 입금확인에 대해 SSO 우회
            $this->logger->info(__METHOD__.' : SSO canceled on KCP common return');
            $assigns = [
                'id_tag' => '',
                'check_tag' =>  '',
                'hidden_tag' => '',
                'is_login' => false,
                'login_proc_url' => '#',
                'find_id_url' => '#',
                'find_pw_url' => '#',
                'join_url' => '#',
                'modify_url' => '#',
                'logout_url' => '#'
            ];
        } elseif (substr($app->request->getUri()->getPath(), 0, 9) == '/reseller') {
            // 리셀러페이지에 대해 SSO 우회
            $this->logger->info(__METHOD__.' : SSO canceled on reseller');
            $assigns = [
                'id_tag' => '',
                'check_tag' =>  '',
                'hidden_tag' => '',
                'is_login' => false,
                'login_proc_url' => '#',
                'find_id_url' => '#',
                'find_pw_url' => '#',
                'join_url' => '#',
                'modify_url' => '#',
                'logout_url' => '#'
            ];
        } elseif (in_array($_SERVER['REMOTE_ADDR'], ['::1'])) {
            // localhost에서 sso 우회
            $assigns = [
                'id_tag' => '',
                'check_tag' =>  '',
                'hidden_tag' => '',
                'is_login' => false,
                'login_proc_url' => '#',
                'find_id_url' => '#',
                'find_pw_url' => '#',
                'join_url' => '#',
                'modify_url' => '#',
                'logout_url' => '#'
            ];
        } else {
            $this->auth = $app->get('auth');
            list($id_tag, $check_tag) = $this->auth->getLoginTag();
            $hidden_tag = $this->auth->getLoginHiddenTag();
            
            // 회원가입 페이지 URL (member.whois.co.kr)
            $join_url = $this->auth->getGatewayUrl('join_url');
            // 회원가입 완료 후 돌아올 URL 기본값(whoisg.net 메인페이지)
            $return_url = $this->auth->arrWhoisSSOInfo['agent']['main_url'];
            // 회원가입 페이지 URL에 return_url parameter 추가
            $d = strpos($join_url, '?') ? '&': '?';
            $join_url = preg_replace("/return_url=[^&]*/", "return_url={$return_url}", $join_url, -1, $replaced);
            if (!$replaced) {
                $join_url = "{$join_url}{$d}return_url={$return_url}";
            }
            
            $assigns = [
                'id_tag' => $id_tag,
                'check_tag' =>  $check_tag,
                'hidden_tag' => $hidden_tag,
                'is_login' => $this->auth->isLogin(),
                'login_proc_url' => $this->auth->getGatewayUrl('login_proc_url'),
                'find_id_url' => $this->auth->getGatewayUrl('find_id_url'),
                'find_pw_url' => $this->auth->getGatewayUrl('find_pw_url'),
                'join_url' => $join_url,
                'modify_url' => $this->auth->getGatewayUrl('modify_url'),
                'logout_url' => $this->auth->getGatewayUrl('logout_url')
            ];
        }
        $this->renderer->assign($assigns);
        
        // GNB
        $this->renderer->assign([
            'gnb' => $gnb
        ]);
        
        // Google 애널리틱스
        $this->renderer->assign('google_analytics_id', $this->app->settings['google_analytics_id']);
    }
    
    /**
     * 자식class에서 const BASE_INDEX_URL, const INDEX_URL_FIELDS를 정의하여 사용
     * @return string
     */
    protected function getIndexUrlFromFlash()
    {
        $query_string = $this->flash->getMessage('index_query_string')[0];
        
        $index_url = static::BASE_INDEX_URL.($query_string ? '?'.$query_string : '');
        
        return $index_url;
    }
    
    /**
     * Global Navigation Bar를 route.php의 정보를 이용하여 render하는 로직
     * 상세 로직은 src/route.php의 주석을 참고
     * @param string $nav_name
     * @return StdClass
     */
    protected function getGnbFromRouter(string $nav_name)
    {
        $menutree = [];
        foreach ($this->app->router->getRoutes() as $route) {
            if (
                count($route->getMethods()) == 1
                && $route->getMethods()[0] == 'GET'
                && preg_match("/^\[{$nav_name}\](.*)$/", $route->getName(), $matches)
            ) {
                $menu = (object)[
                    'path' => $route->getPattern(),
                    'name' => $matches[1],
                ];
                $steps = array_values(array_filter(explode('/', $menu->path)));
                if (count($steps) == 1) {
                    $menu->submenus = [];
                    $menutree[$steps[0]] = $menu;
                } else {
                    $menutree[$steps[0]]->submenus[$steps[1]]= $menu;
                }
            }
        }
        $current_menu_steps = explode('/', $this->app->request->getUri()->getPath());
        $current_menu_steps = array_filter($current_menu_steps);
        $current_menu_steps = array_values($current_menu_steps);
        $current_menu_steps = array_slice($current_menu_steps, 0, 2);
        $current_menu = $current_menu_steps[0];
        $current_submenu =  $current_menu_steps[1];
        if (!$current_submenu) {
            $current_submenu = array_keys($menutree[$current_menu]->submenus)[0];
        }
        return (object)[
            'current_menu' => $current_menu,
            'current_submenu' => $current_submenu,
            'menus' => $menutree,
        ];
    }
}
