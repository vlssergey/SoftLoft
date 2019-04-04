<?php

namespace Alex\SoftLoft\Model\Plugin\Product;

use Magento\Catalog\Api\Data\ProductExtensionFactory;
use Magento\Framework\EntityManager\EntityManager;
use Alex\SoftLoft\Api\SalesInformationProviderInterface;

class Repository
{
    /**
     * @var ProductExtensionFactory
     */
    private $productExtensionFactory;

    /**
     * @var  EntityManager
     */
    private $entityManager;

    /**
     * @var SalesInformationProviderInterface
     */
    private $salesInformationProvider;

    /**
     * Repository constructor.
     * @param ProductExtensionFactory $productExtensionFactory
     * @param EntityManager $entityManager
     * @param SalesInformationProviderInterface $salesInformationProvider
     */
    public function __construct(
        ProductExtensionFactory $productExtensionFactory,
        EntityManager $entityManager,
        SalesInformationProviderInterface $salesInformationProvider
    ) {
        $this->productExtensionFactory = $productExtensionFactory;
        $this->entityManager = $entityManager;
        $this->salesInformationProvider = $salesInformationProvider;
    }

    /**
     * Add Sales Information to product extension attributes
     *
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $subject
     * @param \Magento\Framework\Api\SearchResults $searchResult
     * @return \Magento\Framework\Api\SearchResults
     */
    public function afterGetList(
        \Magento\Catalog\Api\ProductRepositoryInterface $subject,
        \Magento\Framework\Api\SearchResults $searchResult
    ) {
        /** @var \Magento\Catalog\Api\Data\ProductInterface $product */
        foreach ($searchResult->getItems() as $product) {
            $this->addSalesInfoToProduct($product);
        }
        return $searchResult;
    }

    /**
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $subject
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    public function afterGet(
        \Magento\Catalog\Api\ProductRepositoryInterface $subject,
        \Magento\Catalog\Api\Data\ProductInterface $product
    ) {
        $this->addSalesInfoToProduct($product);
        return $product;
    }

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return self
     */
    private function addSalesInfoToProduct(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $extensionAttributes = $product->getExtensionAttributes();
        if (empty($extensionAttributes)) {
            $extensionAttributes = $this->productExtensionFactory->create();
        }
        $salesInfo = $this->salesInformationProvider->getSalesInformation($product->getId());
        $extensionAttributes->setSalesInformation($salesInfo);
        $product->setExtensionAttributes($extensionAttributes);

        return $this;
    }
}
