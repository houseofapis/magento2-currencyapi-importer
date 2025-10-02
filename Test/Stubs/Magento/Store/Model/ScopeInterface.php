<?php
declare(strict_types=1);

namespace Magento\Store\Model;

interface ScopeInterface
{
    const SCOPE_STORE = 'store';
    const SCOPE_WEBSITE = 'website';
    const SCOPE_GROUP = 'group';
    const SCOPE_DEFAULT = 'default';
}
