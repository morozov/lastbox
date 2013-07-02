<?php

/**
 * @covers LastBox\Adapter\LastFM
 */
class LastFMTest extends PHPUnit_Framework_TestCase
{
    public function testSuccess()
    {
        $userApi = $this->getUserApiMockSuccess('TheUserName', 'TheResult');
        $adapter = $this->getAdapter($userApi);
        $result = $adapter->getLovedTracks('TheUserName');

        $this->assertEquals('TheResult', $result);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionCode 123
     * @expectedExceptionMessage TheDescription
     */
    public function testFailure()
    {
        $userApi = $this->getUserApiMockFailure(123, 'TheDescription');
        $adapter = $this->getAdapter($userApi);
        $result = $adapter->getLovedTracks(null);
    }

    private function getAuthApiMock()
    {
        $authApi = $this->getMockBuilder('lastfmApiAuth')
            ->disableOriginalConstructor()
            ->getMock();

        return $authApi;
    }

    private function getUserApiMock()
    {
        $userApi = $this->getMockBuilder('lastfmApiUser')
            ->disableOriginalConstructor()
            ->getMock();

        return $userApi;
    }

    private function getApiMock(\lastfmApiUser $userApi)
    {
        $api = $this->getMockBuilder('lastfmApi')
            ->disableOriginalConstructor()
            ->getMock();
        $api->expects($this->any())
            ->method('getPackage')
            ->will($this->returnValue($userApi));

        return $api;
    }

    private function getAdapter($userApi)
    {
        $api = $this->getApiMock($userApi);
        $authApi = $this->getAuthApiMock();

        $adapter = new \LastBox\Adapter\LastFM($api, $authApi);

        return $adapter;
    }

    private function getUserApiMockSuccess($username, $result)
    {
        $userApi = $this->getUserApiMock();
        $userApi->expects($this->once())
            ->method('getLovedTracks')
            ->with(
                array(
                    'user' => $username,
                )
            )
            ->will($this->returnValue($result));

        return $userApi;
    }

    private function getUserApiMockFailure($code, $description)
    {
        $userApi = $this->getUserApiMock();
        $userApi->expects($this->once())
            ->method('getLovedTracks')
            ->will($this->returnValue(null));
        $userApi->error = array(
            'code' => $code,
            'desc' => $description,
        );

        return $userApi;
    }
}
