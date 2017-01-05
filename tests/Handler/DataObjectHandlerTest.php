<?php

namespace SilverLeague\LogViewer\Tests\Handler;

use SilverLeague\LogViewer\Handler\DataObjectHandler;
use SilverLeague\LogViewer\Model\LogEntry;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Core\Injector\Injector;

/**
 * @coversDefaultClass SilverLeague\LogViewer\Handler\DataObjectHandler
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
        $this->logger->addDebug('Hello world');

        $logEntry = LogEntry::get()->first();
        $this->assertContains('Hello world', $logEntry->Entry);
        $this->assertSame('DEBUG', $logEntry->Level);
    }

    /**
     * Restore the original logger handlers
     *
     * {@inheritDoc}
     */
    public function tearDown()
    {
        $this->logger->setHandlers($this->originalHandlers);

        parent::tearDown();
    }
}
