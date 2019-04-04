<?php

namespace Alex\SoftLoft\Model\SalesInformation;

use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\App\ResourceConnection;
use Alex\SoftLoft\Api\SalesInformationProviderInterface;
use Alex\SoftLoft\Model\SalesInformationFactory;
use Alex\SoftLoft\Api\Data\SalesInformationInterface;

class Provider implements SalesInformationProviderInterface
{
    /**
     * @var  EntityManager
     */
    private $entityManager;

    /**
     * @var  SalesInformationFactory
     */
    private $salesInformationFactory;

    /**
     * @var \Alex\SoftLoft\Model\SalesInformation
     */
    private $salesInformation;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var string
     */
    private $orderStatusFilter;

    /**
     * Provider constructor.
     * @param EntityManager $entityManager
     * @param ResourceConnection $resourceConnection
     * @param SalesInformationFactory $salesInformationFactory
     * @param $orderStatusFilter
     */
    public function __construct(
        EntityManager $entityManager,
        ResourceConnection $resourceConnection,
        SalesInformationFactory $salesInformationFactory,
        $orderStatusFilter
    ) {
        $this->entityManager = $entityManager;
        $this->resourceConnection = $resourceConnection;
        $this->salesInformationFactory = $salesInformationFactory;
        $this->orderStatusFilter = $orderStatusFilter;
    }

    /**
     * @inheritdoc
     */
    public function getSalesInformation(int $productId)
    {
        $salesInformationEntity = $this->getSalesInformationEntity();
        $salesInformationEntity->setLastOrder(
            $this->getLastOrder($productId)
        )->setQtySold(
            $this->getQtySold($productId)
        );

        return $salesInformationEntity;
    }

    /**
     * @param $productId
     * @return string|null
     */
    private function getLastOrder(int $productId)
    {
        $expr = 'MAX(cast(sales_order_item.created_at as Datetime))';
        $result = $this->salesInfoProcessor($productId, $expr, SalesInformationInterface::LAST_ORDER);

        return $result ?: null;
    }

    /**
     * @param $productId
     * @return int|null
     */
    private function getQtySold(int $productId)
    {
        $expr = 'SUM(sales_order_item.qty_ordered)';
        $result = $this->salesInfoProcessor($productId, $expr, SalesInformationInterface::QTY_SOLD);

        return $result ?: 0;
    }

    /**
     * @param int $productId
     * @param string $expr
     * @param string $alias
     * @return string
     */
    private function salesInfoProcessor(int $productId, string $expr = '', string $alias = '')
    {
        $connection = $this->resourceConnection->getConnection();
        $status = $this->getOrderStatusFilter();

        $select = $connection
            ->select()
            ->from(
                ['sales_order_item' => $connection->getTableName('sales_order_item')],
                [$alias => new \Zend_Db_Expr($expr)]
            )
            ->joinInner(
                ['sales_order' => $connection->getTableName('sales_order')],
                'sales_order.entity_id = sales_order_item.order_id',
                null
            )
            ->where(SalesInformationInterface::PRODUCT_ID . ' = ?', $productId)
            ->where(SalesInformationInterface::STATUS . ' = ?', $status);

        $result = $connection->fetchOne($select);

        return $result;
    }

    /**
     * @return \Alex\SoftLoft\Model\SalesInformation
     */
    private function getSalesInformationEntity()
    {
        if (is_null($this->salesInformation)) {
            $this->salesInformation = $this->salesInformationFactory->create();
        }

        return $this->salesInformation;
    }

    /**
     * @return string
     */
    private function getOrderStatusFilter()
    {
        return $this->orderStatusFilter;
    }
}
