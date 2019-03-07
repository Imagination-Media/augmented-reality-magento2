<?php

/**
 * Aero
 *
 * Add augmented reality to Magento using Adobe Aero and Apple ArKit.
 *
 * @package ImaginationMedia\Aero
 * @author Igor Ludgero Miura <igor@imaginationmedia.com>
 * @copyright Copyright (c) 2019 Imagination Media (https://www.imaginationmedia.com/)
 * @license https://opensource.org/licenses/OSL-3.0.php Open Software License 3.0
 */

namespace ImaginationMedia\Aero\Model;

use ImaginationMedia\Aero\Api\ArRepositoryInterface;
use ImaginationMedia\Aero\Api\Data\ArInterface;
use ImaginationMedia\Aero\Api\Data\ArInterfaceFactory;
use ImaginationMedia\Aero\Model\ResourceModel\AugmentedReality as ResourceModel;
use ImaginationMedia\Aero\Model\ResourceModel\AugmentedReality\CollectionFactory;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class AugmentedRealityRepository implements ArRepositoryInterface
{
    /**
     * @var SearchResultsInterface
     */
    private $searchResults;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var ArInterfaceFactory
     */
    private $factory;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var ResourceModel
     */
    private $resourceModel;

    /**
     * AugmentedRealityRepository constructor.
     * @param SearchResultsInterface $searchResults
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     * @param ArInterfaceFactory $factory
     * @param CollectionFactory $collectionFactory
     * @param ResourceModel $resourceModel
     */
    public function __construct(
        SearchResultsInterface $searchResults,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        ArInterfaceFactory $factory,
        CollectionFactory $collectionFactory,
        ResourceModel $resourceModel
    ) {
        $this->searchResults = $searchResults;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->factory = $factory;
        $this->collectionFactory = $collectionFactory;
        $this->resourceModel = $resourceModel;
    }

    /**
     * Save item.
     * @param $item
     * @return ArInterface
     * @throws CouldNotSaveException
     */
    public function save($item) : ArInterface
    {
        try {
            $this->resourceModel->save($item);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $item;
    }

    /**
     * Get item
     * @param int $itemId
     * @return ArInterface
     * @throws NoSuchEntityException
     */
    public function getById($itemId) : ArInterface
    {
        $item = $this->factory->create();
        $this->resourceModel->load($item, $itemId);
        if (!$item->getId()) {
            throw new NoSuchEntityException(__('Object with id "%1" does not exist.', $itemId));
        }
        return $item;
    }

    /**
     * Retrieve item list
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria) : SearchResultsInterface
    {
        $searchResults = $this->searchResults;
        $searchResults->setSearchCriteria($searchCriteria);
        $collection = $this->collectionFactory->create();

        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fields = [];
            $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
                $fields[] = $filter->getField();
                $conditions[] = [$condition => $filter->getValue()];
            }
            if ($fields) {
                $collection->addFieldToFilter($fields, $conditions);
            }
        }

        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $searchCriteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $tokens = [];

        foreach ($collection as $tokenModel) {
            $tokens[] = $tokenModel->getData();
        }

        $this->searchResults->setItems($tokens);
        return $this->searchResults;
    }

    /**
     * Delete item from database
     * @param $item
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete($item) : bool
    {
        try {
            $this->resourceModel->delete($item);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Get new empty item
     * @return ArInterface
     */
    public function getEmptyItem() : ArInterface
    {
        return $this->factory->create();
    }

    /**
     * Get AR data by product id
     * @param int $productId
     * @return array
     */
    public function getArByProduct(int $productId) : array
    {
        /**
         * @var $collection \ImaginationMedia\Aero\Model\ResourceModel\AugmentedReality\Collection
         */
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter("product_id", $productId);

        if ($collection->count() > 0) {
            return $collection->getFirstItem()->getData();
        }
        return array();
    }
}
