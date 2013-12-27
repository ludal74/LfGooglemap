<?php
return array(

    //----------------------------------------------
    // CONTROLLERS CONFIGURATION
    //-----------------------------------------------
    'controllers' => array(
        'invokables' => array(
            'Googlemap\Controller\Googlemap' => 'Googlemap\Controller\GooglemapController',
        ),
    ),
    
    
    //----------------------------------------------
    // ROUTER CONFIGURATION
    //-----------------------------------------------
    'router' => array(
        'routes' => array(
            'googlemap' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/googlemap',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Googlemap\Controller',
                        'controller'    => 'Googlemap',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    
    //----------------------------------------------
    // VIEW MANAGER CONFIGURATION
    //-----------------------------------------------
    'view_manager' => array(
        'template_path_stack' => array(
            'Googlemap' => __DIR__ . '/../view',
        ),
    ),
    
    //----------------------------------------------------------------------------------------------
    // GOOGLEMAP CONFIGURATION ( overriden in googlemap.local.php in your config autoload folder )
    //------------------------------------------------------------------------------------------------
    'googlemap' => array(   
        'account-type'		=> "free",                                              // ( free or business)
        'api-url'           => 'https://maps.googleapis.com/maps/api/js?v=3.exp',  // for free account only
    	'api-key'           => 'YOUR_API_KEY',                                     // for free account only
        'client-id'         => 'YOUR-CLIENT-ID',                                   // for business account only
        'cryptographic-key' => 'YOUR-CRYPTOGRAPHIC-KEY',                           // for business account only
        'sensor'            => "false",
        'libraries'         => array(
            'adsense',
            'drawing',
            'geometry',
            'panoramic',
            'places',
            'visualization',
            'weather',
        ),
        'map-type'          => 'ROADMAP',
    ),
    

    
    
    
);
