<?php

namespace AppBundle\Processor;

use AppBundle\Entity\SupportRequest;

class SupportRequestProcessor
{
    public function process(SupportRequest $supportRequest) {

        $message = \Swift_Message::newInstance()
            ->setSubject($supportRequest->getSubject())
            ->setFrom($supportRequest->getEmail(), $supportRequest->getName())
            ->setTo('atanasov@vmandco.com')
            ->setBody($templateRendered, $type);

        return $this->mailer->send($message);
    }
}