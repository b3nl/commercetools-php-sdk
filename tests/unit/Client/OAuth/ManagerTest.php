<?php
/**
 * @author @jayS-de <jens.schulze@commercetools.de>
 * @created: 29.01.15, 14:16
 */

namespace Commercetools\Core\Client\OAuth;

use Cache\Adapter\PHPArray\ArrayCachePool;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\BufferStream;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Subscriber\Mock;
use Commercetools\Core\Cache\NullCacheAdapter;
use Commercetools\Core\Config;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!extension_loaded('apcu') && !extension_loaded('apc')) {
            $this->markTestSkipped(
                'The APCU extension is not available.'
            );
        }
    }

    protected function getConfig()
    {
        $config = Config::fromArray([
                Config::CLIENT_ID => 'id',
                Config::CLIENT_SECRET => 'secret',
                Config::OAUTH_URL => 'oauthUrl',
                Config::PROJECT => 'project',
                Config::API_URL => 'apiUrl'
        ]);
        return $config;
    }

    /**
     * @return Manager
     */
    protected function getManager($config, $returnValue, $statusCode = 200, $noCache = false)
    {
        if ($noCache) {
            $manager = new Manager($config, new NullCacheAdapter());
        } else {
            $manager = new Manager($config);
        }

        if (is_array($returnValue)) {
            $returnValue = json_encode($returnValue);
        }

        if (version_compare(HttpClient::VERSION, '6.0.0', '>=')) {
            $mockBody = new BufferStream();
            $mockBody->write($returnValue);

            $mock = new MockHandler([
                new Response($statusCode, [], $mockBody)
            ]);

            $handler = HandlerStack::create($mock);
            // Add the mock subscriber to the client.
            $manager->getHttpClient(['handler' => $handler]);
        } else {
            $mockBody = new \GuzzleHttp\Stream\BufferStream();
            $mockBody->write($returnValue);

            $mock = new Mock([
                new \GuzzleHttp\Message\Response($statusCode, [], $mockBody)
            ]);
            // Add the mock subscriber to the client.
            $manager->getHttpClient()->getEmitter()->attach($mock);
        }

        return $manager;
    }

    public function testToken()
    {
        $manager = $this->getManager(
            $this->getConfig(),
            [
                "access_token" => "myToken",
                "token_type" => "Bearer",
                "expires_in" => 1000,
                "scope" => "manage_project:project"
            ],
            200,
            true
        );
        $this->assertInstanceOf('\Commercetools\Core\Client\OAuth\Token', $manager->getToken());
        $this->assertSame($manager->getConfig()->getScope(), $manager->getToken()->getScope());
    }

    public function testScope()
    {
        $config = Config::fromArray([
            Config::CLIENT_ID => 'id',
            Config::CLIENT_SECRET => 'secret',
            Config::SCOPE => 'test_scope',
            Config::OAUTH_URL => 'oauthUrl',
            Config::PROJECT => 'testScope',
            Config::API_URL => 'apiUrl'
        ]);
        $manager = $this->getManager(
            $config,
            [
                "access_token" => "myToken",
                "token_type" => "Bearer",
                "expires_in" => 1000,
                "scope" => "test_scope:testScope"
            ],
            200,
            true
        );
        $token = $manager->getToken();
        $this->assertInstanceOf('\Commercetools\Core\Client\OAuth\Token', $token);
        $this->assertSame($manager->getConfig()->getScope(), $token->getScope());
    }

    public function testEmptyScope()
    {
        $config = Config::fromArray([
            Config::CLIENT_ID => 'id',
            Config::CLIENT_SECRET => 'secret',
            Config::SCOPE => '',
            Config::OAUTH_URL => 'oauthUrl',
            Config::PROJECT => 'testEmptyScope',
            Config::API_URL => 'apiUrl'
        ]);
        $manager = $this->getManager(
            $config,
            [
                "access_token" => "myToken",
                "token_type" => "Bearer",
                "expires_in" => 1000,
                "scope" => "test_scope:testEmptyScope"
            ],
            200,
            true
        );
        $token = $manager->getToken();
        $this->assertEmpty($config->getScope());
        $this->assertInstanceOf('\Commercetools\Core\Client\OAuth\Token', $token);
        $this->assertSame("test_scope:testEmptyScope", $token->getScope());
        $this->assertNotSame($manager->getConfig()->getScope(), $token->getScope());
    }

    public function testScopes()
    {
        $config = Config::fromArray([
            Config::CLIENT_ID => 'id',
            Config::CLIENT_SECRET => 'secret',
            Config::SCOPE => ['scope1', 'scope2'],
            Config::OAUTH_URL => 'oauthUrl',
            Config::PROJECT => 'testScopes',
            Config::API_URL => 'apiUrl'
        ]);
        $manager = $this->getManager(
            $config,
            [
                "access_token" => "myToken",
                "token_type" => "Bearer",
                "expires_in" => 1000,
                "scope" => "scope1:testScopes scope2:testScopes"
            ],
            200,
            true
        );
        $this->assertInstanceOf('\Commercetools\Core\Client\OAuth\Token', $manager->getToken());
        $this->assertSame($manager->getConfig()->getScope(), $manager->getToken()->getScope());
    }

    public function testCache()
    {
        $manager = $this->getManager(
            $this->getConfig(),
            [
                "access_token" => "myToken",
                "token_type" => "Bearer",
                "expires_in" => 1000,
                "scope" => "manage_project:testCache"
            ]
        );
        $manager->getToken(); // first call ensures caching of token
        $this->assertEmpty($manager->getToken()->getTtl()); // ttl should be empty as token comes from cache
    }

    public function testCacheAdapter()
    {
        $cache1 = new NullCacheAdapter();
        $manager = new Manager($this->getConfig(), $cache1);

        $cache2 = new NullCacheAdapter();
        $this->assertInstanceOf('\Commercetools\Core\Client\OAuth\Manager', $manager->setCacheAdapter($cache2));
        $this->assertSame($cache2, $manager->getCacheAdapter());
    }

    public function testPsrCacheAdapter()
    {
        if (version_compare(phpversion(), '5.5.0', '<')) {
            $this->markTestSkipped(
                'PHP >= 5.5 needed to run this test'
            );
        }
        $cache1 = new NullCacheAdapter();
        $manager = new Manager($this->getConfig(), $cache1);

        $cache2 = new ArrayCachePool();
        $this->assertInstanceOf('\Commercetools\Core\Client\OAuth\Manager', $manager->setCacheAdapter($cache2));
        $this->assertSame($cache2, $manager->getCacheAdapter());
    }

    /**
     * @expectedException \Commercetools\Core\Error\InvalidClientCredentialsException
     */
    public function testError()
    {
        $manager = $this->getManager(
            $this->getConfig(),
            [
                'error' => 'invalid_client',
                'error_description' =>
                    'Please provide valid client credentials using HTTP Basic Authentication.'
            ],
            401,
            true
        );

        /**
         * @var Manager $manager
         */
        $manager->getToken();
    }

    public function testOAuthUrl()
    {
        $manager = $this->getManager($this->getConfig(), []);

        // change visibility of getBaseUrl
        $class = new \ReflectionClass($manager);
        $method = $class->getMethod('getBaseUrl');
        $method->setAccessible(true);
        $output = $method->invoke($manager);

        $this->assertSame($this->getConfig()->getOauthUrl(), $output);
    }
}
