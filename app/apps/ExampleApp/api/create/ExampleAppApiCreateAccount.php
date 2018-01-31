<?php

namespace Bc\App\Apps\ExampleApp\Api\Create;

use Bc\App\Apps\ExampleApp\Api\ExampleAppApiAccount;
use Bc\App\Util;

class ExampleAppApiCreateAccount extends ExampleAppApiAccount {

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
            $message = $result->success ? 'Account is complete' : 'Account not complete';

            $this->sendResponse($message, $result);
        }
    }

}
