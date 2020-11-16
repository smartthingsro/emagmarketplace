<?php

namespace Zitec\EmagMarketplace\Console\Command\Order;

use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zitec\EmagMarketplace\Model\Queue\Order\ImporterFactory;

/**
 * Class Import
 * @package Zitec\EmagMarketplace\Console\Command\Order
 */
class Import extends Command
{
    const COMMAND_NAME = 'zitec_emagmarketplace:order:import';

    /**
     * @var ImporterFactory
     */
    protected $importerFactory;

    /**
     * @var State
     */
    protected $state;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Locality constructor.
     * @param ImporterFactory $importerFactory
     * @param State $state
     * @param LoggerInterface $logger
     * @internal param LocalityRepositoryInterface $localityRepository
     */
    public function __construct(
        ImporterFactory $importerFactory,
        State $state,
        LoggerInterface $logger
    ) {
        $this->importerFactory = $importerFactory;
        $this->logger = $logger;
        $this->state = $state;

        parent::__construct();
    }

    /**
     *
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription('Import eMag orders.');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->state->setAreaCode(Area::AREA_GLOBAL);
        } catch (\Exception $exception) {
        }

        try {
            $output->writeln(date('H:i:s') . ' Start importing orders.');
            $this->importerFactory->create()->importOrders();
            $output->writeln(date('H:i:s') . ' Finished importing orders.');
        } catch (\Exception $exception) {
            $output->write('Error: ' . $exception->getMessage());
            $this->logger->critical($exception);
        }
    }
}
