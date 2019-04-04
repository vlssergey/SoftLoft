<?php

namespace Alex\SoftLoft\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface SalesInfoInterface extends ExtensibleDataInterface
{
    const LAST_ORDER = "last_order";

    const QTY_SOLD = "qty_sold";

    const PRODUCT_ID = "product_id";

    const STATUS = "status";

    /**
     * Retrieve Last Order
     *
     * @return string
     */
    public function getLastOrder();

    /**
     * Set Last Order
     *
     * @param string $lastOrder
     * @return self
     */
    public function setLastOrder(string $lastOrder);

    /**
     * @return int
     */
    public function getQtySold();

    /**
     * @param int $qtySold
     * @return self
     */
    public function setQtySold(int $qtySold);

    /**
     * @return \Alex\SoftLoft\Api\Data\SalesInfoInterface|null
     */
    public function getExtensionAttributes();

    /**
     * @param \Alex\SoftLoft\Api\Data\SalesInfoInterface $extensionAttributes
     * @return self
     */
    public function setExtensionAttributes(SalesInfoInterface $extensionAttributes);
}
