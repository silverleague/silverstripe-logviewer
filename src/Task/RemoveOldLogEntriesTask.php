<?php

namespace SilverLeague\LogViewer\Task;

use SilverLeague\LogViewer\Model\LogEntry;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Dev\BuildTask;
use SilverStripe\Core\Config\Config;
use SilverStripe\CronTask\Interfaces\CronTask;

/**
 * Remove old LogEntry records from the database
 *
 * @package silverstripe-logviewer
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class RemoveOldLogEntriesTask extends BuildTask implements CronTask
{
    /**
     * {@inheritDoc}
     */
    private static string $segment = 'RemoveOldLogEntriesTask';

    /**
     * {@inheritDoc}
     */
    protected $title = 'Remove LogEntry records older than a "n" days';

    /**
     * {@inheritDoc}
     */
    protected $description = 'Removes LogEntry records that are older than the configured `LogViewer.max_log_age`.';

    /**
     * BuildTask implementation
     *
     * {@inheritDoc}
     *
     * @return bool Whether anything was removed
     */
    public function run($request)
    {
        return $this->removeOldLogs();
    }

    /**
     * CronTask implementation - can be disabled with YAML configuration
     *
     * {@inheritDoc}
     *
     * @return bool Whether anything was removed
     */
    public function process(): bool
    {
        if (!$this->getCronEnabled()) {
            return false;
        }
        return $this->removeOldLogs();
    }

    /**
     * {@inheritDoc}
     */
    public function getSchedule(): mixed
    {
        return Config::inst()->get(LogEntry::class, 'cron_schedule');
    }

    /**
     * Get the maximum age allowed for a LogEntry from configuration
     *
     * @return int
     */
    public function getMaxAge(): int
    {
        return (int) Config::inst()->get(LogEntry::class, 'max_log_age');
    }

    /**
     * Return whether the cron functionality is enabled from configuration
     *
     * @return bool
     */
    public function getCronEnabled(): bool
    {
        return (bool) Config::inst()->get(LogEntry::class, 'cron_enabled');
    }

    /**
     * Remove LogEntry records older than the LogViewer.max_log_age days
     *
     * @return bool Whether anything was deleted or not
     */
    protected function removeOldLogs(): bool
    {
        $tableName = LogEntry::singleton()->baseTable();
        $maxAge = $this->getMaxAge();

        $logs = LogEntry::get()
            ->where(sprintf('"%s"."Created" <= NOW() - INTERVAL \'%d\' DAY', $tableName, $maxAge));

        $count = $logs->count();
        if ($count > 0) {
            $logs->removeAll();
        }

        echo sprintf('Finished. Removed %d log(s) older than %d day(s).', $count, $maxAge) . PHP_EOL;

        return $count > 0;
    }
}
