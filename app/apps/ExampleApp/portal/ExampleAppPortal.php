<?php

namespace Bc\App\Apps\ExampleApp\Portal;

use Bc\App\Apps\ExampleApp\Db\ExampleAppDbQueries;
use Bc\App\Apps\ExampleApp\Db\SetupExampleAppDbQueries;
use Bc\App\Apps\ExampleApp\ValidateExampleAppService;
use Bc\App\Core\RouteExtenders\PortalRouteExtender;
use Bc\App\Core\Util;
use Bc\App\Services\JwtService;
use Bc\App\Services\TimeAgoService;
use Exception;

abstract class ExampleAppPortal extends PortalRouteExtender {

    protected $body;
    protected $bodyWithSideBar;
    protected $sidebar;
    protected $itQueries;
    protected $uri;
    protected $curUsertimeZone;
    protected $curuser;

    public function getSettings()
    {
        return $this->settings;
    }

    protected function setTemplatePaths()
    {
        if (!defined('CSS_DIR')) {
            define('CSS_DIR', $this->getThemePart('assets/build/css/'));
            define('JS_DIR', $this->getThemePart('assets/build/js/'));
            define('IMAGE_DIR', $this->getThemePart('assets/build/img/'));
        }

        parent::setTemplatePaths();
    }

    protected function init()
    {
        $this->validateService = new ValidateExampleAppService($this);
//        $this->formatService   = new FormatListingMatcherService($this);
        $this->uri             = $_SERVER['REQUEST_URI'];

        $this->itQueries = new SetupExampleAppDbQueries(
                'image_tracker_no_db', $this->bc);
        $this->itQueries->setup();
        $this->itQueries = new ExampleAppDbQueries(
                'image_tracker', $this->bc);

        $this->gate();
    }

    protected function setupTimezoneAndTimeAgo()
    {
        // Get User's TimeZone
        $this->curuser = $this->getUserDataFromTokenOrRenderLogin(false);
        $this->curuserTimeZone = empty($this->curuser)
                ? $this->curuser->data->timezone
                : $this->bc->getSetting('timeZone', 'America/Los_Angeles');
        $this->timeService     = new TimeAgoService($this->curuserTimeZone);
    }

    protected function userHasPermission($permission)
    {
        if (empty($this->curuser)) {
            return false; // no user, so probably no permission...
        }

        return in_array(
                $permission,
                $this->curuser->data->permissions);
    }

    protected function getUserDataFromTokenOrRenderLogin($renderLoginOnFailure = true)
    {
        if (!$this->sessionKeyNotEmpty('token') && $renderLoginOnFailure) {
            $this->renderLogin(
                'Session token does not exist. Try logging in again?');
        }

        if (!$this->sessionKeyNotEmpty('token')) {
            return;
        }

        return JwtService::decode($this->getSessionKey('token'), null, false);
    }

    protected function doCustomInit() {
        parent::doCustomInit();

        $this->settings = $this->bc->getSetting('exampleApp');
        $this->bc->changeSetting(
            'errorRoute',
            $this->settings->errorPortalRoute
        );

        $this->setTheme($this->settings->theme);

        $this->data = (object) $this->data;
        $this->data->uri = $this->uri;

        $this->body = Util::getTemplateContents(
            $this->bc,
            $this->getThemePart('/src/tokenHTML/body.php')
        );
        $this->bodyWithSideBar = Util::getTemplateContents(
            $this->bc,
            $this->getThemePart('/src/tokenHTML/body-sidebar.php')
        );
        $this->sidebar = $this->getThemePartContents(
            '/src/tokenHTML/sidebars/sidebar.php', $this->data);
    }

    protected function checkAuth()
    {
        // Check session for user's token
        if (!$this->sessionKeyIsset('token')) {
            $this->sessionAuthIsEmpty = true;
            return;
        }

        // See if token is going to expire and renew it:
        $authData = $this->getUserDataFromTokenOrRenderLogin();

        if ((strtotime('now') > $authData->exp)) {
            // login again! token expired.
            $this->sessionAuthIsEmpty = true;
            return;
        }

        // if the token is halfway to expiration time, we can go get a new one.
        if ($authData->exp - strtotime('now') < ($authData->ttl / 2)) {
            $this->checkRefreshLogin($this->getSessionKey('token'));
        }

        // We made it.
        $this->sessionAuthIsEmpty = false;
    }

    protected function checkLogin()
    {
        // if login creds are empty, render the login
        if (
            $this->bc->isEmptyQueryParam('handle_key') ||
            $this->bc->isEmptyQueryParam('password_key')
        ) {
            $this->renderLogin();
        }

        // attempt login
        $handleKey   = $this->bc->getQueryParam('handle_key');
        $passwordKey = $this->bc->getQueryParam('password_key');
        $handle      = $this->bc->getQueryParam($handleKey);
        $password    = $this->bc->getQueryParam($passwordKey);

        $data = $this->login($handle, $password);

        // login failed
        if (!$data->success) {
            $this->renderLogin($data->message);
        }

        // login successful, add to the session
        $this->sessionAddKeyValue('username', $handle);
        $this->sessionAddKeyValue('token', $data->data->token);

        // person used the form to login, so avoid "resending" by refreshing:
        Util::redirectShortPath($this->uri);
    }

    protected function checkRefreshLogin($token)
    {
        $refreshData = $this->refreshToken($token);
        if (!$refreshData->success) {
            $this->renderLogin($refreshData->message);
        }

        // refresh successful, update the session token
        $this->sessionAddKeyValue('token', $refreshData->data->token);
    }

    protected function renderLogin($failedMessage = null)
    {

        $this->render(
            $this->getThemePart('/src/main/login.php'),
            $failedMessage,
            [
                '[:nav]' => $this->nav->getNav(true, $this->uri, ['/signup/']),
                '[:body]' => $this->body,
                '[:login]' => 'loginRendered',
            ]
        );
        exit();
    }

    protected function login($handle, $password)
    {
        $apiRequestUri = Util::getBasePath() . '/api/os/get/token/';

        $postBody = (object) [
            'username' => $handle,
            'password' => $password
        ];

        try {
            $response = Util::makeCurlCall(
                $apiRequestUri,
                json_encode($postBody),
                false,
                true
            );

            $data = json_decode($response);

        } catch (Exception $ex) {
            $this->triggerError(500, 'Could not connect to API to confirm auth.');
        }

        return $data;
    }

    protected function refreshToken($token)
    {
        $apiRequestUri = Util::getBasePath() . '/api/os/get/refresh/token/';

        try {
            $response = Util::makeCurlCall($apiRequestUri, [], true, true, [
                'Authorization' => $token
            ]);
            $data = json_decode($response);
        } catch (Exception $ex) {
            $this->triggerError(500, 'Could not connect to API to refresh auth.');
        }

        return $data;
    }

    public function getCurUser()
    {
        return $this->curuser;
    }

    public function getCurUserProp($prop)
    {
        if (!isset($this->curuser->$prop)) {
            return null;
        }

        return $this->curuser->$prop;
    }
}

