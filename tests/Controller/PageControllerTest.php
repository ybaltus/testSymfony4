<?php


namespace App\Tests\Controller;


use App\Entity\User;
use App\Tests\Traits\NeedLogin;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class PageControllerTest extends WebTestCase
{
    use FixturesTrait;
    use NeedLogin;

    public function testHelloPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/hello');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
//        $this->assertResponseIsSuccessful();
    }

    public function testH1HelloPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/hello');
        $this->assertSelectorTextContains('h1', 'Mon titre h1');
//        $this->assertCount(1, $crawler->filter('h1'));
    }

//    public function testAuthPageIsRestricted()
//    {
//        $client =static::createClient();
//        $client->request('GET', '/auth');
//        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
//    }

    public function testRedirectToLogin()
    {
        $client=static::createClient();
        $client->request('GET', '/auth');
        $this->assertResponseRedirects('/login');
    }

    public function testLetUserOnAuthPage()
    {
        $client=static::createClient();

        $users = $this->loadFixtureFiles([dirname(__DIR__).'/Fixtures/Users.yaml']);
        /**
         * @var User $user
         */
        $user = $users['user_user'];
        $this->login($client, $user);
        $client->request('GET', '/auth');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

//    public function testAdminRequireAdminRole()
//    {
//        $client=static::createClient();
//
//        $users = $this->loadFixtureFiles([dirname(__DIR__).'/Fixtures/Users.yaml']);
//        /**
//         * @var User $user
//         */
//        $user = $users['user_user'];
//        $this->login($client, $user);
//        $client->request('GET', '/admin');
//        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
//    }

    public function testAdminWithSufficientRole()
    {
        $client=static::createClient();

        $users = $this->loadFixtureFiles([dirname(__DIR__).'/Fixtures/Users.yaml']);
        /**
         * @var User $user
         */
        $user = $users['user_admin'];
        $this->login($client, $user);
        $client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }


    public function testMailSendEmail()
    {
        $client=static::createClient();
        $client->enableProfiler();
        $client->request('GET', 'mail');
        $mailCollector =$client->getProfile()->getCollector('swiftmailer');
        $this->assertEquals(1, $mailCollector->getMessageCount());

        /**
         * @var \Swift_Message[] $messages
         */
        $messages = $mailCollector->getMessages();
        $this->assertEquals($messages[0]->getTo(), ['contact@domain.fr'=>null]);
    }
}