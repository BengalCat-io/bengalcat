<?php

namespace Bc\App\Apps\ExampleApp\Api\Get;

use Bc\App\Apps\ExampleApp\Api\ExampleAppApiAccount;
use Bc\App\Util;

class ExampleAppApiGetAccounts extends ExampleAppApiAccounts {

    protected function init() {
        $this->gate();
        $this->handlePost();
        $this->triggerError(404);
    }

    protected function handlePost() {
        if ($this->method == 'POST') {
            $postBody = json_decode($this->bc->getQueryParamsString());

            $accounts = $this->getAccounts($postBody);

            $formatted = $this->formatService->formatValueByKeyAndNameSpace(
                    'accounts', $accounts, '', ['password_hash']);

            $message = empty($users) ? 'No accounts found' : 'Accounts found';

            $this->sendResponse($message, $users);
        }
    }
}
