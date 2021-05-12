<?php

namespace Strativ\DynamicProduct\Controller\Adminhtml\System\Config;

use \Magento\Catalog\Model\Product\Visibility;

class Update extends \Magento\Backend\App\Action
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
        $this->_logger->debug('Updating Products!!');
        
        $writer = new \Laminas\Log\Writer\Stream(BP . '/var/log/product_update.log');
        $logger = new \Laminas\Log\Logger();
        $logger->addWriter($writer);
        

        $products_import = file_get_contents("http://localhost/magento/json_data/products_update.json");
        $json_data = json_decode($products_import, true);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productObj = $objectManager->get('Magento\Catalog\Model\Product');
        foreach ($json_data as $products => $product) {

            foreach ($product as $id => $value) {



            if($productObj->getIdBySku($product[$id]['sku'])) {
                  
            
                
                $update_product = $objectManager->create('\Magento\Catalog\Api\ProductRepositoryInterface')->get($product[$id]['sku'],true, 0, true);
                
                $update_product->setStatus($product[$id]['status']); 
                $update_product->setPrice($product[$id]['price']); 
                $update_product->setSpecialPrice($product[$id]['cost_price']) ;
                $update_product->setStockData(
                                        array(
                                            'use_config_manage_stock' => 0,
                                            'manage_stock' => $product[$id]['manage_stock'],
                                            'min_sale_qty'=>$product[$id]['min_sale_qty'], 
                                            'max_sale_qty'=>$product[$id]['max_sale_qty'], 
                                            'is_in_stock' => $product[$id]['is_in_stock'],
                                            'qty' => $product[$id]['qty']
                                        )
                                    );
               
                $update_product->save();

                $logger->info('Product with SKU:'.$product[$id]['sku'].' has ben updated! price:'.$product[$id]['price'].' qty:'.$product[$id]['qty']);

            }

           } 
            
        }
    }
}