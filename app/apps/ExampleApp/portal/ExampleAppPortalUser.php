<?php

namespace Bc\App\Apps\ExampleApp\Portal;

class ExampleAppPortalUser extends ExampleAppPortal {

    protected function init()
    {
        parent::init();

        $this->render(
            $this->getThemePart('/src/main/account.php'),
            $this->routeData,
            [
                '[:body]'                   => $this->bodyWithSideBar,
                '[:has-sidebar body-class]' => 'has-sidebar',
                '[:sidebar]'                => $this->sidebar,
            ]
        );
    }
}

