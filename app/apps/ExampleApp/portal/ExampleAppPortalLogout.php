<?php

namespace Bc\App\Apps\ExampleApp\Portal;

use Bc\App\Core\Util;

class ExampleAppPortalLogout extends ExampleAppPortal {

    protected function init()
    {
        $this->sessionStart();
        $this->sessionDestroy();
        Util::redirectShortPath('/');
    }
}