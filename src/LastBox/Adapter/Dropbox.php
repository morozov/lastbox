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

use Dropbox\AccessToken;
use Dropbox\AccessType;
use Dropbox\AppInfo;
use Dropbox\Client;
use Dropbox\Config;
use Dropbox\WriteMode;

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
        $appInfo = new AppInfo(
            $consumerKey,
            $consumerSecret,
            AccessType::FullDropbox()
        );

        $accessToken = new AccessToken($token, $tokenSecret);

        $config = new Config($appInfo, 'LastBox');
        $this->client = new Client($config, $accessToken);
    }

    /**
     * Store data from the given stream
     *
     * @param resource $stream Data stream
     * @param string   $path   Path in Dropbox
     *
     * @return array Dropbox response
     */
    public function store($stream, $path)
    {
        return $this->client->uploadFile($path, WriteMode::add(), $stream);
    }
}
