<?php

namespace LastBox\Adapter\Dropbox;

use Dropbox\OAuth\Storage\StorageInterface;
use stdClass;

class Storage implements StorageInterface
{
    protected $token;
    
    public function __construct($access_token, $access_token_secret)
    {
        $this->token = new stdClass();
        $this->token->access_token = $access_token;
        $this->token->access_token_secret = $access_token_secret;
    }

    /**
     * Get a token by type
     * @param string $type Token type to retrieve
     * @return stdClass
     */
    public function get($type)
    {
        return $this->token;
    }

    /**
     * Set a token by type
     * @param stdClass $token Token object to set
     * @param string $type Token type
     */
    public function set($token, $type)
    {
    }

    /**
     * Delete tokens for the current session/user
     */
    public function delete()
    {
    }
}
