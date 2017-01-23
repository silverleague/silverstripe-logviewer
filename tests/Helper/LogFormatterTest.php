<?php

namespace SilverLeague\LogViewer\Tests\Helper;

use SilverLeague\LogViewer\Deprecated\DeprecatedLogFormatter;
use SilverLeague\LogViewer\Helper\LogFormatter;
use SilverLeague\LogViewer\Model\LogEntry;
use SilverStripe\Core\Convert;
use SilverStripe\Dev\SapphireTest;

/**
 * @author Simon Erkelens <simon@casa-laguna.net>
 */
class LogFormatterTest extends SapphireTest
{

    /**
     * Test if the entrty is formatted nicely
     */
    public function testEntryToUl()
    {
        $entry = array(
            'Message'  => 'Something went wrong',
            'DateTime' => '2016-01-22 03:14:23'
        );
        $result = LogFormatter::entryToUl($entry);
        $expectedString = '<span style="color: #007700">[</span>'.
            '<ul style="margin-bottom: 0"><li class="list-unstyled">'.
            '<span style="color: #0000BB">Message</span><span style="color: #007700">: </span>'.
            '<span style="color: #DD0000">Something went wrong</span></li>'.
            '<li class="list-unstyled"><span style="color: #0000BB">DateTime</span>'.
            '<span style="color: #007700">: </span><span style="color: #DD0000">2016-01-22 03:14:23</span></li>'.
            '</ul><span style="color: #007700">]</span>';

        $this->assertEquals($expectedString, $result);
    }

}