<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_ConfigurableSwatches
 * @copyright  Copyright (c) 2006-2014 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Olegnax_Colorswatches_Block_Catalog_Media_Js_List extends Mage_Core_Block_Template
{
    protected $_template = 'olegnax/colorswatches/catalog/media/js.phtml';

    /**
     * json encode image fallback array
     *
     * @param array $imageFallback
     * @return string
     */
    protected function _getJsImageFallbackString(array $imageFallback) {
        /* @var $coreHelper Mage_Core_Helper_Data */
        $coreHelper = Mage::helper('core');

        return $coreHelper->jsonEncode($imageFallback);
    }

    /**
     * Get image fallbacks by product as
     * array(product ID => array( product => product, image_fallback => image fallback ) )
     *
     * @return array
     */
    public function getProductImageFallbacks($keepFrame = null) {
        /* @var $helper Mage_ConfigurableSwatches_Helper_Mediafallback */
        $helper = Mage::helper('olegnaxcolorswatches/mediafallback');

        $fallbacks = array();

        $products = $this->getProducts();

        if ($keepFrame === null) {
            $listBlock = $this->getLayout()->getBlock('product_list');
            if ($listBlock && $listBlock->getMode() == 'grid') {
                $keepFrame = true;
            } else {
                $keepFrame = false;
            }
        }

        /* @var $product Mage_Catalog_Model_Product */
        foreach ($products as $product) {
            $imageFallback = $helper->getConfigurableImagesFallbackArray($product, $this->_getImageSizes(), $keepFrame);

            $fallbacks[$product->getId()] = array(
                'product' => $product,
                'image_fallback' => $this->_getJsImageFallbackString($imageFallback)
            );
        }

        return $fallbacks;
    }

    /**
     * Prevent actual block render if we are disabled, and i.e. via the module
     * config as opposed to the advanced module settings page
     *
     * @return string
     */
    protected function _toHtml() {
        if (!Mage::helper('olegnaxcolorswatches')->isEnabled()) { // functionality disabled
            return ''; // do not render block
        }
        return parent::_toHtml();
    }
    
    /**
     * Get target product IDs from product collection
     * which was set on block
     *
     * @return array
     */
    public function getProducts() {
        return $this->getProductCollection();
    }

    /**
     * Default to small image type
     *
     * @return string
     */
    public function getImageType() {
        $type = parent::getImageType();

        if (empty($type)) {
            $type = Olegnax_Colorswatches_Helper_Productimg::MEDIA_IMAGE_TYPE_SMALL;
        }

        return $type;
    }

    /**
     * instruct small_image image type to be loaded
     *
     * @return array
     */
    protected function _getImageSizes() {
        return array('small_image');
    }
}
