<?php

namespace SilverLeague\LogViewer\Tests\Admin;

use SilverLeague\LogViewer\Admin\LogViewerAdmin;
use SilverStripe\Dev\FunctionalTest;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\ReadonlyField;

/**
 * @coversDefaultClass \SilverLeague\LogViewer\Admin\LogViewerAdmin
 * @package silverstripe-logviewer
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class LogViewerAdminTest extends FunctionalTest
{
    /**
     * {@inheritDoc}
     */
    protected static $fixture_file = 'LogViewerAdminTest.yml';

    /**
     * The test subject
     *
     * @var LogViewerAdmin
     */
    protected $logViewerAdmin;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->logViewerAdmin = new LogViewerAdmin;
        $this->logViewerAdmin->doInit();
    }

    /**
     * Test that the log entries are returned in reverse order of creation date/time
     *
     * @covers ::getList
     */
    public function testLogsShouldBeInReverseOrder()
    {
        $entries = $this->logViewerAdmin->getList();
        $this->assertSame('INFO', $entries->first()->Level);
        $this->assertSame('DEBUG', $entries->last()->Level);
    }

    /**
     * Test that the GridField "add new" button has been removed
     *
     * @covers ::getEditForm
     * @covers ::getGridFieldName
     */
    public function testNoAddButton()
    {
        /** @var \SilverStripe\Forms\GridField\GridFieldConfig $gridFieldConfig */
        $gridFieldConfig = $this->logViewerAdmin
            ->getEditForm()
            ->Fields()
            ->fieldByName($this->logViewerAdmin->getGridFieldName())
            ->getConfig();

        $this->assertNull($gridFieldConfig->getComponentByType(GridFieldAddNewButton::class));
    }

    /**
     * Test that the entry and level fields are displayed in the GridField, and can be exported
     */
    public function testEntryAndLevelShouldBeInSummaryFields()
    {
        $summaryFields = $this->logViewerAdmin->getExportFields();
        $this->assertContains('Entry', $summaryFields);
        $this->assertContains('Level', $summaryFields);
    }
}
