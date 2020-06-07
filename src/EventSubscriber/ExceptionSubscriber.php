<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionSubscriber implements EventSubscriberInterface
{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var string
     */
    private $from;
    /**
     * @var string
     */
    private $to;

    public function __construct(\Swift_Mailer $mailer,string $from="", string $to="")
    {
        $this->mailer = $mailer;
        $this->from = $from;
        $this->to = $to;
    }

    public static function getSubscribedEvents()
    {
        return [
            ExceptionEvent::class => 'onException',
        ];
    }

    public function onException(ExceptionEvent $event)
    {
        $message = new \Swift_Message();
        $message->setFrom($this->from);
        $message->setTo($this->to);
        $message->setBody("{$event->getRequest()->getRequestUri()}
        
        
        {$event->getException()->getMessage()}
        
        {$event->getException()->getTraceAsString()}");

//        var_dump($message->getBody());
        $this->mailer->send($message);
    }
}
