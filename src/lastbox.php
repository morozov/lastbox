<?php
/**
 * Last.FM to Dropbox downloader.
 *
 * PHP version 5
 *
 * @category  LastBox
 * @package   LastBox
 * @author    Sergei Morozov <morozov@tut.by>
 * @copyright 2013 Sergei Morozov
 * @license   http://mit-license.org/ MIT Licence
 * @link      http://github.com/morozov/lastbox
 */

require_once __DIR__ . '/../vendor/autoload.php';

if (is_file(__DIR__ . '/../config.php')) {
    $config = include __DIR__ . '/../config.php';
} else {
    $config = include __DIR__ . '/../config.php.dist';
}

$lastFM = new \LastBox\Adapter\LastFM($config['last.fm']['api_key']);
$vk = new \LastBox\Adapter\Vk($config['vk']['access_token']);
$dropbox = new \LastBox\Adapter\Dropbox(
    $config['dropbox']['api_key'],
    $config['dropbox']['api_secret'],
    $config['dropbox']['access_token'],
    $config['dropbox']['access_token_secret']
);

$tracks = $lastFM->getLovedTracks($config['last.fm']['username']);
print_r($tracks);

while ($track = array_shift($tracks)) {
    $url = $vk->getTrackUrl($track['artist']['name'], $track['name']);
    if ($url) {
        echo $url, PHP_EOL;
        $stream = fopen($url, 'rb');
        if ($stream) {
            $path = '/Lastbox/' . basename($url);
            $result = $dropbox->store($stream, $path);
            print_r($result);
        }
        break;
    }
}
