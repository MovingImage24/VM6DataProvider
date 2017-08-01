<?php

namespace MovingImage\DataProvider;

use MovingImage\Client\VM6\Criteria\VideoQueryCriteria;
use MovingImage\Client\VM6\Interfaces\ApiClientInterface;
use MovingImage\DataProvider\Interfaces\DataProviderInterface;
use MovingImage\DataProvider\Wrapper\Video as VideoWrapper;

/**
 * Class VideoManager6
 * @package MovingImage\DataProvider
 *
 * @author Robert Szeker <robert.szeker@movingimage.com>
 */
class VideoManager6 implements DataProviderInterface
{
    /**
     * @var ApiClientInterface
     */
    private $apiClient;

    /**
     * VideoManager6 constructor.
     * @param ApiClientInterface $apiClient
     */
    public function __construct(ApiClientInterface $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(array $options)
    {
        return $this->apiClient->getVideos($this->createVideoQueryCriteria($options));
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(array $options)
    {
        return $this->apiClient->getVideos($this->createVideoQueryCriteria($options));
    }

    /**
     * {@inheritdoc}
     */
    public function getOne(array $options)
    {
        if (!isset($options['id'])) {
            // Simply fetch the first video from the collection, but without
            // loading all videos inside the collection
            $options['limit'] = 1;

            $videos = $this->getAll($options);

            if (count($videos) === 0) {
                return null;
            }

            $video = $videos[0];
        } else {
            $video = $this->apiClient->getVideo($options['id']);
        }

        $embedCode = $this->apiClient->getEmbedCode(
            $video,
            $options['player_id']);

        return new VideoWrapper($video, $embedCode);
    }

    /**
     * {@inheritdoc}
     */
    public function getCount(array $options)
    {
        return $this->apiClient->getVideoCount($this->createVideoQueryCriteria($options));
    }

    /**
     * @param array $options
     * @return VideoQueryCriteria
     */
    private function createVideoQueryCriteria(array $options)
    {
        // remove empty channel_ids, so it doesn't overwrite single channel_id
        if (isset($options['channel_ids']) && empty($options['channel_ids'])) {
            unset($options['channel_ids']);
        }

        $criteria = new VideoQueryCriteria();

        $methods = [
            'limit'                => 'setLimit',
            'offset'               => 'setOffset',
            'channel_id'           => 'setChannelId',
            'channel_ids'          => 'setChannelIds',
            'page'                 => 'setPage',
            'order_by'             => 'setSortColumn',
            'order'                => 'setSortByColumnOrder',
            'search_term'          => 'setSearchTerm',
            'include_sub_channels' => 'setIncludeSubChannels',
            'publication_state'    => 'setPublicationState',
        ];

        foreach ($methods as $key => $method) {
            if (isset($options[$key])) {
                $criteria->$method($options[$key]);
            }
        }

        return $criteria;
    }
}