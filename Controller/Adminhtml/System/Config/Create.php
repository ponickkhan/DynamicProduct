<?php

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

        $products_import = file_get_contents("http://localhost/magento/json_data/products_import.json");
        $json_data = json_decode($products_import, true);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $fileSystem = $objectManager->create('\Magento\Framework\Filesystem');
        $mediaPath = $fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath();

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

                $imgurl = $product[$id]['image']; 
                $imagename= basename($imgurl);
                $image = $this->getimg($imgurl); 
                file_put_contents($mediaPath.'catalog/product/'.$imagename,$image); 

                $create_product->addImageToMediaGallery($mediaPath.'catalog/product/'.$imagename, array('image', 'small_image', 'thumbnail'), false, false);
               
                $create_product->save();

            }
            
        }

 
        
    }


   public function getimg($url) 
   {         
    $headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';              
    $headers[] = 'Connection: Keep-Alive';         
    $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';         
    $user_agent = 'php';         
    $process = curl_init($url);         
    curl_setopt($process, CURLOPT_HTTPHEADER, $headers);         
    curl_setopt($process, CURLOPT_HEADER, 0);         
    curl_setopt($process, CURLOPT_USERAGENT, $user_agent); //check here         
    curl_setopt($process, CURLOPT_TIMEOUT, 30);         
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);         
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);         
    $return = curl_exec($process);         
    curl_close($process);         
    return $return;     
   } 

}
