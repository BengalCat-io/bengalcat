<?php

namespace Bc\App\Core\RouteExtenders;

use Bc\App\Core\Util;
use Exception;

abstract class AppIndexRouteExtender extends DataRouteExtender {

    protected $app;
    protected $apiPath;
    protected $uri;
    protected $settings;
    protected $apiRoute;
    protected $portalRoute;

    protected $matchVars = [
        'create',
        'get',
        'update',
        'delete',
    ];

    protected function addMatchVar($var)
    {
        $this->matchVars = array_merge($this->matchVars, [$var]);
    }
    
    protected function addMatchVars($arr)
    {
        if (!empty($arr)) {
            foreach ($arr as $var) {
                $this->addMatchVar($var);
            }
        }
    }

    protected function isApiCall()
    {
        if (!preg_match("#{$this->settings->apiPath}#", $this->uri)) {
            return;
        }

        $this->setupApiCall();
        $this->buildApiRoute();

        // Bad because the class for this api route does not exist.
        if (!$this->apiRoute) {
            // No need to change error to a theme because api routes should return json
            $this->triggerError(
                404,
                "There are no matching defined routes in the {$this->settings->displayName} API."
            );
        }

        $this->bc->setRouteExtender($this->apiRoute, true);

        try {
            new $this->apiRoute(
                $this->bc,
                (object) [
                    'routeVars' => $this->routeVars,
                    'isGated'   => $this->isGatedRoute(),
                    'formData'  => $this->formData,
                ]
            );
        } catch (Exception $ex) {
            error_log(json_encode([
                'error' => 'new route error exception',
                'code' => $ex->getCode(),
                'message' => $ex->getMessage(),
                'trace' => $ex->getTraceAsString(),
            ]));
            $this->routeError();
        }

        exit();
    }

    protected function isPortalRoute()
    {
        // Determine Portal Route stuff
        $routeData = $this->bc->findMatchingRoute(
            $this->settings->portalRoutes,
            $this->bc->getRoute()
        );

        if (empty($routeData->routeExtenderPath)) {
            return;
        }

        $this->portalRoute = $routeData->routeExtenderPath;
        $this->bc->setRouteExtender($this->portalRoute, true);

        $this->variants = $this->routePieces;
        $this->buildRouteVars();

        try {
            new $this->portalRoute(
                $this->bc,
                (object) [
                    'routeVars' => $this->routeVars,
                    'variant' => $this->variant,
                    'variants' => $this->variants,
                    'isGated'   => $this->isGatedRoute(),
                ]
            );
        } catch (Exception $ex) {
            $this->routeError($this->settings->errorPortalRoute);
        }

        exit();
    }

    protected function isGatedRoute()
    {
        if (empty((array) $this->settings->gatedRoutes)) {
            return false;
        }

        $gatedRoutes = array_combine(
            $this->settings->gatedRoutes,
            $this->settings->gatedRoutes
        );

        $gated = $this->bc->findMatchingRoute(
            $gatedRoutes,
            $this->bc->getRoute()
        );

        $skipGatedRoutes = array_combine(
            $this->settings->skipGateRoutes,
            $this->settings->skipGateRoutes
        );

        $skipGated = $this->bc->findMatchingRoute(
            $skipGatedRoutes,
            $this->bc->getRoute()
        );

        return (
            !empty($gated->routeExtenderPath) && // is gated
            empty($skipGated->routeExtenderPath) // and not skipable
        );
    }

    protected function getDisplayController($displayType)
    {
        if (empty($this->settings)) {
            return false;
        }

        if (empty($this->settings->displays)) {
            return false;
        }

        if (empty($this->settings->displays->$displayType)) {
            return false;
        }

        if (empty($this->settings->displays->$displayType->controller)) {
            return false;
        }

        return $this->settings->displays->$displayType->controller;
    }

    protected function setupApiCall()
    {
        $variants = $this->variants;
        $this->variants = $this->routePieces;
        $this->buildRouteVars();
        $this->variants = $variants;

        $this->routeVars->route = (!empty($this->routeVars->route))
            ? $this->variants[1] : null;
    }

    protected function buildApiRoute()
    {
        if (
            empty($this->routeVars->{$this->routeVars->{$this->apiPathNameSpace}}) ||
            empty($this->routeVars->{$this->apiPathNameSpace})
        ) {
            $this->apiRoute = false;
            return;
        }

        $action = $this->routeVars->{$this->apiPathNameSpace};
        $object = $this->routeVars->$action;
        // Handle if the object has a hyphen: ie refresh-token -> RefreshToken
        $object = stristr($object, '-')
            ? str_replace(' ', '', ucwords((str_replace('-', ' ', $object))))
            : $object;

        $route = vsprintf($this->settings->apiRouteTemplate, [
            ucfirst($action),
            ucfirst($action),
            ucfirst($object),
        ]);

        $this->apiRoute = (class_exists($route) ? $route : false);
    }

    protected function routeError($errorRoute = '')
    {
        $errorRoute = !empty($errorRoute) ? $errorRoute : $this->bc->getSetting('errorRoute');
        $this->bc->changeSetting(
            'errorRoute',
            $errorRoute
        );

        $this->triggerError(
            404,
            "There are no defined routes matching this url in the {$this->settings->displayName}."
        );
    }

}