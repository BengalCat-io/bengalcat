<?php

namespace Bc\App\Apps\ExampleApp\Portal;

class ExampleAppPortalSearch extends ExampleAppPortal {

    protected function init()
    {
        parent::init();

        $this->sidebar = $this->getThemePartContents(
            '/src/tokenHTML/sidebars/photo.php', $this->data);

        $this->render(
            $this->getThemePart('/src/main/photos.php'),
            $this->routeData,
            [
                '[:body]'                   => $this->bodyWithSideBar,
                '[:has-sidebar body-class]' => 'has-sidebar',
                '[:sidebar]'                => $this->sidebar,
                '[:upload form]' => $this->getThemePartContents(
                        '/src/tokenHTML/forms/photo.php', $this->data),
            ]
        );
    }
}

