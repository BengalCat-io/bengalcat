<?php

namespace Bc\App\Apps\ExampleApp\Api\Delete;

use Bc\App\Apps\ExampleApp\Api\ExampleAppApiUser;
use Bc\App\Util;

class ExampleAppApiDeleteUser extends ExampleAppApiUser {

    protected function init() {
        $this->gate();
        $this->handleDelete();
        $this->triggerError(404);
    }

    protected function handleDelete() {
        if ($this->method == 'DELETE') {
            $postBody = json_decode($this->bc->getQueryParamsString());
            
            $result   = $this->deleteUser($postBody);
            $message  = $result->success ? 'User is deleted' : 'User not deleted';

            $this->sendResponse($message);
        }
    }

}
