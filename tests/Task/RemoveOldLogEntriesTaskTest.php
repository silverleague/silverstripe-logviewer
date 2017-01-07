<?php

namespace SilverLeague\LogViewer\Tests\Task;

use SilverLeague\LogViewer\Model\LogEntry;
use SilverLeague\LogViewer\Task\RemoveOldLogEntriesTask;
use SilverStripe\Core\Config\Config;
use Silverstripe\ORM\FieldType\DBDatetime;
use SilverStripe\Dev\SapphireTest;

/**
 * @coversDefaultClass \SilverLeague\LogViewer\Task\RemoveOldLogEntriesTask
 * @package silverstripe-logviewer
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class RemoveOldLogEntriesTaskTest extends SapphireTest
{
    /**
     * {@inheritDoc}
     */
    protected $usesDatabase = true;

    /**
     * {@inheritDoc}
     */
    protected static $fixture_file = 'RemoveOldLogEntriesTaskTest.yml';

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();
        Config::inst()->nest();
        DBDatetime::set_mock_now('2017-01-08 00:58:00');
    }

    /**
     * Test that the configuration properties are set correctly
     */
    public function testClassProperties()
    {
        $task = new RemoveOldLogEntriesTask;
        $this->assertContains('Remove LogEntry', $task->getTitle());
        $this->assertContains('Will run as a cron task', $task->getDescription());
        $this->assertSame('RemoveOldLogEntriesTask', Config::inst()->get(RemoveOldLogEntriesTask::class, 'segment'));
    }

    /**
     * Test that the max log age, cron schedule and cron enabled can be set via YAML configuration
     *
     * @dataProvider configurableSettingsProvider
     */
    public function testSettingsAreConfigurable($getter, $setting, $value)
    {
        Config::inst()->update('LogViewer', $setting, $value);
        $this->assertSame($value, (new RemoveOldLogEntriesTask)->{$getter}());
    }

    /**
     * @return array[]
     */
    public function configurableSettingsProvider()
    {
        return [
            ['getMaxAge', 'max_log_age', 9],
            ['getCronEnabled', 'cron_enabled', false],
            ['getSchedule', 'cron_schedule', '1 2 3 4 5']
        ];
    }

    /**
     * Test that when in the cron context and the cron task is disabled that nothing happens
     */
    public function testNothingHappensInCronContextIfCronIsDisabled()
    {
        $mock = $this
            ->getMockBuilder(RemoveOldLogEntriesTask::class)
            ->setMethods(['removeOldLogs'])
            ->getMock();

        $mock
            ->expects($this->never())
            ->method('removeOldLogs');

        Config::inst()->update('LogViewer', 'cron_enabled', false);

        $this->assertFalse($mock->process());
    }

    /**
     * Test that old log entries are removed from the database according to the max age setting
     *
     * @covers ::run
     * @covers ::process
     */
    public function testRemoveOldLogEntries()
    {
        Config::inst()->update('LogViewer', 'max_log_age', 14);

        ob_start();
        $result = (new RemoveOldLogEntriesTask)->process();
        $buffer = ob_get_clean();

        $this->assertTrue($result);
        $this->assertContains('Removed 3 logs', $buffer);
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown()
    {
        Config::inst()->unnest();
        parent::tearDown();
    }
}
