<?php

namespace SilverLeague\LogViewer\Tests\Forms\GridField;

use SilverLeague\LogViewer\Forms\GridField\GridFieldClearAllButton;
use SilverLeague\LogViewer\Model\LogEntry;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\ORM\DataList;

/**
 * Tests for the "clear all" GridField action class
 *
 * @coversDefaultClass \SilverLeague\LogViewer\Forms\GridField\GridFieldClearAllButton
 * @package silverstripe-logviewer
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class GridFieldClearAllButtonTest extends SapphireTest
{
    /**
     * {@inheritDoc}
     */
    protected $usesDatabase = true;

    protected $gridField;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();

        $config = GridFieldConfig::create()->addComponent(new GridFieldClearAllButton('before'));
        $this->gridField = GridField::create('logs', 'logs', DataList::create(LogEntry::class), $config);
        $form = Form::create(
            Controller::create(),
            'foobar',
            FieldList::create([$this->gridField]),
            FieldList::create()
        );
    }

    /**
     * Return the actual class we're testing
     *
     * @return GridFieldClearAllButton
     */
    protected function getSubject()
    {
        return $this->gridField->getConfig()->getComponentByType(GridFieldClearAllButton::class);
    }

    /**
     * Ensure that the HTML fragment was pushed correctly and assigned to the specified fragment (in setUp above)
     */
    public function testGetHtmlFragments()
    {
        $fragments = $this->getSubject()->getHTMLFragments($this->gridField);

        $this->assertArrayHasKey('before', $fragments);
        $this->assertContains('Clear all', $fragments['before']);
        $this->assertContains('clear-all-logs', $fragments['before']);
        $this->assertContains('font-icon-trash-bin action_clear', $fragments['before']);
        $this->assertContains('<p class="grid-clear-all-button">', $fragments['before']);
    }

    /**
     * Test that the GridFieldAction actions are returned correctly
     */
    public function testActionsAreDefined()
    {
        $this->assertSame(['clear'], (new GridFieldClearAllButton)->getActions($this->gridField));
    }

    /**
     * Test that an exception is thrown if the Member doesn't have permission to delete the data class assigned
     *
     * @expectedException \SilverStripe\ORM\ValidationException
     * @expectedExceptionMessage No permission to unlink record
     */
    public function testCannotClearAllWithoutPermission()
    {
        $forbiddenList = DataList::create(Stub\SomeDataObject::class);
        $this->gridField->setList($forbiddenList);

        $this->getSubject()->handleAction($this->gridField, 'clear', null, []);
    }

    /**
     * Test that with permission the list can be cleared
     */
    public function testClearList()
    {
        $this->logInWithPermission('ADMIN');

        $this->createDummyLogs();
        $this->assertSame(5, $this->gridField->getList()->count());

        $this->getSubject()->handleAction($this->gridField, 'clear', null, []);
        $this->assertSame(0, $this->gridField->getList()->count());
    }

    /**
     * Create a set of dummy LogEntry records
     *
     * @param int $limit
     */
    protected function createDummyLogs($limit = 5)
    {
        $factory = Injector::inst()->create('FixtureFactory');

        for ($i = 1; $i <= $limit; $i++) {
            $factory->createObject(
                LogEntry::class,
                'stub_log_' . $i,
                [
                    'Entry' => 'Log #' . $i,
                    'Level' => 'DEBUG'
                ]
            );
        }
    }
}
