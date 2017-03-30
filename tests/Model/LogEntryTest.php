<?php

namespace SilverLeague\LogViewer\Tests\Model;

use SilverStripe\Dev\SapphireTest;
use SilverLeague\LogViewer\Model\LogEntry;
use SilverStripe\Security\Security;

/**
 * @package silverstripe-logviewer
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class LogEntryTest extends SapphireTest
{
    /**
     * Test if the Permissions are an array and contain the view and delete permissions
     */
    public function testProvidePermissions()
    {
        $permissions = LogEntry::create()->providePermissions();
        $this->assertTrue(is_array($permissions));
        $this->assertTrue(array_key_exists('DELETE_ENTRY', $permissions));
        $this->assertTrue(array_key_exists('VIEW_ENTRY', $permissions));
    }

    /**
     * There's no reason to manually create, so don't allow manual creation
     */
    public function testAllowCreate()
    {
        $createFalse = LogEntry::create()->canCreate(null);
        $this->assertFalse($createFalse);
        $this->logInWithPermission('ADMIN');
        $createFalse = LogEntry::create()->canCreate();
        $this->assertFalse($createFalse);
    }

    /**
     * Test that LogEntry classes can not be edited
     */
    public function testAllowEditing()
    {
        $this->assertFalse(LogEntry::create()->canEdit());
    }

    /**
     * We can view if we're logged in as admin. Otherwise, no.
     */
    public function testAllowView()
    {
        $viewFalse = LogEntry::create()->canView(null);
        $this->assertFalse($viewFalse);
        $this->logInWithPermission('ADMIN');
        $viewTrue = LogEntry::create()->canView();
        $this->assertTrue($viewTrue);
    }

    /**
     * We can Delete if we're logged in as admin. Otherwise, no.
     */
    public function testAllowDelete()
    {
        $deleteFalse = LogEntry::create()->canDelete(null);
        $this->assertFalse($deleteFalse);
        $this->logInWithPermission('ADMIN');
        $deleteTrue = LogEntry::create()->canDelete();
        $this->assertTrue($deleteTrue);
    }
}
