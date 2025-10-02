<?php
declare(strict_types=1);

namespace Magento\Directory\Model;

interface Currency
{
    /**
     * Set currency ID
     *
     * @param string $id
     * @return Currency
     */
    public function setId($id);

    /**
     * Set currency rates
     *
     * @param array $rates
     * @return Currency
     */
    public function setRates(array $rates);

    /**
     * Save currency
     *
     * @return Currency
     */
    public function save();

    /**
     * Get allowed currencies
     *
     * @return array
     */
    public function getConfigAllowCurrencies();

    /**
     * Get base currencies
     *
     * @return array
     */
    public function getConfigBaseCurrencies();
}
