<?php

namespace App\Tests\Helper;

use App\Helper\YoutubeApiHelper;
use PHPUnit\Framework\TestCase;

class YoutubeApiHelperTest extends TestCase
{
    private YoutubeApiHelper $youtubeApiHelper;

    protected function setUp(): void
    {
        $this->youtubeApiHelper = new YoutubeApiHelper();
    }

    public function testIsPublishedVideoWhenVideoPublishedAtIsDefined()
    {
        $data = [
            'contentDetails' => [
                'videoId' => 'uicbuaO7fhM',
                'videoPublishedAt' => '2021-11-10T08:00:15Z',
            ],
        ];

        $this->assertTrue($this->youtubeApiHelper->isPublishedVideo($data));
    }

    public function testIsPublishedVideoWhenVideoPublishedAtIsNotDefined()
    {
        $data = [
            'contentDetails' => [
                'videoId' => 'uicbuaO7fhM',
            ],
        ];

        $this->assertFalse($this->youtubeApiHelper->isPublishedVideo($data));
    }

    public function testGetThumbnailImage()
    {
        $data = [
            'snippet' => [
                'thumbnails' => [
                    'default' => [
                        'url' => 'https://i.ytimg.com/vi/uicbuaO7fhM/default.jpg',
                        'width' => 120,
                        'height' => 90,
                    ],
                    'medium' => [
                        'url' => 'https://i.ytimg.com/vi/uicbuaO7fhM/mqdefault.jpg',
                        'width' => 320,
                        'height' => 180,
                    ],
                    'high' => [
                        'url' => 'https://i.ytimg.com/vi/uicbuaO7fhM/hqdefault.jpg',
                        'width' => 480,
                        'height' => 360,
                    ],
                    'standard' => [
                        'url' => 'https://i.ytimg.com/vi/uicbuaO7fhM/sddefault.jpg',
                        'width' => 640,
                        'height' => 480,
                    ],
                    'maxres' => [
                        'url' => 'https://i.ytimg.com/vi/uicbuaO7fhM/maxresdefault.jpg',
                        'width' => 1280,
                        'height' => 720,
                    ],
                ],
            ],
        ];

        $this->assertEquals('https://i.ytimg.com/vi/uicbuaO7fhM/mqdefault.jpg', $this->youtubeApiHelper->getThumbnailImage($data));
    }

    public function testGetMaxResImage()
    {
        $data = [
            'snippet' => [
                'thumbnails' => [
                    'default' => [
                        'url' => 'https://i.ytimg.com/vi/uicbuaO7fhM/default.jpg',
                        'width' => 120,
                        'height' => 90,
                    ],
                    'medium' => [
                        'url' => 'https://i.ytimg.com/vi/uicbuaO7fhM/mqdefault.jpg',
                        'width' => 320,
                        'height' => 180,
                    ],
                    'high' => [
                        'url' => 'https://i.ytimg.com/vi/uicbuaO7fhM/hqdefault.jpg',
                        'width' => 480,
                        'height' => 360,
                    ],
                    'standard' => [
                        'url' => 'https://i.ytimg.com/vi/uicbuaO7fhM/sddefault.jpg',
                        'width' => 640,
                        'height' => 480,
                    ],
                    'maxres' => [
                        'url' => 'https://i.ytimg.com/vi/uicbuaO7fhM/maxresdefault.jpg',
                        'width' => 1280,
                        'height' => 720,
                    ],
                ],
            ],
        ];

        $this->assertEquals('https://i.ytimg.com/vi/uicbuaO7fhM/maxresdefault.jpg', $this->youtubeApiHelper->getMaxResImage($data));
    }

    public function getGetVideoUrl()
    {
        $data = [
            'id' => 'test',
        ];

        $this->assertEquals('https://www.youtube.com/watch?v=test', $this->youtubeApiHelper->getVideoUrl($data));
    }

    public function testGetVideoDurationInSeconds()
    {
        $data = [
            'contentDetails' => [
                'duration' => 'PT4M0S',
            ],
        ];

        $this->assertEquals(240, $this->youtubeApiHelper->getVideoDurationInSeconds($data));
    }
}
