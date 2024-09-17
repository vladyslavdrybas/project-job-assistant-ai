<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\DataTransferObject\Form\Contact\ClientRequestCallBackDto;
use App\DataTransferObject\ViewResponseDto;
use App\Entity\RequestCallBack;
use App\Form\Contact\ContactFormType;
use Cassandra\Exception\ValidationException;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;

#[Route(
    "/api/contact",
    name: "api_contact"
)]
class ContactController extends AbstractController
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

            if ($params->get('email_is_active')) {
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

        return $this->response(
            [
                'message' => 'Please, check your email. <br> Your request id is #' . $rcb->getRawId(),
                'requestId' => $rcb->getRawId(),
            ]
        );
    }
}
