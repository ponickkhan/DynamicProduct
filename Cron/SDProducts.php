<?php
declare(strict_types=1);

namespace Strativ\DynamicProduct\Cron;

class SDProducts
{

    protected $logger;

    /**
     * Constructor
     *
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        $this->logger->addInfo("Cronjob SDProducts is executed.");

        $writer = new \Laminas\Log\Writer\Stream(BP . '/var/log/cronjob_product_update.log');
        $logger = new \Laminas\Log\Logger();
        $logger->addWriter($writer);

        $logger->info('cron is running');
    }
}

