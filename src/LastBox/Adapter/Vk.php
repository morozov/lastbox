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
     * @param VkPhpSdk $sdk VK API SDK
     */
    public function __construct(VkPhpSdk $sdk)
    {
        $this->sdk = $sdk;
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

        if (!isset($result['response']) || !is_array($result['response'])) {
            throw new RuntimeException('Response is malformed');
        }

        $response = $result['response'];
        array_shift($response);
        $result = array_shift($response);

        if (!$result) {
            return null;
        }

        if (!isset($result['url'])) {
            throw new RuntimeException('Response doesn\'t contain URL');
        }

        return $result['url'];
    }
}
