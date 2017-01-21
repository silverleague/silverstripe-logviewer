<?php

namespace SilverLeague\LogViewer\Tests\Model;

use SilverLeague\LogViewer\Model\LogEntry;
use SilverLeague\LogViewer\Tests\Helper\Invoker;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Convert;
use SilverStripe\Dev\SapphireTest;

/**
 * @package silverstripe-logviewer
 * @author  Robbie Averill <robbie@averill.co.nz>
 * @author  Simon Erkelens <simon@casa-laguna.net>
 */
class LogEntryTest extends SapphireTest
{

    /**
     * Test the gridfield Summary method
     */
    public function testGridfieldSummary()
    {
        $shortString = 'abcdefghijklmnopqrstuvwxyz';
        $longString = '';
        // Generate a string of 350 characters. Because we don't care about the actual content
        // A set of a's is good enough;
        for ($i = 0; $i <= 350; $i++) {
            $longString .= 'a';
        }
        $logEntry = LogEntry::create();
        $logEntry->Entry = $shortString;
        $this->assertEquals($shortString, $logEntry->getGridfieldSummary());
        $logEntry->Entry = $longString;
        $elipsisString = substr($longString, 0, 300) . '...';
        $this->assertEquals($elipsisString, $logEntry->getGridfieldSummary());
        $this->assertEquals($longString, $logEntry->getGridfieldSummary(351));
    }

    /**
     * Test if the fields are in the fieldlist
     * A literal field is returning null in getCMSFields.
     */
    public function testGetCMSFieldsFormattedEntry()
    {
        $logEntry = LogEntry::create(array(
            'Level' => 'INFO',
            'Entry' => Convert::array2json(array('Test' => 'Message'))
        ));
        $fields = $logEntry->getCMSFields();
        $levelField = $fields->dataFieldByName('Level');
        $this->assertEquals("SilverStripe\\Forms\\TextField", $levelField->class);
    }

    /**
     * Test if the field shows when not formatting
     */
    public function testGetCMSFieldsUnformattedEntry()
    {
        Config::inst()->update('SilverLeague\LogViewer\Model\LogEntry', 'format_entry', false);
        $logEntry = LogEntry::create(array(
            'Level' => 'INFO',
            'Entry' => Convert::array2json(array('Test' => 'Message'))
        ));
        $fields = $logEntry->getCMSFields();
        $entryField = $fields->dataFieldByName('Entry');
        $this->assertEquals("SilverStripe\\Forms\\TextareaField", $entryField->class);
    }

    /**
     * Test the entire build of a formatted entry
     */
    public function testBuildEntry()
    {
        $entry = array(
            'Message'  => 'Something went wrong',
            'DateTime' => '2016-01-22 03:14:23'
        );
        $logEntry = LogEntry::create(array(
            'Level' => 'INFO',
            'Entry' => Convert::array2json($entry)
        ));
        $result = Invoker::invokeMethod($logEntry, 'buildEntry', array());
        $expectedResult = "<pre class='form-control-static logentry-entry' style='white-space: normal; max-width: 85%;'>".
            '<ul style="margin-bottom: 0"><li class="list-unstyled"><span style="color: #0000BB">Message</span>'.
            '<span style="color: #007700">: </span><span style="color: #DD0000">Something went wrong</span></li>'.
            '<li class="list-unstyled"><span style="color: #0000BB">DateTime</span>'.
            '<span style="color: #007700">: </span><span style="color: #DD0000">2016-01-22 03:14:23</span></li>'.
            '</ul></pre>';
        $this->assertEquals($expectedResult, $result);
    }
}
