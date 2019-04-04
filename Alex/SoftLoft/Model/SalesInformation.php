<?php

namespace Alex\SoftLoft\Model;

use Alex\SoftLoft\Api\Data\SalesInformationInterface;

class SalesInformation implements SalesInformationInterface
{
    /**
     * @var  string
     */
    private $lastOrder;

    /**
     * @var  int
     */
    private $qtySold;

    /**
     * @var  \Alex\SoftLoft\Api\Data\SalesInformationInterface|null
     */
    private $extenstionAttributes;

    /**
     * Retrieve Last Order
     *
     * @return string
     */
    public function getLastOrder()
    {
        return $this->lastOrder;
    }

    /**
     * Set Last Order
     *
     * @param string $lastOrder
     * @return self
     */
    public function setLastOrder(string $lastOrder)
    {
        $this->lastOrder = $lastOrder;
        return $this;
    }

    /**
     * @return int
     */
    public function getQtySold()
    {
        return $this->qtySold;
    }

    /**
     * @param int $qtySold
     * @return self
     */
    public function setQtySold(int $qtySold)
    {
        $this->qtySold = $qtySold;
        return $this;
    }

    /**
     * @return \Alex\SoftLoft\Api\Data\SalesInformationInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->extenstionAttributes;
    }

    /**
     * @param \Alex\SoftLoft\Api\Data\SalesInformationInterface $extensionAttributes
     * @return self
     */
    public function setExtensionAttributes(
        \Alex\SoftLoft\Api\Data\SalesInformationInterface $extensionAttributes
    ) {
        $this->extenstionAttributes = $extensionAttributes;
        return $this;
    }
}
