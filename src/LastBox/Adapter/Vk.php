<?php
/**
 * VK API adapter.
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

use VkPhpSdk;
use RuntimeException;

/**
 * VK API adapter.
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
class Vk
{
    /**
     * @var VkPhpSdk
     */
    protected $sdk;

    /**
     * Constructor
     *
     * @param string $accessToken VK API access token
     */
    public function __construct($accessToken)
    {
        $sdk = $this->sdk = new VkPhpSdk();
        $sdk->setAccessToken($accessToken);
    }

    /**
     * Retrieves track URL
     *
     * @param string $artist Track artist
     * @param string $title  Track title
     *
     * @return string|null Track URL or NULL if not found
     * @throws RuntimeException
     */
    public function getTrackUrl($artist, $title)
    {
        $result = $this->sdk->api(
            'audio.search',
            array(
                'q' => sprintf('%s - %s', $artist, $title),
                'count' => 2,
            )
        );

        if (!isset($result['response'])) {
            throw new RuntimeException('VK response is malformed');
        }

        $response = $result['response'];
        array_shift($response);
        $result = array_shift($response);

        if (!$result) {
            return null;
        }

        return $result['url'];
    }
}
