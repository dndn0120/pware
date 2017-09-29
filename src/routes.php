<?php

use PriWare\Controller\MainController;

// Routes
/**
 * ※ route.php에 Global Navigation Bar 생성 로직 작성하기
 * 
 * - GNB에 표시할 route에 Route::setName()을 이용하여 route name을 지정한다.
 * 
 * - GNB에 표시할 route는
 *   1) 2depth까지만 가능
 *   2) GET route만 가능
 *   
 * - 현재 route가 아래 규칙을 따르는 named route인 경우
 *   해당 메뉴에 on class가 적용된다.
 *   ※ routes.php에서 hierarchy 및 순서가 맞지 않으면 제대로 작동하지 않는다.
 * 
 * - route name 규칙
 *   1) [] 안에 사용할 nav_name을 지정한다
 *   → Controller::getGnbFromRouter()에 해당 nav_name을 parameter로 넘겨서
 *      해당 nav_name이 붙은 route만 가져다 결과물을 생성함
 *   2) [] 뒤에 메뉴명을 지정한다.
 *   ※ 1depth nav에서 메뉴명을 비우면 표시되지 않는다. 
 *   
 * ※ Slim Framework 메뉴얼상의 route name 사용법대로 사용하기는 곤란하기 때문에
 *    향후 route name을 본래 용도로 사용하지 않는 것이 좋음.
 */
$app->get('/', MainController::class.':index');
