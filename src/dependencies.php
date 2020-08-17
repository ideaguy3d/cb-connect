<?php declare(strict_types=1);

/**
 * ALL dependencies injected here are Singletons
 * http://www.slimframework.com/docs/v3/tutorial/first-app.html#add-dependencies
 */

use CodeBuddies\AppGlobals;
use Slim\Views\PhpRenderer;

$container = $app->getContainer();

// view renderer
$container['view'] = fn($c) => new PhpRenderer('templates/');

// monolog
$container['logger'] = function($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path']));
    return $logger;
};

// code buddies user
$container['codeUser'] = function($c) {
    return $c['settings']['codeUser'];
};

// old, NOT dry code >:/
if(!AppGlobals::isLocal()) {
    // The CloudSQL HackMatch.io db
    $container['dbProduction'] = function($c) {
        $dbSettings = $c['settings']['dbProduction'];
        $dbName = $dbSettings['db'];
        $host = $dbSettings['host'];
        $user = $dbSettings['user'];
        $pass = $dbSettings['pass'];
        
        $dsn = sprintf('mysql:dbname=%s;unix_socket=/cloudsql/%s',
            $dbName, $host);
        $dsnHost = sprintf('mysql:dbname=%s;host=%s', $dbName, $host);
        
        return new PDO($dsn, $user, $pass, [
            //PDO::ATTR_TIMEOUT => 5,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    };
}
else {
    // the localhost db
    $container['dbLocal'] = function($c) {
        $dbSettings = $c['settings']['dbLocal'];
        $dbName = $dbSettings['db'];
        $host = $dbSettings['host'];
        $user = $dbSettings['user'];
        $pass = $dbSettings['pass'];
        
        $dsnUnixSocket = sprintf('mysql:dbname=%s;unix_socket=/cloudsql/%s',
            $dbName, $host);
        $dsn = sprintf('mysql:dbname=%s;host=%s', $dbName, $host);
        
        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    };
}

function dbHackMatch($c, string $dbSetting = 'dbLocal', $cloud = false): PDO {
    $db = $c['settings'][$dbSetting];
    [$n, $h, $u, $p] = [$db['dbname'], $db['host'], $db['user'], $db['pass']];
    $x = 'mysql:dbname=%s;' . ($cloud ? 'unix_socket=/cloudsql/%s' : 'host=%s');
    return new PDO(sprintf($x, $n, $h), $u, $p, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}