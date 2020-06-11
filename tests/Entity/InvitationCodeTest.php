<?php


namespace App\Tests\Entity;


use App\Entity\InvitationCode;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class InvitationCodeTest extends WebTestCase
{
    use FixturesTrait;

    private function getInvalidationCodeEntity():InvitationCode
    {
        $code = new InvitationCode();
        $code->setCode("12345");
        $code->setDescription("Description test");
        $code->setExpireAt(new \DateTime());
        return $code;
    }

    private function assertAsErrorsWithValidator(InvitationCode $code, int $number=0, $testFixtureLiips=false)
    {
        if($testFixtureLiips)
            $this->loadFixtureFiles([dirname(__DIR__)."/Fixtures/InvitationCodeFixture.yaml"]);

        self::bootKernel();
        $errors=self::$container->get("validator")->validate($code);

        $messages = array();
        /**
         * @var ConstraintViolation $error
         */
        foreach ($errors as $error)
        {
            array_push($messages, $error->getPropertyPath()." - ".$error->getMessage());
        }
        $this->assertCount($number, $errors, implode(', ',$messages));
    }


    public function testValidationCode(){
        $code = $this->getInvalidationCodeEntity();
        $this->assertAsErrorsWithValidator($code);
    }

    public function testInvalidationCodeWithOneLetter(){
        $code = $this->getInvalidationCodeEntity()->setCode("1a234");
        $this->assertAsErrorsWithValidator($code,1);
    }

    public function testInvalidationCodeWithSixNumbers(){
        $code = $this->getInvalidationCodeEntity()->setCode("123456");
        $this->assertAsErrorsWithValidator($code,1);
    }

    public function testInvalidationCodeWithFourNumber(){
        $code = $this->getInvalidationCodeEntity()->setCode("1234");
        $this->assertAsErrorsWithValidator($code,1);
    }

    public function testInvalidationCodeBlankCode(){
        $code = $this->getInvalidationCodeEntity()->setCode("");
        $this->assertAsErrorsWithValidator($code,1);
    }

    public function testInvalidationDescriptonBlank()
    {
        $code = $this->getInvalidationCodeEntity()->setDescription("");
        $this->assertAsErrorsWithValidator($code,1);
    }

    public function testInvalidDoubleCode()
    {
        $code = $this->getInvalidationCodeEntity()->setCode('15932');
        $this->assertAsErrorsWithValidator($code, 1, true);
    }

}