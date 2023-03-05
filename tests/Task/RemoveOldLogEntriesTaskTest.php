<?php

namespace SilverLeague\LogViewer\Tests\Task;

use SilverLeague\LogViewer\Model\LogEntry;
use SilverLeague\LogViewer\Task\RemoveOldLogEntriesTask;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Config\Config;
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
     * Test that the configuration properties are set correctly
     */
    public function testClassProperties()
    {
        $task = new RemoveOldLogEntriesTask;
        $this->assertStringContainsString('Remove LogEntry', $task->getTitle());
        $this->assertStringContainsString('that are older than', $task->getDescription());
        $this->assertSame('RemoveOldLogEntriesTask', Config::inst()->get(RemoveOldLogEntriesTask::class, 'segment'));
    }

    /**
     * Test that the max log age, cron schedule and cron enabled can be set via YAML configuration
     *
     * @dataProvider configurableSettingsProvider
     */
    public function testSettingsAreConfigurable($getter, $setting, $value)
    {
        Config::modify()->set(LogEntry::class, $setting, $value);
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

        Config::modify()->set(LogEntry::class, 'cron_enabled', false);

        $this->assertFalse($mock->process());
    }

    /**
     * Test that old log entries are removed from the database according to the max age setting. The actual date
     * used for checking is gathered from the SQL server inside the query, so we can't really mock it - using 1 day
     * and a new record created now instead.
     *
     * @covers ::removeOldLogs
     * @covers ::run
     * @covers ::process
     */
    public function testRemoveOldLogEntries()
    {
        Config::modify()->set(LogEntry::class, 'max_log_age', 1);

        LogEntry::create(['Entry' => 'Will not be deleted', 'Level' => 'ERROR']);

        ob_start();
        $result = (new RemoveOldLogEntriesTask)->process();
        $second = (new RemoveOldLogEntriesTask)->run(new HTTPRequest('GET', '/'));
        $buffer = ob_get_clean();

        $this->assertTrue($result);
        $this->assertStringContainsString('Removed 6 log(s)', $buffer);
        // Nothing to do the second time
        $this->assertFalse($second);
        $this->assertStringContainsString('Removed 0 log(s)', $buffer);
        $this->assertStringContainsString('older than 1 day(s)', $buffer);
    }
}
