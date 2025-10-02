<?php
declare(strict_types=1);

// Include Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Global __() function stub for Magento translations
if (!function_exists('__')) {
    function __(string $text, ...$arguments): \Magento\Framework\Phrase
    {
        return new \Magento\Framework\Phrase($text, $arguments);
    }
}
