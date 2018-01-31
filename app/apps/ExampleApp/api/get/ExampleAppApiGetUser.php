<?php

namespace Bc\App\Apps\ExampleApp\Api\Get;

use Bc\App\Apps\ExampleApp\Api\ExampleAppApiUser;
use Bc\App\Util;

class ExampleAppApiGetUser extends ExampleAppApiUser {

    protected function init() {
        $this->gate();
        $this->handleGet();
        $this->triggerError(404);
    }

    protected function handleGet() {
        if (
            $this->method == 'GET' &&
            !empty($this->routeData->routeVars->user)
        ) {
            $userId = $this->routeData->routeVars->user;
            $user = current($this->getUsers(['data' => $userId]));

            if (!$user) {
                $this->triggerError(404, "User not found.");
            }

            $formatted = $this->formatService->formatValueByKeyAndNameSpace(
                    'user', $user, ['password_hash']);

            $this->sendResponse('User Found!', $formatted);
        }
    }

}
