<?php

namespace Bc\App\Apps\ExampleApp\Api;

use Bc\App\Apps\ExampleApp\Db\ExampleAppDbQueries;
use Bc\App\Apps\ExampleApp\Db\SetupExampleAppDbQueries;
use Bc\App\Apps\ExampleApp\FormatExampleAppService;
use Bc\App\Apps\ExampleApp\ValidateExampleAppService;
use Bc\App\Core\RouteExtenders\CrudRouteExtender;
use Bc\App\Core\Util;
use Bc\App\Services\JWTService;
use Exception;
use function getallheaders;

abstract class ExampleAppApi extends CrudRouteExtender {

    protected $headers;
    protected $api_key;
    protected $tokens;
    protected $data;
    protected $curuser;
    protected $itQueries;

    public function getSettings()
    {
        return $this->settings;
    }

    protected function doCustomInit()
    {
        parent::doCustomInit();

        $this->settings = $this->bc->getSetting('exampleApp');
        $this->api_key  = $this->settings->apiToken;
        $this->uri      = $_SERVER['REQUEST_URI'];
        $this->headers  = getallheaders();

    }

    protected function loadServices()
    {
        parent::loadServices();

        $this->validateService = new ValidateExampleAppService($this);
        $this->formatService   = new FormatExampleAppService($this);
    }

    protected function loadQueries()
    {
        parent::loadQueries();

        $this->itQueries = new SetupExampleAppDbQueries(
                'image_tracker_no_db', $this->bc);
        $this->itQueries->setup();
        $this->itQueries = new ExampleAppDbQueries(
                'image_tracker', $this->bc);
    }

    protected function gate()
    {
        $adminAuth = $this->headers['Authorization'] == $this->api_key;

        if (!$adminAuth) {
            // User Auth is needed
            $this->curuser = $this->getCurUserByAuthToken(
                    $this->headers['Authorization']);
        }

        if (!$adminAuth && !$this->curuser) {
            $this->triggerError(403, 'Access Denied');
        }
    }


    protected function getCurUserByAuthToken($token)
    {
        try {
            $tokenData = JWTService::decode($token, null, false);
        } catch (Exception $ex) {
            Util::errorLog(['message' => $ex->getMessage()], 400, 'Bad JWT Token');
            $tokenData = false;
        }

        // will have to figure out auth of this token now.

        return false;
    }
    
    protected function sendEmail($data)
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

}
