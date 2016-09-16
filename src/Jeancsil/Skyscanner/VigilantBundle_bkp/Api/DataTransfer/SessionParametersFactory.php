<?php
/**
 * @author Jean Silva <jeancsil@gmail.com>
 * @license MIT
 */
namespace Jeancsil\Skyscanner\VigilantBundle\Api\DataTransfer;

use Jeancsil\Skyscanner\VigilantBundle\Entity\Parameter;
use Symfony\Component\Console\Input\Input;

class SessionParametersFactory {
    private $apiKey;

    /**
     * @param string $apiKey
     */
    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function createFromInput(Input $input) {
        $parameters = new SessionParameters();
        $parameters->apiKey = $this->apiKey;
        $parameters->originPlace = $input->getOption(Parameter::FROM);
        $parameters->destinationPlace = $input->getOption(Parameter::TO);
        $parameters->outboundDate = $input->getOption(Parameter::DEPARTURE_DATE);
        $parameters->inboundDate = $input->getOption(Parameter::RETURN_DATE);
        $parameters->locationSchema = $input->getOption(Parameter::LOCATION_SCHEMA);
        $parameters->country = $input->getOption(Parameter::COUNTRY);
        $parameters->currency = $input->getOption(Parameter::CURRENCY);
        $parameters->locale = $input->getOption(Parameter::LOCALE);
        $parameters->adults = $input->getOption(Parameter::ADULTS);
        $parameters->cabinClass = $input->getOption(Parameter::CABIN_CLASS);
        $parameters->children = $input->getOption(Parameter::CHILDREN);
        $parameters->infants = $input->getOption(Parameter::INFANTS);
        $parameters->groupPricing= $input->getOption(Parameter::GROUP_PRICING);

        return $parameters;
    }
}
