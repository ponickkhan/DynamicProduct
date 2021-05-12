<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Strativ\DynamicProduct\Controller\Adminhtml\System\Config;

use \Magento\Catalog\Model\Product\Visibility;

class Create extends \Magento\Backend\App\Action
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->_logger = $logger;
        parent::__construct($context);
    }


    /**
     * Create
     *
     * @return void
     */
    public function execute()
    {
        $this->_logger->debug('Creating Products!!');
        // do whatever you want to do

        $products_import = file_get_contents("http://localhost/magento/json_data/products_import.json");
        $json_data = json_decode($products_import, true);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        foreach ($json_data as $products => $product) {

            foreach ($product as $id => $value) {
                
                $create_product = $objectManager->create('\Magento\Catalog\Model\Product');
                $create_product->setSku($product[$id]['sku']); 
                $create_product->setName($product[$id]['name']); 
                $create_product->setDescription($product[$id]['description']);
                $create_product->setShortDescription($product[$id]['short_description']);
                $create_product->setAttributeSetId(4); 
                $create_product->setStatus($product[$id]['status']); 
                $create_product->setWeight($product[$id]['weight']); 
                $create_product->setVisibility(4); 
                $create_product->setTaxClassId(0); 
                $create_product->setTypeId('simple'); 
                $create_product->setPrice($product[$id]['price']); 
                $create_product->setCost($product[$id]['special_price']) ;
                $create_product->setSpecialPrice($product[$id]['cost_price']) ;
                $create_product->setStockData(
                                        array(
                                            'use_config_manage_stock' => 0,
                                            'manage_stock' => $product[$id]['manage_stock'],
                                            'min_sale_qty'=>$product[$id]['min_sale_qty'], 
                                            'max_sale_qty'=>$product[$id]['max_sale_qty'], 
                                            'is_in_stock' => $product[$id]['is_in_stock'],
                                            'qty' => $product[$id]['qty']
                                        )
                                    );
               
                $create_product->save();

            }
            
        }

        $this->_logger->debug('Product created!!');
        
    }
}