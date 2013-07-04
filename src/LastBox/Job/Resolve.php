<?php
/**
 * Resolution of track URLs by means of VK API.
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

namespace LastBox\Job;

use LastBox\Job;
use LastBox\Adapter\Vk as Adapter;
use LastBox\Repository;

/**
 * Resolution of track URLs by means of VK API.
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
class Resolve implements Job
{
    /**
     * Last.FM adapter
     *
     * @var Adapter
     */
    protected $adapter;

    /**
     * Data repository
     *
     * @var Repository
     */
    protected $repository;

    /**
     * Constructor
     *
     * @param Adapter    $adapter    Last.FM adapter
     * @param Repository $repository Data repository
     */
    public function __construct(Adapter $adapter, Repository $repository)
    {
        $this->adapter = $adapter;
        $this->repository = $repository;
    }

    /**
     * Run job
     *
     * @return void
     */
    public function run()
    {
        $track = $this->repository->getTrackToResolve();
        if (is_array($track)) {
            $url = $this->adapter->getTrackUrl($track['artist'], $track['title']);
            if ($url) {
                $status = true;
            } else {
                $status = false;
                $url = null;
            }
            $this->adapter->setTrackUrl($track['id'], $status, $url);
        }
    }
}
