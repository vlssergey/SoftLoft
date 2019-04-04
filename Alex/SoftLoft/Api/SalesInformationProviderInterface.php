<?php

namespace Alex\SoftLoft\Api;

interface SalesInformationProviderInterface
{
    /**
     * @param int $productId
     * @return \Alex\SoftLoft\Model\SalesInformation
     */
    public function getSalesInformation(int $productId);
}
