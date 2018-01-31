<?php

namespace Bc\App\Apps\ExampleApp\Api\Update;

use Bc\App\Apps\ExampleApp\Api\ExampleAppApi;
use Bc\App\Util;

class ExampleAppApiUpdateUser extends ExampleAppApi {

    protected function init() {
        $this->gate();
        $this->handlePost();
        $this->triggerError(404);
    }

    protected function handlePost() {
        if ($this->method == 'POST') {
            $postBody = json_decode($this->bc->getQueryParamsString());

            $this->sendResponse('Get - Post Images Things: ', $postBody);
        }
    }

}
