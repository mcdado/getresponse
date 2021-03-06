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

namespace GetResponse\Export;

use GetResponse\Contact\ContactCustomFieldCollectionFactory;
use GetResponse\Customer\CustomerFactory;
use GetResponse\CustomFields\CustomFieldService;
use GetResponse\Order\OrderFactory;
use GrShareCode\Contact\ContactCustomField\ContactCustomFieldsCollection;
use GrShareCode\Export\Command\ExportContactCommand;
use GrShareCode\Export\ExportContactService;
use GrShareCode\Export\Settings\EcommerceSettings as ShareCodeEcommerceSettings;
use GrShareCode\Export\Settings\ExportSettings as ShareCodeExportSettings;
use GrShareCode\GrShareCodeException;
use GrShareCode\Order\OrderCollection;
use Order;

/**
 * Class ExportService
 * @package GetResponse\Export
 */
class ExportService
{
    /** @var ExportRepository */
    private $exportRepository;
    /** @var ExportContactService */
    private $shareCodeExportContactService;
    /** @var OrderFactory */
    private $orderFactory;

    /** @var CustomFieldService */
    private $customFieldService;

    /** @var ContactCustomFieldCollectionFactory */
    private $contactCustomFieldCollectionFactory;

    /**
     * @param ExportRepository $exportRepository
     * @param ExportContactService $shareCodeExportContactService
     * @param OrderFactory $orderFactory
     * @param CustomFieldService $customFieldService
     * @param ContactCustomFieldCollectionFactory $contactCustomFieldCollectionFactory
     */
    public function __construct(
        ExportRepository $exportRepository,
        ExportContactService $shareCodeExportContactService,
        OrderFactory $orderFactory,
        CustomFieldService $customFieldService,
        ContactCustomFieldCollectionFactory $contactCustomFieldCollectionFactory
    ) {
        $this->exportRepository = $exportRepository;
        $this->shareCodeExportContactService = $shareCodeExportContactService;
        $this->orderFactory = $orderFactory;
        $this->customFieldService = $customFieldService;
        $this->contactCustomFieldCollectionFactory = $contactCustomFieldCollectionFactory;
    }

    /**
     * @param ExportSettings $exportSettings
     * @throws \PrestaShopDatabaseException
     */
    public function export(ExportSettings $exportSettings)
    {
        $contacts = $this->exportRepository->getContacts($exportSettings->isNewsletterSubsIncluded());

        if (!count($contacts)) {
            return;
        }

        if ($exportSettings->isUpdateContactInfo()) {
                $customFieldMappingCollection = $this->customFieldService->getActiveCustomFieldMapping();
        }

        $shareCodeExportSettings = new ShareCodeExportSettings(
            $exportSettings->getContactListId(),
            $exportSettings->getCycleDay(),
            new ShareCodeEcommerceSettings(
                $exportSettings->isEcommerce(),
                $exportSettings->getShopId()
            )
        );

        foreach ($contacts as $contact) {
            $shareCodeOrderCollection = new OrderCollection();

            if (0 == $contact['id']) {
                // flow for newsletters subscribers
                $customer = CustomerFactory::createFromNewsletter($contact['email']);
            } else {
                $customer = CustomerFactory::createFromArray($contact);

                $customerOrders = $this->exportRepository->getOrders($contact['id']);

                foreach ($customerOrders as $customerOrder) {
                    $shareCodeOrderCollection->add(
                        $this->orderFactory->createShareCodeOrderFromOrder(new Order($customerOrder['id_order']))
                    );
                }
            }

            if ($exportSettings->isUpdateContactInfo()) {
                $contactCustomFieldCollection = $this->contactCustomFieldCollectionFactory
                    ->createFromContactAndCustomFieldMapping(
                        $customer,
                        $customFieldMappingCollection,
                        $exportSettings->isUpdateContactInfo()
                    );
            } else {
                $contactCustomFieldCollection = new ContactCustomFieldsCollection();
            }

            try {
                $this->shareCodeExportContactService->exportContact(
                    new ExportContactCommand(
                        $customer->getEmail(),
                        $customer->getName(),
                        $shareCodeExportSettings,
                        $contactCustomFieldCollection,
                        $shareCodeOrderCollection
                    )
                );
            } catch (GrShareCodeException $e) {
                \PrestaShopLoggerCore::addLog(
                    'Getresponse export error: ' . $e->getMessage(),
                    2,
                    null,
                    'GetResponse',
                    'GetResponse'
                );
            }
        }
    }
}
