<?php
declare(strict_types=1);

namespace Magento\Framework\HTTP;

interface LaminasClientFactory
{
    /**
     * Create Laminas HTTP client
     *
     * @return LaminasClient
     */
    public function create();
}
