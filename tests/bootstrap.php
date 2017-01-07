<?php
/**
 * PHPUnit test bootstrapping
 *
 * @package silverstripe-logviewer
 * @author  Robbie Averill <robbie@averill.co.nz>
 */

require_once __DIR__ . '/../vendor/autoload.php';

if (empty($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = 'localhost';
}

require FRAMEWORK_PATH . '/tests/bootstrap/init.php';
require FRAMEWORK_PATH . '/tests/bootstrap/cli.php';
require FRAMEWORK_PATH . '/tests/bootstrap/environment.php';

// Mock mysite if not installed with silverstripe/installer
if (defined('BASE_PATH')) {
    $projectPath = BASE_PATH . '/mysite';
} else {
    $projectPath = getcwd() . '/mysite';
}
if (!is_dir($projectPath)) {
    mkdir($projectPath, 02775);
    mkdir($projectPath.'/code', 02775);
    mkdir($projectPath.'/_config', 02775);
}

require FRAMEWORK_PATH . '/tests/bootstrap/mysite.php';
require FRAMEWORK_PATH . '/tests/bootstrap/phpunit.php';
