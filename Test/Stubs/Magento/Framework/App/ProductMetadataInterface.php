<?php
declare(strict_types=1);

namespace Magento\Framework\App;

interface ProductMetadataInterface
{
    /**
     * Get product version
     *
     * @return string
     */
    public function getVersion();

    /**
     * Get product edition
     *
     * @return string
     */
    public function getEdition();

    /**
     * Get product name
     *
     * @return string
     */
    public function getName();
}
