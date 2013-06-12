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
$tracks = $lastFM->getLovedTracks($config['last.fm']['username']);
print_r($tracks);
