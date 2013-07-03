<?php

namespace LastBox;

use PDO;

class Repository
{
    /**
     * @var PDO
     */
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getLastTrackDate()
    {
        $sql = <<<SQL
SELECT MAX(`date`) FROM `track`
SQL;
        $stmt = $this->db->query($sql);
        $date = $stmt->fetch(PDO::FETCH_COLUMN, 0);

        return $date;
    }

    public function addTrack(array $track)
    {
        $sql = <<<SQL
INSERT INTO
    `track` (`id`, `artist`, `title`, `date`)
VALUES (:id, :artist, :title, :date)
SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $this->pack($track['id']));
        $stmt->bindValue(':artist', $track['artist']);
        $stmt->bindValue(':title', $track['title']);
        $stmt->bindValue(':date', $track['date'], PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getTrackToResolve()
    {
        $sql = <<<SQL
SELECT
    `id`, `artist`, `title`
FROM
    `track`
WHERE
    `is_resolved` = 0
ORDER BY
    `date`
LIMIT
    1
SQL;
        $stmt = $this->db->query($sql);
        $track = $stmt->fetch(PDO::FETCH_ASSOC);

        return $track;
    }

    public function setTrackUrl($id, $status, $url)
    {
        $sql = <<<SQL
UPDATE
    `track`
SET
    `is_resolved` = :status,
    `url` = :url
WHERE
    id = :id
SQL;
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $this->pack($id));
        $stmt->bindValue(':status', $status, PDO::PARAM_INT);
        $stmt->bindValue(':url', $url);
        $stmt->execute();
    }

    public function getTrackToUpload()
    {
        $sql = <<<SQL
SELECT
    `id`, `url`
FROM
    `track`
WHERE
    `is_uploaded` = 0
ORDER BY
    `date`
LIMIT
    1
SQL;
        $stmt = $this->db->query($sql);
        $track = $stmt->fetch(PDO::FETCH_ASSOC);

        return $track;
    }

    public function setTrackUploadStatus($id, $status)
    {
        $sql = <<<SQL
UPDATE
    `track`
SET
    `is_uploaded` = :status
WHERE
    id = :id
SQL;
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $this->pack($id));
        $stmt->bindValue(':status', $status, PDO::PARAM_INT);
        $stmt->execute();
    }

    protected function pack($id)
    {
        return pack(
            'h*',
            str_replace('-', '', $id)
        );
    }
}
