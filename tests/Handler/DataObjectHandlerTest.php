<?php

namespace SilverLeague\LogViewer\Tests\Handler;

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
    protected $logger;

    /**
     * The original logger handlers
     * @var Monolog\LoggerInterface[]
     */
    protected $originalHandlers = [];

    /**
     * {@inheritDoc}
     */
    protected $usesDatabase = true;

    /**
     * Create a Logger to test with and clear the existing logger handlers
     *
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();

        Config::nest();

        $this->logger = Injector::inst()->get('Logger');

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
        $this->logger->addError('Hello world');

        $logEntry = LogEntry::get()->first();
        $this->assertContains('Hello world', $logEntry->Entry);
        $this->assertSame('ERROR', $logEntry->Level);
    }

    /**
     * Test that logs are handled at a minimum level, but not lower than it.
     */
    public function testDontLogMessagesLowerThanMinimumLever()
    {
        Config::inst()->update('LogViewer', 'minimum_log_level', 300);
        LogEntry::get()->removeAll();
        $this->logger->pushHandler(new DataObjectHandler);

        $this->logger->addDebug('Debug');
        $this->assertSame(0, LogEntry::get()->count());

        $this->logger->addWarning('Warning');
        $this->assertGreaterThan(0, LogEntry::get()->filter('Level', 'WARNING')->count());

        $this->logger->addAlert('Alert');
        $this->assertGreaterThan(0, LogEntry::get()->filter('Level', 'ALERT')->count());
    }

    /**
     * Test that the minumum log capture level is returned from configuration
     */
    public function testGetMinimumLogLevelFromConfiguration()
    {
        Config::inst()->update('LogViewer', 'minimum_log_level', 123);
        $this->assertSame(123, (new DataObjectHandler)->getMinimumLogLevel());
    }

    /**
     * Restore the original logger handlers
     *
     * {@inheritDoc}
     */
    public function tearDown()
    {
        Config::unnest();

        $this->logger->setHandlers($this->originalHandlers);

        parent::tearDown();
    }
}
