<?php
declare(strict_types=1);

namespace Magento\Directory\Model;

interface CurrencyFactory
{
    /**
     * Create currency model
     *
     * @return Currency
     */
    public function create();
}
