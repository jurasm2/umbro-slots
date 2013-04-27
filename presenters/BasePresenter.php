<?php

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{

    public function &__get($name) {
        if (preg_match('#([[:alnum:]]+Model)#', $name, $matches)) {
            $model = $this->context->modelLoader->loadModel(ucfirst($matches[1]));
            return $model;
        }  else if (preg_match('#([[:alnum:]]+)Service#', $name, $matches)) {
            
           
            if ($this->context->hasService($matches[1])) {
                $service = $this->context->$matches[1];
                switch ($matches[1]) {
                    case 'billingAgent':
                        if (!$service->isPresenterSet()) {
                            $service->presenter = $this;
                        }
                        break;
                }
                return $service;
            } else {
                throw new Nette\MemberAccessException("Service with name '$matches[1]' does not exist");
            }
        }
        return parent::__get($name);
    }
    
    
      // genericka tovarna
    public function createComponent($name) {
        
        

            
        // generic facotry for components with default constructor
        // public function __construct($parent, $name)

        $classname = "\\Components\\" . ucfirst($name);

//            dump($classname);
//            die();

        if ($classname !== NULL && class_exists($classname)) {
            $class = new \Nette\Reflection\ClassType($classname);
            $constructor = $class->getConstructor();
            $constructorParams = $constructor->getParameters();

            if (count($constructorParams) == 2 && $constructorParams[0]->name == 'parent' && $constructorParams[1]->name == 'name') {
                $control = new $classname($this, $name);
                return $control;
            }

        }

        
        
        return parent::createComponent($name);
        
    }
    
}
