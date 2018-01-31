<?php

/*
 * Version-Controlled default settings go here.
 * Create settings.php (is in .gitignore) to add to or override default settings.
 */

return [
    'defaultTheme'         => 'bengalcat',
    'timeZone'             => 'America/Los_Angeles',
    'errorRoute'           => '\Bc\App\Controllers\Example\View\Error',
    'errorJsonIdentifiers' => [
        '/api/'
    ],
    'navRenderPath'        => THEMES_DIR . 'bengalcat/src/tokenHTML/nav.php',
    'navActiveClass'       => 'bc-active',
    'navItems'             => [
        'docs'     => [
            'attributes'        => [
                'href' => '/docs/',
            ],
//            'fontawesomeIcon' => 'copy',
            'display'           => 'Docs',
            'matchingRoutePath' => '/docs/'
        ],
        'about'    => [
            'attributes'        => [
                'href' => '/about/',
            ],
//            'fontawesomeIcon'   => 'question',
            'display'           => 'About',
            'matchingRoutePath' => '/about/'
        ],
        'download' => [
            'attributes'      => [
                'href' => 'https://github.com/amurrell/bengalcat/',
            ],
            'fontawesomeIcon' => 'download',
            'display'         => 'Download',
        ],
    ],
    'cms'                  => [
        'appName'          => 'cms',
        'displayName'      => 'CMS',
        'errorPortalRoute' => '\Bc\App\Core\Apps\Cms\Portal\CmsPortalError',
        'apiPath'          => '/api/cms/',
        'apiRouteTemplate' => 'Bc\App\Core\Apps\Cms\Api\%s\CmsApi%s%s',
        'theme'            => 'admin',
        'displays'         => [
            'docs' => ['controller' => '\Bc\App\Controllers\Example\View\Docs'],
            'doc'  => ['controller' => '\Bc\App\Controllers\Example\View\Doc'],
        ],
        'portalRoutes'     => [
            '/portal/cms/' => '\Bc\App\Core\Apps\Cms\Portal\CmsPortalRouteTypes',
        ],
        'gatedRoutes'      => [
            '/portal/cms/',
        ]
    ],
    'admin'                => [
        'appName'          => 'admin',
        'displayName'      => 'Admin',
        'errorPortalRoute' => '\Bc\App\Core\Apps\Admin\Portal\AdminPortalError',
        'apiPath'          => '/api/admin/',
        'apiRouteTemplate' => 'Bc\App\Core\Apps\Admin\Api\%s\AdminApi%s%s',
        'theme'            => 'admin',
        'portalRoutes'     => [
            '/admin/'       => '\Bc\App\Core\Apps\Admin\Portal\AdminPortalApps',
        ],
        'gatedRoutes'      => [
            '/admin/(.*)',
        ]
    ],
    'exampleApp'         => [
        'appName'               => 'exampleApp',
        'displayName'           => 'Example App',
        'errorPortalRoute'      => '\Bc\App\Apps\ExampleApp\Portal\ExampleAppPortalError',
        'apiToken'              => 'bror7YrxtBJAGX5oSWc4qncX6ZUPk4LxSGNZiV2kfsrFmWfgRFxJYhJytMZc',
        'apiPathNameSpace'      => 'bc',
        'apiPath'               => '/api/bc/',
        'apiRouteTemplate'      => 'Bc\App\Apps\ExampleApp\Api\%s\ExampleAppApi%s%s',
        'permissions'           => [
            "login",
            "user-view-own",
            "user-view-all",
            "user-edit-own",
            "user-edit-all",
            "user-delete-own",
            "user-delete-all",
            "user-approve",
            "user-deny",
            "user-grant" // for permissions
        ],
        "permissionDefaults"    => [ // default is none if not given "login"
            "login",
            "user-view-all",
            "user-edit-own",
        ],
        'permissionGroups' => [
            'admin' => [
                "login",
                "user-view-own",
                "user-view-all",
                "user-edit-own",
                "user-edit-all",
                "user-delete-own",
                "user-delete-all",
                "user-approve",
                "user-deny",
                "user-grant" // for permissions
            ],
            'basic' => [
                "login"
            ],
            'user' => [
                "login",
                "user-view-own",
                "user-edit-own",
                "user-delete-own",
            ],
            'moderator' => [
                "user-view-own",
                "user-view-all",
                "user-edit-own",
                "user-edit-all",
                "user-delete-own",
                "user-approve",
                "user-deny",
                "user-grant" // for permissions
            ]
        ],
        'ttlDefault'            => '600',
        'theme'                 => 'example-app',
        'portalRoutes'          => [
            '/'               => '\Bc\App\Apps\ExampleApp\Portal\ExampleAppPortalExample',
            '/signup/'        => '\Bc\App\Apps\ExampleApp\Portal\ExampleAppPortalSignUp',
            '/users/'         => '\Bc\App\Apps\ExampleApp\Portal\ExampleAppPortalUsers',
            '/user/me/'       => '\Bc\App\Apps\ExampleApp\Portal\ExampleAppPortalUser',
            '/user/([^/]*)/'  => '\Bc\App\Apps\ExampleApp\Portal\ExampleAppPortalUser',
            '/logout/'        => '\Bc\App\Apps\ExampleApp\Portal\ExampleAppPortalLogout',
        ],
        'pathMatchVars'         => [
            'bc',
            'password-reset',
            'user',
            'users',
            'signup',
            'me',
            'logout',
            'verify-email',
        ],
        'gatedRoutes'           => [
            '/(.*)',
        ],
        'skipGateRoutes'        => [
            '^/signup/'
        ],
        'adminEmail'          => 'amasiell.g@gmail.com',
        'exampleAppEmail'     => 'amasiell.g@gmail.com',
        'exampleAppEmailName' => 'Example App',
        'sendGridApiKey'      => 'PASTE YOUR SENDGRID KEY',
    ]
];

