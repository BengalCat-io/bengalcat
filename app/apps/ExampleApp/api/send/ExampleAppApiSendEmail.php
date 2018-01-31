<?php

namespace Bc\App\Apps\ExampleApp\Api\Send;

use Bc\App\Apps\ExampleApp\Api\ExampleAppApiEmail;
use Bc\App\Apps\ExampleApp\ExampleAppUtil;
use Bc\App\Core\Util;
use Bc\App\Services\SendGridService\SendGridService;
use Bc\App\Services\SendGridService\SendGridServiceMail;

class ExampleAppApiSendEmail extends ExampleAppApiEmail {

    protected $message;
    protected $sendGrid;
    protected $mail;
    protected $data;
    
    protected function init() {
        $this->gate();
        $this->handlePost();
        $this->triggerError(404);
    }

    protected function handlePost()
    {
        if ($this->method == 'POST') {
            $postBody = json_decode($this->bc->getQueryParamsString());

            if (!isset($postBody->type)) {
                $this->triggerError(400);
            }

            $this->data = $postBody;
            $result     = $this->routeEmail();
            $message    = $result->success ? 'Email is sent' : 'Email is not sent';

            $this->sendResponse($message, $result);
        }
    }
    
    protected function routeEmail()
    {
        $validateBaseEmail = $this->validateEmailBase($this->data);
        
        if (!$validateBaseEmail->success) {
            $this->triggerError(400, $validateBaseEmail);
        }
        
        /** @note Gets method like sendVerifyEmail from verify email. */
        /** @note That method should exist on the parent class. */
        $method = ExampleAppUtil::slugToCamel('send-' . $this->data->type);
        if (!method_exists($this, $method)) {
            $this->triggerError(400);
        }
        
        $this->sendGrid = new SendGridService($this->settings->sendGridApiKey);
        $this->mail     = new SendGridServiceMail();
        
        return $this->{$method}();
    }
    
    protected function validateEmailBase($data)
    {
        return $this->validateService->validateData(
            $data,
            'SendEmail',
            [
                'email',
                'type',
                'siteUrl',
                'siteName',
                'name',
                'name_first',
                'name_last',
            ]
        );
    }

}
