<?php


namespace App\Tests\Validator;


use App\Repository\ConfigRepository;
use App\Validator\EmailDomain;
use App\Validator\EmailDomainValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class EmailDomainValidatorTest extends TestCase
{
    private function getValidator($expectedViolation = false, $dbBlockedDomain=[])
    {
        $repository = $this->getMockBuilder(ConfigRepository::class)->disableOriginalConstructor()->getMock();

        $repository->expects($this->any())
            ->method("getAsArray")
            ->with('blocked_domains')
            ->willReturn($dbBlockedDomain);


        $validator = new EmailDomainValidator($repository);
        $context= $this->getContext($expectedViolation);
        $validator->initialize($context);
        return $validator;
    }

    public function getContext($expectedViolation):ExecutionContextInterface
    {
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
        return $context;
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

    public function testBlockedDomainFromDB(){
        $constraint = new EmailDomain([
            'blocked' => ['baddomain.fr', 'toto.com']
        ]);

        $this->getValidator(true, ['baddbdomain.fr'])->validate('demo@baddbdomain.fr', $constraint);

    }

//    public function testParametersSetCorrectly()
//    {
//        $constraint = new EmailDomain([
//            'blocked' => []
//        ]);
//
//        self::bootKernel();
//        $validator = self::$container->get(EmailDomainValidator::class);
//        $validator->initialize($this->getContext(true));
//        $validator->validate("demo@globalblocked.fr", $constraint);
//    }
}