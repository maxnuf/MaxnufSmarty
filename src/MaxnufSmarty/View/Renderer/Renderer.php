<?php

namespace MaxnufSmarty\View\Renderer;

use Smarty;
use Zend\View\Exception;
use Zend\View\Model\ModelInterface;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\ResolverInterface;

class Renderer extends PhpRenderer
{
    /**
     * @var \Smarty
     */
    protected $engine;


    public function __construct(Smarty $engine, ResolverInterface $resolver)
    {
        $this->engine = $engine;
        $this->setResolver($resolver);
    }
    
    /**
     * @param \Smarty $engine
     * @return Renderer
     */
    public function setEngine(Smarty $engine)
    {
        $this->engine = $engine;
        return $this;
    }

    /**
     * @return \Smarty
     */
    public function getEngine()
    {
        return $this->engine;
    }
    
    /**
     * Can the template be rendered?
     *
     * @param string $name
     * @return bool
     */
    public function canRender($name)
    {
        return $this->resolver()->resolve($name, $this);
    }

    /**
     * Processes a view script and returns the output.
     *
     * @param  string|ModelInterface $name The script/resource process, or a view model
     * @param  null|array|\ArrayAccess Values to use during rendering
     * @return string The script output.
     */
    public function render($nameOrModel, $values = null)
    {
        if ($nameOrModel instanceof ModelInterface) {
            $model       = $nameOrModel;
            $nameOrModel = $model->getTemplate();

            if (empty($nameOrModel)) {
                throw new Exception\DomainException(sprintf(
                    '%s: received View Model argument, but template is empty',
                    __METHOD__
                ));
            }

            $options = $model->getOptions();
            foreach ($options as $setting => $value) {
                $method = 'set' . $setting;
                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
                unset($method, $setting, $value);
            }
            unset($options);

            $values = $model->getVariables();
            unset($model);
        }

        if (null !== $values) {
            $this->setVars($values);
        }
        
        if (!($file = $this->resolver()->resolve($nameOrModel))) {
        	throw new Exception\RuntimeException(sprintf(
                    '%s: Unable to find template "%s"; resolver could not resolve to a file',
                    __METHOD__,
                    $nameOrModel
                ));
        }

        $smarty = $this->getEngine();
        $vars = $this->vars()->getArrayCopy();
        $smarty->assign('this', $this); // TODO this may change
        $smarty->assign($vars);
        
        $content = $smarty->fetch($file);

        return $this->getFilterChain()->filter($content);
    }
}
