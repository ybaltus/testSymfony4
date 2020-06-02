<?php


namespace App\Tests\Entity;


use App\Entity\User;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class UserEntityTest extends WebTestCase
{
    use FixturesTrait;

    private function getUserEntity()
    {
        $entity = (new User())
            ->setPassword("12345")
            ->setEmail("totoUser@domain.fr")
            ->setCodeUser("123456")
        ;

        return $entity;
    }

    private function assertErrorsWithValidator(User $user, $number, $withLiipTestsFixture=false)
    {
        self::bootKernel();
        $errors = self::$container->get("validator")->validate($user);

        if($withLiipTestsFixture)
            $this->loadFixtureFiles([dirname(__DIR__)."/Fixtures/UserFixture.yaml"]);

        $messages = [];
        /**
         * @var ConstraintViolation $error
         */
        foreach ($errors as $error)
        {
            array_push($messages, $error->getPropertyPath()." - ".$error->getMessage());
        }

        $this->assertCount($number, $errors, implode(', ', $messages));

    }

    public function testDoubleEmail()
    {
        $entity = $this->getUserEntity()->setEmail("toto1@domain.fr");
        $this->assertErrorsWithValidator($entity, 1, true);
    }

    public function testValidationCodeUser(){
        $entity = $this->getUserEntity();
        $this->assertErrorsWithValidator($entity,0);
    }

    public function testCodeUserWithFiveNumber(){
        $entity = $this->getUserEntity()->setCodeUser("12345");
        $this->assertErrorsWithValidator($entity,1);
    }

    public function testCodeUserWithSevenNumber(){
        $entity = $this->getUserEntity()->setCodeUser("1234567");
        $this->assertErrorsWithValidator($entity,1);
    }

    public function testCodeUserWithOneLetter(){
        $entity = $this->getUserEntity()->setCodeUser("1234d5");
        $this->assertErrorsWithValidator($entity,1);
    }

    public function testCodeUserNotBlank(){
        $entity = $this->getUserEntity()->setCodeUser("");
        $this->assertErrorsWithValidator($entity,1);
    }

}