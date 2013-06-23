<?php

namespace LastBox;

use PDO;

class Repository
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getLastTrackDate()
    {
    }

    public function addTrack(array $track)
    {
    }

    public function getTrackToResolve()
    {
    }

    public function setTrackUrl($id, $status, $url)
    {
    }

    public function getTrackToUpload()
    {
    }

    public function setTrackUploadStatus($id, $status)
    {
    }
}
