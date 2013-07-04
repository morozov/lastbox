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
use LastBox\Adapter\Dropbox as Adapter;
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
class Upload implements Job
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
        $track = $this->repository->getTrackToUpload();
        if (is_array($track)) {
            $stream = fopen($track['url']);
            if ($stream) {
                $filename = $this->getFilename($track);
                $this->adapter->store($stream, $filename);
                $this->repository->setTrackUploadStatus($track['id'], true);
            }
        }
    }

    /**
     * Get filename of specified track
     *
     * @param array $track Track parameters
     *
     * @return string Filename
     */
    protected function getFilename(array $track)
    {
        $filename = sprintf(
            '%03d. %s - %s',
            $track['number'],
            $track['artist'],
            $track['title']
        );

        // remove UNC reserver characters
        $filename = strtr($str, '<>:"/\|?*', '---------');

        return $filename;
    }
}
