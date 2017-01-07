<?php

namespace SilverLeague\LogViewer\Tests\Model;

use SilverLeague\LogViewer\Model\LogEntry;
use SilverStripe\Dev\SapphireTest;

/**
 * @package silverstripe-logviewer
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class LogEntryTest extends SapphireTest
{
    /**
     * Test that LogEntry classes can not be edited
     *
     * @covers ::canEdit
     */
    public function testDoNotAllowEditing()
    {
        $this->assertFalse(LogEntry::create()->canEdit());
    }
}
