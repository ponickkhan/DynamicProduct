# Magento 2 Module Strativ DynamicProduct

    ``strativ/module-dynamicproduct``


Magento 2 Recruitment Test Module For Strativ

## Installation

 - Unzip the zip file in `app/code/Strativ`
 - Enable the module by running `php bin/magento module:enable Strativ_DynamicProduct`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Configuration

 - Set products_import.json file url in DynamicProduct/Controller/Adminhtml/System/Config/Create.php on line 41
 - Set products_update.json file url in DynamicProduct/Controller/Adminhtml/System/Config/Update.php on line 41






