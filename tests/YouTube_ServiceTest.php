<?php

namespace Craft;

/**
 * YouTube Service Test.
 *
 * Asserts for the YouTubeService class
 *
 * @author    Bob Olde Hampsink <b.oldehampsink@itmundi.nl>
 * @copyright Copyright (c) 2015, Itmundi
 * @license   MIT
 *
 * @link      http://github.com/boboldehampsink
 */
class YouTube_ServiceTest extends BaseTest
{
    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        require_once __DIR__.'/../vendor/autoload.php';
        require_once __DIR__.'/../YouTubePlugin.php';
        require_once __DIR__.'/../services/YouTubeService.php';
        require_once __DIR__.'/../models/YouTube_VideoModel.php';
        require_once __DIR__.'/../../oauth/services/OauthService.php';
        require_once __DIR__.'/../../oauth/records/Oauth_ProviderInfosRecord.php';
        require_once __DIR__.'/../../oauth/models/Oauth_ProviderInfosModel.php';
        require_once __DIR__.'/../../oauth/records/Oauth_TokenRecord.php';
        require_once __DIR__.'/../../oauth/models/Oauth_TokenModel.php';
    }

    /**
     * Test process type hinting.
     */
    public function testProcessTypeHinting()
    {
        // Set up service
        $service = new YouTubeService();

        // Expect exception
        $this->setExpectedException(get_class(new \PHPUnit_Framework_Error('', 0, '', 1)));

        // Test type hinting correctness
        $service->process(new \stdClass(), new \stdClass(), 'handle', 0);
    }

    /**
     * Test process.
     */
    public function testProcess()
    {
        // Set up service
        $this->setMockYouTubeService();

        // Set up model
        $model = $this->getMockAssetFileModel();

        // Process
        $result = craft()->youtube->process($model, $model, 'handle', 0);

        // Assert true
        $this->assertTrue($result);
    }

    /**
     * Mock YouTube Service.
     *
     * @return YouTube|\PHPUnit_Framework_MockObject_MockObject
     */
    private function setMockYouTubeService()
    {
        $this->setMockPluginsService();
        $this->setMockOauthService();
        $this->setMockContentService();

        $mock = $this->getMockBuilder(YouTubeService::class)
            ->setMethods(array('uploadChunks'))
            ->getMock();

        // Set YouTube ID
        $status = new \stdClass();
        $status->id = '9NiMDN1fxno';

        $mock->expects($this->any())->method('uploadChunks')->willReturn($status);

        craft()->setComponent('youtube', $mock);
    }

    /**
     * Mock Plugins Service.
     *
     * @return PluginsService|\PHPUnit_Framework_MockObject_MockObject
     */
    private function setMockPluginsService()
    {
        $mock = $this->getMockBuilder(PluginsService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $plugin = new YouTubePlugin();

        $mock->expects($this->any())->method('getPlugin')->willReturn($plugin);

        craft()->setComponent('plugins', $mock);
    }

    /**
     * Mock AssetFileModel.
     *
     * @return AssetFileModel|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockAssetFileModel()
    {
        $mock = $this->getMockBuilder(AssetFileModel::class)
            ->disableOriginalConstructor()
            ->getMock();

        $source = $this->getMockAssetSourceModel();
        $content = $this->getMockContentModel();

        $mock->expects($this->any())->method('getSource')->willReturn($source);
        $mock->expects($this->any())->method('getContent')->willReturn($content);

        return $mock;
    }

    /**
     * Mock AssetSourceModel.
     *
     * @return AssetSourceModel|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockAssetSourceModel()
    {
        $mock = $this->getMockBuilder(AssetSourceModel::class)
            ->disableOriginalConstructor()
            ->getMock();

        $sourceType = $this->getMockBaseAssetSourceType();

        $mock->expects($this->any())->method('getSourceType')->willReturn($sourceType);

        return $mock;
    }

    /**
     * Mock ContentModel.
     *
     * @return ContentModel|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockContentModel()
    {
        $mock = $this->getMockBuilder(ContentModel::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->any())->method('getAttribute')->willReturn(array());

        return $mock;
    }

    /**
     * Mock Content Service.
     *
     * @return ContentService|\PHPUnit_Framework_MockObject_MockObject
     */
    private function setMockContentService()
    {
        $mock = $this->getMockBuilder(ContentService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->any())->method('saveContent')->willReturn(true);

        craft()->setComponent('content', $mock);
    }

    /**
     * Mock AssetSourceModel.
     *
     * @return AssetSourceModel|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockBaseAssetSourceType()
    {
        $mock = $this->getMockBuilder(BaseAssetSourceType::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->any())->method('getLocalCopy')->willReturn('test.jpg');

        return $mock;
    }

    /**
     * Mock OAuth Service.
     *
     * @return OauthService|\PHPUnit_Framework_MockObject_MockObject
     */
    private function setMockOauthService()
    {
        $mock = $this->getMockBuilder(OauthService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $token = $this->getMockOauthTokenModel();

        $mock->expects($this->any())->method('getTokenById')->willReturn($token);

        craft()->setComponent('oauth', $mock);
    }

    /**
     * Mock OAuth Token Model.
     *
     * @return OAuth_TokenModel
     */
    private function getMockOAuthTokenModel()
    {
        $mock = new OAuth_TokenModel();
        $mock->accessToken = 'ya29.zQEjMLOfhL4VrrAKNsLFZcV8V1HJIU0cyq8-FciOKITQABdBEjlEwxZjHCyIhxXKV1vX6g';
        $mock->refreshToken = '1/CEY3ISZaEFaQkG-wjtETZYa3s2lwtjJWh5bp4Tm4Xi9IgOrJDtdun6zK6XiATCKT';
        $mock->endOfLife = '1439377156';

        return $mock;
    }
}
