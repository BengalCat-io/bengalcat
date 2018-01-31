<?php

namespace Bc\App\Apps\ExampleApp\Api\Get;

use Bc\App\Apps\ExampleApp\Api\ExampleAppApiUser;
use Bc\App\Util;

class ExampleAppApiGetUsers extends ExampleAppApiUser {

    protected function init() {
        $this->gate();
        $this->handlePost();
        $this->triggerError(404);
    }

    protected function handlePost() {
        if ($this->method == 'POST') {
            $postBody = json_decode($this->bc->getQueryParamsString());

            $users = $this->getUsers($postBody);

            $formatted = $this->formatService->formatValueByKeyAndNameSpace(
                    'users', $users, '', ['password_hash']);

            $message = empty($users) ? 'No users found' : 'Users found';

            $this->sendResponse($message, $users);
        }
    }
}
