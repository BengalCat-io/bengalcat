<?php

namespace Bc\App\Apps\ExampleApp\Api\Get;

use Bc\App\Apps\ExampleApp\Api\ExampleAppApi;
use Bc\App\Util;

class ExampleAppApiGetPasswordReset extends ExampleAppApi {

    protected function init() {
        $this->gate();
        $this->handlePost();
        $this->triggerError(404);
    }

    protected function handlePost() {
        if (
            $this->method == 'POST' &&
            !empty($this->routeData->routeVars->{'password-reset'})
        ) {
            $resetToken = $this->routeData->routeVars->{'password-reset'};
            $postBody = json_decode($this->bc->getQueryParamsString());


            $this->sendResponse('Get - Post Image Things for reset token id: ' . $resetToken, $postBody);
        }
    }

}
