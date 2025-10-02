<?php
if (!class_exists(\Magento\Framework\Component\ComponentRegistrar::class)) {
    return;
}
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'HouseOfApis_CurrencyApi',
    __DIR__
);


