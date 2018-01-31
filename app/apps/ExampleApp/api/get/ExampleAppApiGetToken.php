<?php

namespace Bc\App\Apps\ExampleApp\Api\Get;

use Bc\App\Apps\ExampleApp\Api\ExampleAppApi;
use Bc\App\Util;

class ExampleAppApiGetToken extends ExampleAppApi {

    protected function init() {
        $this->gate();
        $this->handlePost();
        $this->triggerError(404);
    }

    protected function handlePost() {
        if ($this->method == 'POST') {
            $postBody = json_decode($this->bc->getQueryParamsString());

            $this->sendResponse('Get - Post Token Things', $postBody);
        }
    }

}
