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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

require_once __DIR__ . '/../vendor/autoload.php';

$container = new ContainerBuilder();
$loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/..'));
$loader->load('services.xml');
$loader->load('config.xml');

$lastFM = $container->get('lastfm.adapter');
$vk = $container->get('vk.adapter');
$dropbox = $container->get('dropbox.adapter');

$tracks = $lastFM->getLovedTracks(
    $container->getParameter('lastfm.username')
);

while ($track = array_shift($tracks)) {
    echo sprintf("%s - %s", $track['artist']['name'], $track['name']), PHP_EOL;
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
