<?php


namespace App\Tests\Validator;


use App\Validator\EmailDomain;
use App\Validator\EmailDomainValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class EmailDomainValidatorTest extends TestCase
{
    private function getValidator($expectedViolation = false)
    {
        $validator = new EmailDomainValidator();
        $context = $this->getMockBuilder(ExecutionContextInterface::class)->getMock();

        if($expectedViolation)
        {
            $violation = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)->getMock();
            $violation->expects($this->any())->method('setParameter')->willReturn($violation);
            $violation->expects($this->once())->method('addViolation');
            $context
                ->expects($this->once())
                ->method('buildViolation')
                ->willReturn($violation);
        }else{
            $context
                ->expects($this->never())
                ->method('buildViolation');
        }
        $validator->initialize($context);
        return $validator;
    }

    public function testCatchBadDomain(){
        $constraint = new EmailDomain([
            'blocked' => ['baddomain.fr', 'toto.com']
        ]);

        $this->getValidator(true)->validate('demo@baddomain.fr', $constraint);
    }

    public function testCatchGoodDomain(){
        $constraint = new EmailDomain([
            'blocked' => ['baddomain.fr', 'toto.com']
        ]);

        $this->getValidator(false)->validate('demo@gooddomamin.fr', $constraint);

    }
}