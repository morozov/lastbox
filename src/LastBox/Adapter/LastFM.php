<?php
/**
 * Last.FM API adapter.
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

use lastfmApi;
use lastfmApiAuth;
use lastfmApiUser;
use RuntimeException;

/**
 * Last.FM API adapter.
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
class LastFM
{
    /**
     * @var \lastfmApiUser
     */
    protected $userApi;

    /**
     * Constructor
     *
     * @param lastfmApi     $api     Last.FM API
     * @param lastfmApiAuth $authApi Last.FM authentication API
     *
     * @throws RuntimeException
     */
    public function __construct(lastfmApi $api, lastfmApiAuth $authApi)
    {
        $this->userApi = $this->call(
            function (lastfmApi $api) use ($authApi) {
                return $api->getPackage($authApi, 'user');
            },
            $api
        );
    }

    /**
     * Retrieves loved tracks of the given user
     *
     * @param string $username Username
     *
     * @return array           Loved tracks
     * @throws RuntimeException
     */
    public function getLovedTracks($username)
    {
        $api = $this->userApi;
        return $this->call(
            function (lastfmApiUser $api) use ($username) {
                return $api->getLovedTracks(
                    array(
                        'user' => $username,
                    )
                );
            },
            $api
        );
    }

    /**
     * Calls the given function and handles the result. Throw exception in case
     * if error occurred
     *
     * @param \Closure  $function Function to call
     * @param lastfmApi $api      The API object to retrieve error from
     *
     * @return array
     * @throws RuntimeException
     */
    protected function call(\Closure $function, lastfmApi $api)
    {
        $result = call_user_func($function, $api);

        if (!$result) {
            $error = $api->error;
            throw new RuntimeException($error['desc'], $error['code']);
        }

        return $result;
    }
}
