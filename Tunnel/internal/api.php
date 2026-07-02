<?php
/**
 * Great Endured Technology — Internal API Router
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);

use Back\Controllers\ContactController;

$controller = new ContactController();
$controller->submit();
