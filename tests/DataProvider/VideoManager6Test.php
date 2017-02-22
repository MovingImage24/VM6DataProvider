<?php

namespace MovingImage\DataProvider\Tests;

use MovingImage\Client\VM6\Criteria\VideoQueryCriteria;
use MovingImage\Client\VM6\Interfaces\ApiClientInterface;
use MovingImage\DataProvider\VideoManager6;

/**
 * Class VideoManager6Test
 * @package MovingImage\DataProvider\Tests\VM6DataProvider
 *
 * @author Robert Szeker <robert.szeker@movingimage.com>
 */
class VideoManager6Test extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the data provider, searching for one specific channel id.
     */
    public function testGetDataWithOneChannel()
    {
        $client = $this->getMockBuilder(ApiClientInterface::class)->getMock();

        $dataProvider = new VideoManager6($client);

        $limit = 1;
        $offset = 2;
        $channelId = 3;
        $page = 7;

        $videoQueryCriteria = new VideoQueryCriteria();
        $videoQueryCriteria
            ->setChannelId($channelId)
            ->setLimit($limit)
            ->setOffset($offset)
            ->setPage($page);

        $expectedReturn = 'expected Return';

        $options = [
            'limit' => $limit,
            'offset' => $offset,
            'channelId' => $channelId,
            'page' => $page,
        ];

        $client
            ->expects($this->once())
            ->method('getVideos')
            ->with($videoQueryCriteria)
            ->willReturn($expectedReturn);

        $videos = $dataProvider->getData($options);

        $this->assertEquals($expectedReturn, $videos);
    }

    /**
     * Testing the data provider, searching for multiple channel IDs.
     */
    public function testGetDataWithMultipleChannels()
    {
        $client = $this->getMockBuilder(ApiClientInterface::class)->getMock();

        $dataProvider = new VideoManager6($client);

        $limit = 1;
        $offset = 2;
        $channelIds = [3, 4, 5, 6];
        $page = 7;

        $videoQueryCriteria = new VideoQueryCriteria();
        $videoQueryCriteria
            ->setChannelIds($channelIds)
            ->setLimit($limit)
            ->setOffset($offset)
            ->setPage($page);

        $expectedReturn = 'expected Return';

        $options = [
            'limit' => $limit,
            'offset' => $offset,
            'channelIds' => $channelIds,
            'page' => $page,
        ];

        $client
            ->expects($this->once())
            ->method('getVideos')
            ->with($videoQueryCriteria)
            ->willReturn($expectedReturn);

        $videos = $dataProvider->getData($options);

        $this->assertEquals($expectedReturn, $videos);
    }
}