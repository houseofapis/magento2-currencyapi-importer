<?php
declare(strict_types=1);

namespace Magento\Directory\Model\Currency\Import;

interface ImportInterface
{
    /**
     * Import rates
     *
     * @return ImportInterface
     */
    public function importRates();

    /**
     * Get messages
     *
     * @return array
     */
    public function getMessages();
}
