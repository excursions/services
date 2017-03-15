<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\SupportRequest;
use AppBundle\Form\SupportRequestType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/request", name="request")
     * @param Request $request
     * @return Response
     */
    public function formAction(Request $request)
    {
        $supportRequest = new SupportRequest();
        $form = $this->createForm(SupportRequestType::class, $supportRequest);

        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->processSupportRequest($form->getData());

                return $this->render('success.html.twig');
            }
        }

        return $this->render('requestform.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param SupportRequest $supportRequest
     */
    protected function processSupportRequest(SupportRequest $supportRequest)
    {
        $body = $this->renderView(
            'email.html.twig',
            [
                'name' => $supportRequest->getName() ?: 'Anonymous',
                'email' => $supportRequest->getEmail() ?: "None",
                'subject' => $supportRequest->getSubject() ?: 'None',
                'description' => $supportRequest->getDescription(),
            ]
        );

        $message = \Swift_Message::newInstance()
            ->setSubject($supportRequest->getSubject())
            ->setFrom('orosupport@vmandco.com', 'OroCRM Support')
            ->setTo('atanasov@vmandco.com')
            ->setBody($body, 'text/html');

        $this->get('mailer')->send($message);
    }
}
