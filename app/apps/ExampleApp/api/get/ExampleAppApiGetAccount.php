<?php

namespace Bc\App\Apps\ExampleApp\Api\Get;

use Bc\App\Apps\ExampleApp\Api\ExampleAppApiAccount;
use Bc\App\Util;

class ExampleAppApiGetAccount extends ExampleAppApiAccount {

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
            $accountId = $this->routeData->routeVars->account;
            $account = current($this->getAccounts(['data' => $accountId]));

            if (!$account) {
                $this->triggerError(404, "Account not found.");
            }

            $formatted = $this->formatService->formatValueByKeyAndNameSpace(
                    'account', $account);

            $this->sendResponse('Account Found!', $formatted);
        }
    }

}
