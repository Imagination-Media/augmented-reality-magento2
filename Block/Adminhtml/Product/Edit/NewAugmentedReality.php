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

namespace ImaginationMedia\Aero\Block\Adminhtml\Product\Edit;

use ImaginationMedia\Aero\Api\ArRepositoryInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;

class NewAugmentedReality extends Generic
{
    /**
     * @var EncoderInterface
     */
    private $jsonEncoder;

    /**
     * @var ArRepositoryInterface
     */
    private $arRepository;

    /**
     * NewAugmentedReality constructor.
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param EncoderInterface $jsonEncoder
     * @param ArRepositoryInterface $arRepository
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        EncoderInterface $jsonEncoder,
        ArRepositoryInterface $arRepository,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->jsonEncoder = $jsonEncoder;
        $this->arRepository = $arRepository;
    }

    /**
     * @return Generic|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create([
            'data' => [
                'id' => 'new_ar_form',
                'class' => 'admin__scope-old',
                'enctype' => 'multipart/form-data',
            ]
        ]);
        $form->setUseContainer($this->getUseContainer());
        $fieldset = $form->addFieldset('new_video_form_fieldset', []);
        $fieldset->addField(
            '',
            'hidden',
            [
                'name' => 'form_key',
                'value' => $this->getFormKey(),
            ]
        );
        $fieldset->addField(
            'ar_file',
            'file',
            [
                'label' => __('Augmented Reality File'),
                'title' => __('Augmented Reality File'),
                'name' => 'ar_file',
            ]
        );
        $fieldset->addField(
            'ar_preview_file',
            'file',
            [
                'label' => __('Preview Image'),
                'title' => __('Preview Image'),
                'name' => 'ar_preview_file',
            ]
        );

        $this->setForm($form);
    }

    /**
     * Get controller url
     *
     * @return string
     */
    public function getControllerUrl(): string
    {
        return $this->getUrl('aero/media/upload');
    }

    /**
     * Retrieve currently viewed product object
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        if (!$this->hasData('product')) {
            $this->setData('product', $this->_coreRegistry->registry('product'));
        }
        return $this->getData('product');
    }

    /**
     * Get preview path
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPreviewArUrl(): string
    {
        $productId = $this->getProduct()->getId();
        if ($productId !== null) {
            $arData = $this->arRepository->getArByProduct($productId);
            if (key_exists("preview_path", $arData) &&
                (string)$arData["preview_path"] !== "") {
                return $this->_storeManager->getStore()
                        ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . "catalog/product/ar/" .
                        $arData["preview_path"];
            }
        }
        return "";
    }

    /**
     * Get AR file path
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getArFileUrl(): string
    {
        $productId = $this->getProduct()->getId();
        if ($productId !== null) {
            $arData = $this->arRepository->getArByProduct($productId);
            if (key_exists("ar_path", $arData) &&
                (string)$arData["ar_path"] !== "") {
                return $this->_storeManager->getStore()
                        ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . "catalog/product/ar/" .
                    $arData["ar_path"];
            }
        }
        return "";
    }
}
