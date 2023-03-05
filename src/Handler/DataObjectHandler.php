<?php

namespace SilverLeague\LogViewer\Handler;

use Monolog\Logger;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use SilverLeague\LogViewer\Model\LogEntry;
use SilverStripe\Core\Config\Config;

/**
 * The DataObjectHandler allows you to use a SilverStripe DataObject for handling Monolog log entries.
 *
 * The default class to use is "LogEntry" which will store the log message, level and date/time in the database.
 *
 * @package silverstripe-logviewer
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class DataObjectHandler extends AbstractProcessingHandler
{
    /**
     * The default DataObject to use for storing log entries
     *
     * @var string
     */
    const DEFAULT_CLASS = LogEntry::class;

    /**
     * The default DateTime format to use for storing the log timestamp
     *
     * @var string
     */
    const DEFAULT_DATETIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * DataObject class for storing log entries
     *
     * @var string
     */
    protected string $objectClass;

    /**
     * @param string  $objectClass The DataObject class to use for handling the write
     * @param int     $level       The minimum logging level at which this handler will be triggered (configurable)
     * @param boolean $bubble      Whether the messages that are handled can bubble up the stack or not
     */
    public function __construct(string $objectClass = self::DEFAULT_CLASS, int $level = Logger::DEBUG, bool $bubble = true)
    {
        $this->setObjectClass($objectClass);
        $level = $this->getMinimumLogLevel();
        parent::__construct($level, $bubble);
    }

    protected function getDefaultFormatter(): JsonFormatter
    {
        return new JsonFormatter;
    }

    protected function write(LogRecord $record): void
    {
        $this->addDataObject((string) $record['formatted'], $record['level_name']);
    }

    /**
     * Create a new DataObject instance and set the log information to it
     *
     * @param  string $message The log message
     * @param  string $level   The log level text, e.g. "DEBUG"
     * @return int             The written DataObject ID
     */
    public function addDataObject(string $message, string $level): int
    {
        $class = $this->getObjectClass();

        $object = $class::create();
        $object->setField('Entry', $message);
        $object->setField('Level', $level);
        $object->write();

        return $object->ID;
    }

    /**
     * Set the DataObject to use for storing log entries
     *
     * @param  string $class
     * @return $this
     */
    public function setObjectClass(string $class): self
    {
        $this->objectClass = $class;
        return $this;
    }

    /**
     * Get the DataObject to use for storing log entries
     *
     * @return string
     */
    public function getObjectClass(): string
    {
        return $this->objectClass;
    }

    /**
     * Get the minimum Monolog\Logger log level to start catching messages at
     *
     * @see \Monolog\Logger
     * @return int
     */
    public function getMinimumLogLevel(): int
    {
        return (int) Config::inst()->get(LogEntry::class, 'minimum_log_level');
    }
}
