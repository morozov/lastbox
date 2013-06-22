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

use Dropbox\Client;
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
     * @param Client $client Dropbox API client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
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
