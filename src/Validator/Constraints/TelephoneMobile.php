<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
* @Annotation
*
*/
class TelephoneMobile extends Constraint
{
  public $message = "Ce numéro de téléphone n'est pas valide.";
}