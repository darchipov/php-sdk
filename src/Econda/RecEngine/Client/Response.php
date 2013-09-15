<?php
/**
 * econda recommendations client library
 *
 * @copyright Copyright econda GmbH
 * @link http://www.econda.de
 * @package Econda/RecEngine
 * @license MIT License
 */
namespace Econda\RecEngine\Client;

use Econda\RecEngine\Base\StandardConstructorTrait;
use Econda\RecEngine\Widget\Model\ModelInterface;

class Response implements ModelInterface
{
    use StandardConstructorTrait;

	protected $title;

    protected $products = [];

    protected $disableIfEmpty = true;

    protected $startIndex = 0;

    public function __construct($data=null)
    {
        if($data) {
            $this->initPropertiesFromArray($data);
        }
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

    public function getDisableifEmpty()
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