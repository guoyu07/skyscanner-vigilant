<?php
/**
 * @author Jean Silva <jeancsil@gmail.com>
 * @license MIT
 */
namespace Jeancsil\Skyscanner\VigilantBundle\Entity;

class Parameter
{
    const FROM = 'from';
    const TO = 'to';
    const DEPARTURE_DATE= 'departure';
    const RETURN_DATE = 'arrival';
    const API_KEY = 'api-key';
    const LOCATION_SCHEMA = 'location-schema';
    const COUNTRY = 'country';
    const CURRENCY = 'currency';
    const LOCALE = 'locale';
    const ADULTS = 'adults';
    const CHILDREN = 'children';
    const INFANTS = 'infants';
    const CABIN_CLASS = 'cabin-class';
    const GROUP_PRICING = 'price-per-adult';
}
