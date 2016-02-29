<?php
/**
 * econda recommendations client library
 *
 * @copyright Copyright econda GmbH
 * @link http://www.econda.de
 * @package Econda/RecEngine
 * @license MIT License
 */
namespace Econda\RecEngine\Client\Response;

use Econda\RecEngine\Widget\Model\ModelInterface;
use InvalidArgumentException;

class ResponseModel implements ModelInterface
{
	protected $title;

    protected $products = array();

    protected $disableIfEmpty = true;

    protected $startIndex = 0;

    protected $widgetDetails = array();

    public function __construct($data=null)
    {
        if($data) {
            $this->initPropertiesFromArray($data);
        }
    }
    
    protected function initPropertiesFromArray($data)
    {
    	if(!is_array($data)) {
    		throw new InvalidArgumentException("Constructor expects an array of properties with their values.");
    	}
    	foreach($data as $key => $value) {
    		$setterName = 'set' . ucfirst($key);
    		if(!method_exists($this, $setterName)) {
    			throw new InvalidArgumentException("No setter found for property with name: " . $key);
    		}
    		$this->$setterName($value);
    	}
    }

    /**
     * Getter for property
     *
     * @return array
     */
    public function getWidgetDetails()
    {
        return $this->widgetDetails;
    }

    /**
     * Setter for property
     *
     * @param array $widgetDetails
     *
     * @return null
     */
    public function setWidgetDetails($widgetDetails)
    {
        $this->widgetDetails = $widgetDetails;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setProducts($products)
    {
        $this->products = $products;
        return $this;
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function setDisableIfEmpty($disable)
    {
        $this->disableIfEmpty = (bool) $disable;
        return $this;
    }

    public function getDisableIfEmpty()
    {
        return $this->disableIfEmpty;
    }

    public function setStartIndex($index)
    {
        $this->startIndex = $index;
        return $this;
    }

    public function getStartIndex()
    {
        return $this->startIndex;
    }

}