<?php
/**
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author     Getresponse <grintegrations@getresponse.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

namespace GetResponse\CustomFields;

use GetResponse\CustomFieldsMapping\CustomFieldMapping;
use GetResponse\CustomFieldsMapping\CustomFieldMappingCollection;
use GetResponse\CustomFieldsMapping\CustomFieldMappingException;

/**
 * Class CustomFieldService
 */
class CustomFieldService
{
    /** @var CustomFieldsRepository */
    private $repository;

    /**
     * @param CustomFieldsRepository $repository
     */
    public function __construct(CustomFieldsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function setDefaultCustomFieldsMapping($storeId = null)
    {
        $customFields = DefaultCustomFields::DEFAULT_CUSTOM_FIELDS;

        $collection = new CustomFieldMappingCollection();
        foreach ($customFields as $field) {
            $collection->add(new CustomFieldMapping(
                $field['id'],
                $field['custom_name'],
                $field['customer_property_name'],
                $field['gr_custom_id'],
                $field['is_active'],
                $field['is_default']
            ));
        }

        $this->repository->updateCustomFields($collection, $storeId);
    }

    public function clearCustomFields()
    {
        $this->repository->clearCustomFields();
    }

    /**
     * @return CustomFieldMappingCollection
     */
    public function getCustomFieldsMapping()
    {
        return $this->repository->getCustomFieldsMapping();
    }

    /**
     * @param CustomFieldMapping $newCustomFieldMapping
     */
    public function updateCustom(CustomFieldMapping $newCustomFieldMapping)
    {
        $newMappingCollection = new CustomFieldMappingCollection();
        $mappingCollection = $this->getCustomFieldsMapping();

        foreach ($mappingCollection as $customFieldMapping) {
            if ($customFieldMapping->getId() === $newCustomFieldMapping->getId()) {
                $newMappingCollection->add($newCustomFieldMapping);
            } else {
                $newMappingCollection->add($customFieldMapping);
            }
        }

        $this->repository->updateCustomFields($newMappingCollection);
    }

    /**
     * @param CustomFieldMapping $newCustomFieldMapping
     * @throws CustomFieldMappingException
     */
    public function updateCustomFieldMapping(CustomFieldMapping $newCustomFieldMapping)
    {
        $customFieldMapping = $this->getCustomFieldMappingById($newCustomFieldMapping->getId());

        if (!$customFieldMapping) {
            throw CustomFieldMappingException::createForNotFoundCustomFieldMapping($newCustomFieldMapping->getId());
        }

        if ($customFieldMapping->isDefault()) {
            throw CustomFieldMappingException::createForDefaultCustomFieldMapping($newCustomFieldMapping->getId());
        }

        $this->updateCustom($newCustomFieldMapping);
    }

    /**
     * @param int $customFieldMappingId
     * @return CustomFieldMapping|null
     */
    public function getCustomFieldMappingById($customFieldMappingId)
    {
        foreach ($this->repository->getCustomFieldsMapping() as $customFieldMapping) {
            if ($customFieldMappingId == $customFieldMapping->getId()) {
                return $customFieldMapping;
            }
        }

        return null;
    }

    /**
     * @return CustomFieldMappingCollection
     */
    public function getActiveCustomFieldMapping()
    {
        $customFieldMappingCollection = new CustomFieldMappingCollection();

        foreach ($this->repository->getCustomFieldsMapping() as $customFieldMapping) {
            if (!$customFieldMapping->isDefault() && $customFieldMapping->isActive()) {
                $customFieldMappingCollection->add($customFieldMapping);
            }
        }

        return $customFieldMappingCollection;
    }
}
