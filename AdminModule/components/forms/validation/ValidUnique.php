<?php

namespace AdminModule\Forms\Validation;

use Nette\Application\UI\Form;

class ValidUnique {

   public static function isUnique($control1) {
       return false;
   }
    

}