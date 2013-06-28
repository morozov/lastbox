<?php

/**
 * @covers LastBox\Adapter\Dropbox
 */
class DropboxTest extends PHPUnit_Framework_TestCase
{
    public function testClientInvocation()
    {
        $stream = tmpfile();
        $path = '/path/to/saved/file';

        $client = $this->getMockBuilder('Dropbox\Client')
            ->disableOriginalConstructor()
            ->setMethods(array('uploadFile'))
            ->getMock();
        $client->expects($this->once())
            ->method('uploadFile')
            ->with(
                $path,
                $this->isInstanceOf('Dropbox\WriteMode'),
                $stream
            );

        /** @var Dropbox\Client $client */
        $adapter = new LastBox\Adapter\Dropbox($client);
        $adapter->store($stream, $path);
    }
}
