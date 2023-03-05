<?php

namespace SilverLeague\LogViewer\Tests\Handler;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use SilverLeague\LogViewer\Handler\DataObjectHandler;
use SilverLeague\LogViewer\Model\LogEntry;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Injector\Injector;

/**
 * @package silverstripe-logviewer
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class DataObjectHandlerTest extends SapphireTest
{
    /**
     * A Logger instance
     * @var Monolog\Logger
     */
    protected Logger $logger;

    /**
     * The original logger handlers
     * @var Monolog\LoggerInterface[]
     */
    protected  $originalHandlers = [];

    /**
     * {@inheritDoc}
     */
    protected $usesDatabase = true;

    /**
     * Create a Logger to test with and clear the existing logger handlers
     *
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->logger = Injector::inst()->get(LoggerInterface::class);

        // Clear the default handlers so we can test precisely
        $this->originalHandlers = $this->logger->getHandlers();
        $this->logger->setHandlers([]);
    }

    /**
     * Test that arbitary log levels are all written to the database through the DataObjectHandler
     */
    public function testWriteToDefaultLogger()
    {
        $this->logger->pushHandler(new DataObjectHandler);
        $this->logger->error('Hello world');

        $logEntry = LogEntry::get()->first();
        $this->assertStringContainsString('Hello world', $logEntry->Entry);
        $this->assertSame('ERROR', $logEntry->Level);
    }

    /**
     * Test that logs are handled at a minimum level, but not lower than it.
     */
    public function testDontLogMessagesLowerThanMinimumLever()
    {
        Config::modify()->set(LogEntry::class, 'minimum_log_level', 300);
        LogEntry::get()->removeAll();
        $this->logger->pushHandler(new DataObjectHandler);

        $this->logger->debug('Debug');
        $this->assertSame(0, LogEntry::get()->count());

        $this->logger->warning('Warning');
        $this->assertGreaterThan(0, LogEntry::get()->filter('Level', 'WARNING')->count());

        $this->logger->alert('Alert');
        $this->assertGreaterThan(0, LogEntry::get()->filter('Level', 'ALERT')->count());
    }

    /**
     * Test that the minumum log capture level is returned from configuration
     */
    public function testGetMinimumLogLevelFromConfiguration()
    {
        Config::modify()->set(LogEntry::class, 'minimum_log_level', 300);
        $this->assertSame(300, (new DataObjectHandler)->getMinimumLogLevel());
    }

    /**
     * Restore the original logger handlers
     *
     * {@inheritDoc}
     */
    public function tearDown(): void
    {
        $this->logger->setHandlers($this->originalHandlers);

        parent::tearDown();
    }
}
