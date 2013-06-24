<?php

/**
 * @covers LastBox\Adapter\Vk
 */
class VkTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException RuntimeException
     */
    public function testMalformedResponse1()
    {
        $this->call(null);
    }

    public function testNoResults()
    {
        $result = $this->call(
            array(
                'response' => array(),
            )
        );
        $this->assertNull($result);
    }

    public function testMatchesFound()
    {
        $result = $this->call(
            array(
                'response' => array(
                    null,
                    array(
                        'url' => 'the-url',
                    ),
                ),
            )
        );
        $this->assertEquals('the-url', $result);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testMalformedResponse2()
    {
        $this->call(
            array(
                'response' => array(
                    null,
                    array(
                        'baz' => 'qux',
                    ),
                ),
            )
        );
    }

    /**
     * @param mixed $returnValue
     * @return array|null
     */
    private function call($returnValue)
    {
        $sdk = $this->getMockBuilder('VkPhpSdk')
            ->disableOriginalConstructor()
            ->setMethods(array('api'))
            ->getMock();

        $sdk->expects($this->once())
            ->method('api')
            ->will($this->returnValue($returnValue));

        /** @var VkPhpSdk $sdk */
        $adapter = new LastBox\Adapter\Vk($sdk);
        $result = $adapter->getTrackUrl('foo', 'bar');

        return $result;
    }
}
