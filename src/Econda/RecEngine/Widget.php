<?php
/**
 * econda recommendations client library
 *
 * @copyright Copyright econda GmbH
 * @link http://www.econda.de
 * @package Econda/RecEngine
 * @license MIT License
 */
namespace Econda\RecEngine;

use Econda\RecEngine\Base\StandardConstructorTrait;
use Econda\RecEngine\Client\Request;
use Econda\RecEngine\Config\ArrayConfig;
use Econda\RecEngine\Exception\InvalidArgumentException;
use Econda\RecEngine\Widget\Model\ModelInterface;
use Econda\RecEngine\Widget\Renderer\AbstractRenderer;
use Econda\RecEngine\Client\Request\Context;
use Econda\RecEngine\Widget\Renderer\HtmlRenderer;

class Widget
{
    use StandardConstructorTrait;
	
	/**
	 * Widget ID as defined in cross sell management interface
	 * @var int
	 */
	protected $id;
	
	/**
	 * Configuration
	 * @var \Econda\RecEngine\Config\ConfigInterface
	 */
	protected $config;
	
	/**
	 * @var Context
	 */
	protected $context;

    /**
     * Data to render (result of request)
     * @var ModelInterface
     */
    protected $model;

    /**
     * Index of first item, starting at 0
     * @var int
     */
    protected $startIndex = 0;

    /**
     * Max numer of items to retrieve from rec service
     * @var int
     */
    protected $chunkSize;

    /**
     * Template Html
     * @var string
     */
    protected $template;

    /**
     * Path to file containing template html
     * @var string
     */
    protected $templatePath;

	/**
	 * Object to use to render this widget
	 * @var AbstractRenderer
	 */
	protected $renderer;
	
	public function __construct($config = null)
	{
		if($config) {
			$this->config = new ArrayConfig();
            if(isset($config['accountId'])) {
                $this->config->setAccountId($config['accountId']);
                unset($config['accountId']);
            }
            $this->initPropertiesFromArray($config);
		}
		
		$this->context = new Context();
	}

	/**
	 * Set configuration
	 *
	 * @param mixed $config ConfigInterface or array
	 * @throws InvalidArgumentException
	 * @return Widget
	 */
	public function setConfig($config)
	{
		switch(true) {
			case is_array($config):
				$this->config = new ArrayConfig($config);
				break;
			case ($config instanceof ConfigInterface):
				$this->config = $config;
				break;
			default:
				throw new InvalidArgumentException('Got invalid configuration data in constructor.');
		}
		return $this;
	}
	
	/**
	 * @return ConfigInterface
	 */
	public function getConfig()
	{
		return $this->config;
	}
	
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        if(!is_numeric($id)) {
            throw new InvalidArgumentException("Value for property id must be numeric.");
        }
        $this->id = (int) $id;
        return $this;
    }

    public function getRenderer()
    {
        if(!$this->renderer) {
            $this->renderer = new HtmlRenderer();
        }
        return $this->renderer;
    }

    public function setRenderer(AbstractRenderer $renderer)
    {
        $this->renderer = $renderer;
        return $this;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function setContext(Context $context)
    {
        $this->context = $context;
        return $this;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel(ModelInterface $model)
    {
        $this->model = $model;
        return $this;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplatePath($path)
    {
        $this->templatePath = $path;
        return $this;
    }

    public function getTemplatePath()
    {
        return $this->templatePath;
    }

	public function render()
	{
		if(!$this->model) {
            $this->executeRequest();
        }

        $renderer = $this->getRenderer();
        $renderer->setModel($this->model);

        if($this->template) {
            $renderer->setTemplate($this->template);
        }
        if($this->templatePath) {
            $renderer->setTemplatePath($this->templatePath);
        }

        $html = $renderer->render();
        return $html;
	}

    /**
     * Get recommendations from rec service and write to model property.
     */
    public function executeRequest()
    {
        $client = new Client($this->getConfig());
        $client->getRequest()
            ->setStartIndex($this->startIndex)
            ->setChunkSize($this->chunkSize)
            ->setWidgetId($this->id)
            ->setContext($this->context);

        $this->model = $client->execute();
    }
}