<?php
declare(strict_types=1);

namespace Magento\Framework\App\Config;

interface ScopeConfigInterface
{
    /**
     * Retrieve config value by path and scope.
     *
     * @param string $path
     * @param string $scopeType
     * @param string|null $scopeCode
     * @return mixed
     */
    public function getValue($path, $scopeType = null, $scopeCode = null);
}
