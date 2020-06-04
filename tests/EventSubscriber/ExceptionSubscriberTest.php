<?php


namespace App\Tests\EventSubscriber;


use App\EventSubscriber\ExceptionSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelInterface;

class ExceptionSubscriberTest extends TestCase
{
    private function dispatch($mailer){
        $subscriber = new ExceptionSubscriber($mailer, 'from@domain.fr', 'to@domain.fr');
        $kernel = $this->getMockBuilder(KernelInterface::class)->getMock();
        $event = new ExceptionEvent($kernel, new Request(), 1, new \Exception('Hello World'));
        $mailer->expects($this->once())->method('send');

        //$subscriber->onException($event);
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber($subscriber);
        $dispatcher->dispatch($event);
    }
    public function testEventSubscription(){
        $this->assertArrayHasKey(ExceptionEvent::class, ExceptionSubscriber::getSubscribedEvents());
    }

    public function testOnExceptionOnEmail()
    {
        $mailer = $this->getMockBuilder(\Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->getMock();
       $this->dispatch($mailer);
    }

    public function testEmailSentToGoodPerson()
    {
        $mailer = $this->getMockBuilder(\Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mailer->expects($this->once())
            ->method('send')
            ->with($this->callback(function (\Swift_Message $message){
                return
                    array_key_exists('from@domain.fr', $message->getFrom()) &&
                    array_key_exists('to@domain.fr', $message->getTo())
                    ;
            }));
        $this->dispatch($mailer);
    }

    public function testEmailSentWithTheTrace()
    {
        $mailer = $this->getMockBuilder(\Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mailer->expects($this->once())
            ->method('send')
            ->with($this->callback(function (\Swift_Message $message){
                return
                strpos($message->getBody(), 'ExceptionSubscriberTest') &&
                strpos($message->getBody(), 'Hello World')
                    ;
            }));
        $this->dispatch($mailer);
    }
}