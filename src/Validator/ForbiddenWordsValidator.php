<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ForbiddenWordsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        $forbiddenWords = $constraint->payload;

        foreach($forbiddenWords as $word) {
            if(str_contains($value, $word)) {
                $this->context->buildViolation(sprintf('The value contains a forbidden word (%s).', $word))
                    ->addViolation();
            }
        }
    }
}
