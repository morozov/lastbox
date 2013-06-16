<?php
/**
 * Dropbox API adapter.
 *
 * PHP version 5
 *
 * @category  LastBox
 * @package   LastBox\Adapter
 * @author    Sergei Morozov <morozov@tut.by>
 * @copyright 2013 Sergei Morozov
 * @license   http://mit-license.org/ MIT Licence
 * @link      http://github.com/morozov/lastbox
 */

namespace LastBox\Adapter;

use LastBox\Adapter\Dropbox\Storage;
use Dropbox\OAuth\Consumer\Curl as Consumer;
use Dropbox\API as Api;

/**
 * Dropbox API adapter.
 *
 * PHP version 5
 *
 * @category  LastBox
 * @package   LastBox\Adapter
 * @author    Sergei Morozov <morozov@tut.by>
 * @copyright 2013 Sergei Morozov
 * @license   http://mit-license.org/ MIT Licence
 * @link      http://github.com/morozov/lastbox
 */
class Dropbox
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * Constructor
     * 
     * @param string $consumerKey    Consumer key
     * @param string $consumerSecret Consumer secret
     * @param string $token          Access token
     * @param string $tokenSecret    Access token secret
     */
    public function __construct($consumerKey, $consumerSecret, $token, $tokenSecret)
    {
        $storage = new Storage($token, $tokenSecret);

        $consumer = new Consumer($consumerKey, $consumerSecret, $storage);
        $this->api = new Api($consumer);
    }

    public function store($stream, $path)
    {
        return $this->api->putStream($stream, $path);
    }
}
