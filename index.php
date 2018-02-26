<?php

require_once("vendor/autoload.php");

// Set up the base Fat-free class
$f3 = Base::instance();

// Set up the default configuration
$f3->config('config.ini');

// Set up the routes
$f3->config('routes.ini');

// Set up the messages
$f3->config('messages.ini');

// Create route for minifying JS and CSS
// Example: http://mysite.com/minify/js?files=myjs.js
$f3->route('GET /minify/*', function($f3, $args){
    // If there is nothing entered, return.
    $file = $f3->get('GET.files');
    if(empty($file)) {
        return;
    }

    // Get file type from input. If it is CSS, JS or an external library, it should correspond to hive key.
    $fileType = explode('/',$args[0])[2];
    if(strtolower($fileType) != "css" && (strtolower($fileType) != "js")) {
        return;
    }

    // Double check if the hive key exists.
    if(!$f3->exists(strtoupper($fileType))) {
        return;
    }

    // It is a hive key. Get file from corresponding folder.
    echo Web::instance()->minify($f3->get(strtoupper($fileType)) . $file);
}, 3600*24); // Javascript and CSS will be cached for 24 hours.

// Setup generic error page
$f3->set('ONERROR',
    function($f3) {
        // A curated list of movies that can be shown
        // on a error page
        $movieUrls = array(
            "https://www.youtube.com/embed/SFnMTHhKdkw",
            "https://www.youtube.com/embed/V-bjOJzB7LY",
            "https://www.youtube.com/embed/V7Z-Hq-xvxM",
            "https://www.youtube.com/embed/2Yt6raj-S1M",
            "https://www.youtube.com/embed/LNHBMFCzznE",
            "https://www.youtube.com/embed/y8qc8Aa3weE",
            "https://www.youtube.com/embed/NXhddUqNNjo",
            "https://www.youtube.com/embed/esPRsT-lmw8"
        );
        $f3->set('ERR', $movieUrls[rand (0, count($movieUrls)-1)]);
        $template = new Template();
        echo $template->render('error.htm');
    }
);

// Setting up the database for session management
$database = new DB\SQL(
    $f3->get('DB'),
    $f3->get('DBUSER'),
    $f3->get('DBPASSWORD'),
    array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION)
);

// Start a new session
new \DB\SQL\Session($database, 'sessions', TRUE, function($session){
    // Suspect session
    $logger = new \Log(\Logfile::SESSION);
    $f3=\Base::instance();
    if(($ip = $session->ip()) != $f3->get('IP'))
        $logger->write('user changed IP: ' . $ip);
    else
        $logger->write('user changed browser/device: ' . $f3->get('AGENT'));
    // The default behaviour destroys the suspicious session.
    return false;
});

// Copy token against csrf attacks
$f3->copy('CSRF','SESSION.csrf');

\Template::instance()->extend('pagebrowser','\Pagination::renderTag');

// Run the application
$f3->run();