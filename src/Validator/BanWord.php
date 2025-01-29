<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;


#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class BanWord extends Constraint
{

    public function __construct(
        public array $banWords = ['spam', 'money','sex', 'drugs', 'violence'],
        public string $message = 'The word "{{ banWord }}" is banned and not valid.',
        array $groups = null,
        mixed $payload = null
    ) 
    {
        parent::__construct(null, $groups, $payload);
    }

}
