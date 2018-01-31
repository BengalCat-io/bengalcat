<?php

return [
    
    /* Custom View Controllers */
    '/' => '\Bc\App\Controllers\Example\View\Installed', // Change to Home for practice.
    
    /* Apps (and their APIs) */
    
        // Admin
        '/api/admin/[^/]*/route(/.*)' => '\Bc\App\Core\Apps\Admin\AdminIndex',
        '/admin/(.*)' => '\Bc\App\Core\Apps\Admin\AdminIndex',

        // CMS
        '/api/cms/[^/]*/route(/.*)' => '\Bc\App\Core\Apps\Cms\CmsIndex',
        '/portal/cms/(.*)' => '\Bc\App\Core\Apps\Cms\CmsIndex',
        '(/.*)' => '\Bc\App\Core\Apps\Cms\CmsIndex', // catch all, put this last
    
    /* An example app */
//    '(/.*)' => '\Bc\App\Apps\ExampleApp\ExampleAppIndex', // catch all, put this last
    
];