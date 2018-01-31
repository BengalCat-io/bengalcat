<?php

namespace Bc\App\Apps\ExampleApp;

use Bc\App\Core\RouteExtenders\AppIndexRouteExtender;
use Bc\App\Core\Util;
use Exception;

class ExampleAppIndex extends AppIndexRouteExtender {

    protected function init()
    {
        $this->settings         = $this->bc->getSetting('exampleApp');
        $this->app              = $this->settings->appName;
        $this->apiPathNameSpace = $this->settings->apiPathNameSpace;
        $this->uri              = $_SERVER['REQUEST_URI'];
        
        $this->addMatchVars($this->settings->pathMatchVars);
        // $this->addMatchVar('custom');
        
        // Check settings to see the portal routes
        $this->isApiCall();
        $this->isPortalRoute();
        // Basically, if no routes, 404
        $this->routeError($this->settings->errorPortalRoute);
    }
    
    

}

