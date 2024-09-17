<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\DataTransferObject\Form\Contact\ClientRequestCallBackDto;
use App\DataTransferObject\ViewResponseDto;
use App\Entity\RequestCallBack;
use App\Form\Contact\ContactFormType;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    "/api/contact",
    name: "api_contact"
)]
class ContactController extends AbstractApiController
{
    #[Route(
        "/request-call-back",
        name: "_request_call_back",
        methods: ["POST"]
    )]
    public function requestCallBack(
        Request $request,
        MailerInterface $mailer,
        ParameterBagInterface $params,
    ): ViewResponseDto {
        $form = $this->createForm(
                ContactFormType::class,
                new ClientRequestCallBackDto('','','')
            );
        $form->handleRequest($request);

        $errors = $form->getErrors(true);
        if (count($errors) > 0) {
            throw new Exception($errors[0]->getMessage());
        }
        $rcb = new RequestCallBack();
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ClientRequestCallBackDto $data */
            $data = $form->getData();
            $rcb->setEmail($data->clientEmail);
            $rcb->setName($data->clientName);
            $rcb->setDescription($data->projectDescription);
            $rcb->setHash(hash('sha256', $rcb->getEmail() . $rcb->getDescription()));

            $this->entityManager->persist($rcb);
            $this->entityManager->flush();


            if (filter_var($params->get('email_is_active'), FILTER_VALIDATE_BOOLEAN)) {
                $email = (new TemplatedEmail())
                    ->from(new Address($params->get('email_autoreply'), 'Request Call Back Received'))
                    ->to($rcb->getEmail())
                    ->subject('Thank you for contacting me!')
                    ->htmlTemplate('_notification/email/request-call-back-thanks.html.twig');

                $context = $email->getContext();
                $context['ahoy'] = $rcb->getName();
                $context['orderId'] = $rcb->getRawId();
                $email->context($context);

                $mailer->send($email);
            }
        }

        $message = sprintf(
            'Your request id is #%s<br>Thank you!<br>I will respond in one workday.',
            $rcb->getRawId()
        );

        return $this->response(
            [
                'message' => $message,
                'requestId' => $rcb->getRawId(),
            ]
        );
    }
}
