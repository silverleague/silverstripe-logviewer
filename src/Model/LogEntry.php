<?php

namespace SilverLeague\LogViewer\Model;

use SilverLeague\LogViewer\Helper\DeprecatedLogFormatter;
use SilverLeague\LogViewer\Helper\LogFormatter;
use SilverStripe\Core\Convert;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\DataObject;

/**
 * A LogEntry is a set of data provided from Monolog via the DataObjectHandler
 *
 * @package silverstripe-logviewer
 * @author  Robbie Averill <robbie@averill.co.nz>
 * @author  Simon Erkelens <simon@casa-laguna.net>
 *
 * @property string $Entry
 * @property string $Level
 */
class LogEntry extends DataObject
{
    /**
     * {@inheritDoc}
     */
    private static $table_name = 'LogEntry';


    /**
     * @var bool
     */
    private static $format_entry = true;

    /**
     * {@inheritDoc}
     */
    private static $db = [
        'Entry' => 'Text',
        'Level' => 'Varchar'
    ];

    /**
     * We should never need to edit log entries
     * @inheritdoc
     */
    public function canEdit($member = false, $context = [])
    {
        return false;
    }
    /**
     * {@inheritDoc}
     */
    private static $summary_fields = [
        'GridfieldSummary' => 'Entry',
        'Created'          => 'Created',
        'Level'            => 'Level'
    ];

    /**
     * @inheritdoc
     * @return \SilverStripe\Forms\FieldList
     */
    public function getCMSFields()
    {
        $config = self::config()->get('format_entry');
        $fields = parent::getCMSFields();
        if ($config) { // Default setting, format the entry
            $fields->addFieldToTab('Root.Main', HeaderField::create('EntryHeading', 'Entry', 3));
            $fields->removeByName(array('Entry'));
            $entry = $this->buildEntry();
            $fields->addFieldToTab('Root.Main', LiteralField::create('FormattedEntry', $entry));
        } else { // Just move the field after the Level
            $entryField = $fields->dataFieldByName('Entry');
            if ($entryField !== null) {
                $fields->insertAfter('Level', $entryField);
            }
        }

        return $fields;
    }

    private function buildEntry()
    {
        $text = $this->Entry;
        $asArray = Convert::json2array($text);
        // Inline styling for now
        $entry = "<pre class='form-control-static logentry-entry' style='white-space: normal; max-width: 85%;'>";
        if (!is_array($asArray)) {
            /** @deprecated We're falling back to the legacy text-only error */
            $entry .= DeprecatedLogFormatter::formatLegacyEntry($text);
        } else {
            $entry .= LogFormatter::entryToUl($asArray, false);
        }
        $entry .= '</pre>';
        $entry = nl2br($entry);

        return $entry;
    }

    /**
     * @param int $length Length of the summary
     * @return string shortened string of the entry if too long.
     */
    public function getGridfieldSummary($length = 300)
    {
        $elipsis = strlen($this->Entry) > $length ? '...' : '';

        return substr($this->Entry, 0, $length) . $elipsis;
    }
}
