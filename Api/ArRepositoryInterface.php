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

namespace ImaginationMedia\Aero\Api;

use ImaginationMedia\Aero\Api\Data\ArInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface ArRepositoryInterface
{
    /**
     * Save item.
     * @param $item
     * @return ArInterface
     * @throws CouldNotSaveException
     */
    public function save($item) : ArInterface;

    /**
     * Get item
     * @param int $itemId
     * @return ArInterface
     * @throws NoSuchEntityException
     */
    public function getById($itemId) : ArInterface;

    /**
     * Retrieve item list
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria) : SearchResultsInterface;

    /**
     * Delete item from database
     * @param $item
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete($item) : bool;

    /**
     * Get new empty item
     * @return ArInterface
     */
    public function getEmptyItem() : ArInterface;

    /**
     * Get AR data by product id
     * @param int $productId
     * @return array
     */
    public function getArByProduct(int $productId) : array;
}
