<?php

namespace Bc\App\Apps\ExampleApp\Api\Create;

use Bc\App\Apps\ExampleApp\Api\ExampleAppApiUser;
use Bc\App\Util;

class ExampleAppApiCreateUser extends ExampleAppApiUser {

    protected function init() {
        $this->gate();
        $this->handlePost();
        $this->triggerError(404);
    }

    protected function handlePost()
    {
        if ($this->method == 'POST') {
            $postBody = json_decode($this->bc->getQueryParamsString());

            $result = $this->addUser($postBody);
            $message = $result->success ? 'User is complete' : 'User not complete';

            $this->sendResponse($message, $result);
        }
    }

}
