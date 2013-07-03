<?php
/**
 * Synchronization of Last.FM favorites with repository.
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
use LastBox\Adapter\LastFM as Adapter;
use LastBox\Repository;

/**
 * Synchronization of Last.FM favorites with repository.
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
class Synchronize implements Job
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
     * Last.FM username
     *
     * @var string
     */
    protected $username;

    /**
     * The number of results to fetch per page
     *
     * @var int
     */
    protected $limit = 50;

    /**
     * Constructor
     *
     * @param Adapter    $adapter    Last.FM adapter
     * @param string     $username   Last.FM username
     * @param Repository $repository Data repository
     */
    public function __construct(Adapter $adapter, $username, Repository $repository)
    {
        $this->adapter = $adapter;
        $this->username = $username;
        $this->repository = $repository;
    }

    /**
     * Run job
     *
     * @return void
     */
    public function run()
    {
        $lastDate = $this->repository->getLastTrackDate();
        $lastTracks = array();

        $page = 1;
        do {
            $tracks = $this->adapter->getLovedTracks(
                $this->username,
                $this->limit,
                $page++
            );

            if ($tracks == $lastTracks) {
                break;
            }

            $lastTracks = $tracks;

            $needMore = $this->import($tracks, $lastDate);
        } while ($tracks && $needMore);
    }

    /**
     * Imports specified tracks into repository
     *
     * @param array $tracks   Favorite tracks
     * @param int   $lastDate Date of the most recent track in repository
     *
     * @return bool           Whether more tracks is needed to be retrieved
     */
    protected function import(array $tracks, $lastDate)
    {
        foreach ($tracks as $track) {
            if ($track['date'] > $lastDate) {
                $track = $this->extract($track);
                $this->repository->addTrack($track);
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * Extracts the needed elements from array retrieved from Last.FM API
     *
     * @param array $track Original array
     *
     * @return array Array accepted by repository
     */
    protected function extract(array $track)
    {
        return array(
            'mbid' => $track['mbid'],
            'artist' => $track['artist']['name'],
            'title' => $track['name'],
            'date' => $track['date'],
        );
    }
}
