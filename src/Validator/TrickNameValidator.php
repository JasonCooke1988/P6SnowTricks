<?php


namespace App\Validator;


use App\Repository\TrickRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class TrickNameValidator extends ConstraintValidator
{

    private TrickRepository $trickRepository;

    public function __construct(TrickRepository $trickRepository)
    {
        $this->trickRepository = $trickRepository;
    }

    /**
     * @inheritDoc
     */
    public function validate($protocol, Constraint $constraint)
    {
        $name = $protocol->getName();

        if (!$constraint instanceof TrickName) {
            throw new UnexpectedTypeException($constraint, TrickName::class);
        }

        if (null === $name || '' === $name) {
            return;
        }

        if (!is_string($name)) {
            throw new UnexpectedValueException($name, 'string');
        }

        if ($this->checkName($protocol, $this->trickRepository)) {
            $this->context->buildViolation($constraint->message)
                ->atPath('name')
                ->addViolation();
        }
    }

    public function checkName($protocol, $trickRepository): bool
    {
        $query = $trickRepository->findOneBy(array('name' => $protocol->getName()));

        if ($query != null && $query->getId() != $protocol->getId()) {
            return true;
        }
        return false;
    }
}