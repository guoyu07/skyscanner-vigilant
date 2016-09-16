<?php
/**
 * @author Jean Silva <jeancsil@gmail.com>
 * @license MIT
 */
namespace Jeancsil\Skyscanner\VigilantBundle\Command;

use Jeancsil\Skyscanner\VigilantBundle\Entity\Parameter;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SkyScannerVigilantLivePricesCommand extends ContainerAwareCommand
{
    protected function configure() {
        $this
            ->setName('skyscanner:vigilant:live_prices')
            ->setDescription('Look for live prices for the determined filters')
            ->addOption(Parameter::FROM, null, InputOption::VALUE_REQUIRED, 'Starting point for your trip.')
            ->addOption(Parameter::TO, null, InputOption::VALUE_REQUIRED, 'Your final destiny.')
            ->addOption(Parameter::DEPARTURE_DATE, null, InputOption::VALUE_REQUIRED, 'The departure date (dd-mm-yyyy).')
            ->addOption(Parameter::RETURN_DATE, null, InputOption::VALUE_REQUIRED, 'The return date (dd-mm-yyyy).')
            ->addOption(Parameter::API_KEY, null, InputOption::VALUE_OPTIONAL, 'The Skyscanner API key.')
            ->addOption(Parameter::LOCATION_SCHEMA, null, InputOption::VALUE_OPTIONAL, 'One of the locations schema: Iata, GeoNameCode, GeoNameId, Rnid, Sky.', 'Sky')
            ->addOption(Parameter::COUNTRY, null, InputOption::VALUE_OPTIONAL, 'Country code (ISO or a valid one from location schema).')
            ->addOption(Parameter::CURRENCY, null, InputOption::VALUE_OPTIONAL, 'The currency or every price.')
            ->addOption(Parameter::LOCALE, null, InputOption::VALUE_OPTIONAL, 'The locale (ISO containing language and country. Eg.: pt-BR, DE-de).')
            ->addOption(Parameter::ADULTS, null, InputOption::VALUE_OPTIONAL, 'Number of adults. (Between 1 an 8).', 1)
            ->addOption(Parameter::CABIN_CLASS, null, InputOption::VALUE_OPTIONAL, 'The cabin class. (Economy, PremiumEconomy, Business, First).', 'Economy')
            ->addOption(Parameter::CHILDREN, null, InputOption::VALUE_OPTIONAL, 'The number of children. (Between 0 and 8).', 0)
            ->addOption(Parameter::INFANTS, null, InputOption::VALUE_OPTIONAL, 'The number of infants. Cannot exceeds adults.', 0)
            ->addOption(Parameter::GROUP_PRICING, null, InputOption::VALUE_OPTIONAL, 'Show price per adult.', false)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $this
            ->getContainer()
            ->get('jeancsil_skyscanner_vigilant.validator.command_line_parameter')
            ->setInstance($input)
            ->validate();

        $travelOptions = $this
            ->getContainer()
            ->get('jeancsil_skyscanner_vigilant.api.data_transfer.factory.travel_options')
            ->createFromInput($input);

        if (!$livePrices = $this->getContainer()
            ->get('jeancsil_skyscanner_vigilant.api.flights.live_price')
            ->getDeals($input)) {
            return;
        }

//var_dump($livePrices);die;
        #$parsed = $livePrices->parsed;
        $itineraries = $livePrices->Itineraries;
        $cheaperItineraries = array_slice($itineraries, 0, 10);

        $minPrice = 5000;
        $goodPriceFound = false;
        $resultCount = 1;
        foreach ($cheaperItineraries as $itinerary) {
            echo 'Verifying itinerary #'. $resultCount++ . ' ';

            if (!isset($itinerary->PricingOptions[0])) {
                continue;
            }

            $price = $itinerary->PricingOptions[0]->Price;
            $deepLinkUrl = $itinerary->PricingOptions[0]->DeeplinkUrl;

            if ($price <= $minPrice) {
                $goodPriceFound = true;
                echo "Bom preço encontrado ($price) ($deepLinkUrl)" . PHP_EOL;
                continue;
            }

            echo "skipping..." . PHP_EOL;
        }

        if (!$goodPriceFound) {
            echo "Nenhum preço encontrado..." . PHP_EOL;
        } else {
            echo "bons precos";
        }
    }
}
