<?php

namespace App\Validator;

use App\Service\ClienteHttp;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UrlAccesibleValidator extends ConstraintValidator
{   
    private $clienteHttp;

    public function __construct(ClienteHttp $clienteHttp)
    {
        $this->clienteHttp = $clienteHttp;
    }
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        $codigoEstado = $this->clienteHttp->obtenerCodigoUrl($value);

        if (null === $codigoEstado){
            $codigoEstado = 'Error';
        }

        if (200 !== $codigoEstado) {    
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ codigo }}', $codigoEstado)
                ->addViolation();
        }
    }
}
