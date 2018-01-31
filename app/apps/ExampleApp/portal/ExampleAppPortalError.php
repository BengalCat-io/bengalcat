<?php

namespace Bc\App\Apps\ExampleApp\Portal;

class ExampleAppPortalError extends ExampleAppPortal {

    protected function init()
    {
        $this->render(
            $this->getThemePart('/src/main/error.php'),
            $this->routeData,
            [
                '[:nav]' => '',
                '[:body]' => $this->body,
            ]
        );
    }
}

