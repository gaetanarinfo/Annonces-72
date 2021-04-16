<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TelephoneMobileValidator extends ConstraintValidator
{
 
  public function validate($value, Constraint $constraint)
  {
      if(empty($value)) 
      {
          return;
      }

      $phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();

      try {

          $phoneNumberObject = $phoneNumberUtil->parse($value, 'FR');
          if ($phoneNumberUtil->isValidNumber($phoneNumberObject) === false || $phoneNumberUtil->getNumberType($phoneNumberObject) !== 1)
          {
              return $this->context
                  ->buildViolation($constraint->message)
                  ->addViolation()
                  ;
          }

      }
      catch(\Exception $e){

          return $this->context
              ->buildViolation($constraint->message)
              ->addViolation()
              ;
      }
  }
}