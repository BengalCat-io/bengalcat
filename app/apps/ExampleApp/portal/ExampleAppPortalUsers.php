<?php

namespace Bc\App\Apps\ExampleApp\Portal;

class ExampleAppPortalUsers extends ExampleAppPortal {

    protected function init()
    {
        parent::init();

        $this->render(
            $this->getThemePart('/src/main/accounts.php'),
            $this->routeData,
            [
                '[:body]' => $this->body,
            ]
        );
    }
}

