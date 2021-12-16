<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MailSenderController extends AbstractController
{

    const ADMIN_EMAIL="weahealth90@gmail.com";
    /**
     * @Route("/mail/contact", name="cnt_mail",  methods={"GET", "POST"})
     */
    public function sendContactMail(Request $request,\Swift_Mailer $mailer)
    {
        /*$name =  $request->request->get("name");
        $email = $request->request->get("email");
        $subject = $request->request->get("subject");
        $message = $request->request->get("message");*/
        $name =  "Abdallah";
        $email = "lahbib.3abdallah98@gmail.com";
        $subject = "test";
        $msg = "Bonjour";
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom(self::ADMIN_EMAIL)
            ->setTo($email)
            ->setSubject($subject)
            ->setBody(
                $this->renderView(
                    'mail_sender/contact-email.html.twig',
                    ['data' =>["name"=>$name,"email"=>$email,"message"=>$msg]]
                ),
                'text/html'
            );
        $mailer->send($message);
       return $this->redirectToRoute("public");
    }
    /**
     * @Route("/send/mail", name="send_email",  methods={"GET", "POST"})
     */
    public function sendAction(Request $request,\Swift_Mailer $mailer){
        if ($request->isXMLHttpRequest()) {
            $email = $request->get('email');
            $subject = "Recommendation";
            $message = (new \Swift_Message('Hello Email'))
                ->setFrom(self::ADMIN_EMAIL)
                ->setTo($email)
                ->setSubject($subject)
                ->setBody(
                    $this->renderView(
                        'mail_sender/contact-email.html.twig'
                    ),
                    'text/html'
                );
            $mailer->send($message);
            return new JsonResponse([
                'status' => 200,
                'result' => 'email send'
            ]);
        }
        return new JsonResponse([
            'status' => 402,
            'result' => 'error'
        ]);
    }

}