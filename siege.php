#!/usr/bin/env php
<?php
ini_set('error_reporting', E_ALL|E_STRICT);
ini_set('display_errors', true);

$targets = array(
    'symfony1Dev' => 'http://local.sf1.symfpony-project.org/api_dev.php/pony.json',
    'symfony1Prod' => 'http://local.sf1.symfpony-project.org/index.php/pony.json',
    'symfony1Cache' => 'http://local.sf1.symfpony-project.org/api_cache.php/pony.json',
    'Symfony2Dev' => 'http://local.sf2.symfpony-project.org/symfpony2_dev.php/pony.json',
    'Symfony2ProdXml' => 'http://local.sf2.symfpony-project.org/index.php/pony.xml',
    'Symfony2ProdJson' => 'http://local.sf2.symfpony-project.org/index.php/pony.json',
    'Symfony2Cache' => 'http://local.sf2.symfpony-project.org/symfpony2_cache.php/pony.json',
);

function write_siege_file($vars = array())
{
    // the base config vars
    $base = array (
        'verbose'           => 'false',
        // csv              => true,
        // fullurl          => true,
        // display-id       => '',
        'show-logfile'      => 'false',
        'logging'           => 'true',
        // 'logfile'           => '',
        'protocol'          => 'HTTP/1.0',
        'chunked'           => 'true',
        'connection'        => 'close',
        'concurrent'        => '10',
        'time'              => '10s',
        // reps             => '',
        // file             => '',
        // url              => '',
        // 'delay'             => '1',
        // timeout          => '',
        // expire-session   => '',
        // failures         => '',
        // 'internet'          => 'false',
        'benchmark'         => 'true',
        // user-agent       => '',
        // 'accept-encoding'   => 'gzip',
        'spinner'           => 'false',
        // login            => '',
        // username         => '',
        // password         => '',
        // ssl-cert         => '',
        // ssl-key          => '',
        // ssl-timeout      => '',
        // ssl-ciphers      => '',
        // login-url        => '',
        // proxy-host       => '',
        // proxy-port       => '',
        // proxy-login      => '',
        // follow-location  => '',
        // zero-data-ok     => '',
    );

    // make sure we have base vars for everything
    $vars = array_merge($base, $vars);

    // build the text for the file
    $text = '';
    foreach ($vars as $key => $val) {
        $text .= "$key = $val\n";
    }

    // write the siegerc file
    file_put_contents("/tmp/.siegerc", $text);
}

// store logs broken down by time
$time = date("Y-m-d\TH:i:s");
passthru("mkdir -p ./log/$time");

// run each benchmark target
foreach ($targets as $name => $path) {
    // write the siegerc file
    write_siege_file(array(
        'logfile' => "./log/$time/$name.log",
    ));

    // restart the server for a fresh environment
    passthru("/etc/init.d/apache2 restart");

    // prime the cache
    echo "$name: prime the cache\n";
    passthru("curl $path");
    echo "\n";

    // bench runs
    for ($i = 1; $i <= 3; $i++) {
        echo "$name: pass $i\n";
        passthru("siege --rc=/tmp/.siegerc $path");
        echo "\n";
    }
}

// do reporting
echo "Logs saved at ./log/$time.\n\n";
passthru("php ./report.php ./log/$time");
exit(0);