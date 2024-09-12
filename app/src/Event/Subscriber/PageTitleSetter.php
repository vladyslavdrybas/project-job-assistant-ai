<?php
declare(strict_types=1);

namespace App\Event\Subscriber;

use App\DataTransferObject\ViewResponseDto;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

readonly class PageTitleSetter implements EventSubscriberInterface
{
    public function __construct(
        protected Environment $twig,
        protected UrlGeneratorInterface $urlGenerator,
        protected TranslatorInterface $translator,
        protected string $appTitle
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['processViewRequest', 0],
        ];
    }

    public function processViewRequest(ViewEvent $event): void
    {
        $viewResponseDto = $event->getControllerResult();
        if (!$viewResponseDto instanceof ViewResponseDto) {
            return;
        }

        $template = $viewResponseDto->template;
        $data = $viewResponseDto->data;
        $hasTemplate = !empty($template) && str_ends_with($template, 'twig');
        $hasRedirect = !empty($template);

        if ($hasTemplate) {
            if (!isset($data['meta'])) {
                $data['meta'] = [];
            }

            $route = $event->getRequest()->attributes->get('_route');
            $title = $this->translator->trans('meta.title.' . $route);

            $data['meta']['title'] = !empty($title) && !str_contains($title, '_') ? $title : ucfirst($this->appTitle);

            $response = $this->doRender(
                $template,
                null,
                $data,
                new Response(
                    '',
                    $viewResponseDto->statusCode,
                    $viewResponseDto->headers
                ),
                $event->getRequest()->getMethod()
            );
        } else if ($hasRedirect) {
            $response = new RedirectResponse(
                $this->urlGenerator->generate(
                    $viewResponseDto->template,
                    $viewResponseDto->data,
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                Response::HTTP_FOUND,
                $viewResponseDto->headers
            );
        } else {
            $response = new JsonResponse(
                $data,
                $viewResponseDto->statusCode,
                $viewResponseDto->headers
            );
        }

        $event->setResponse($response);
    }

    private function doRenderView(string $view, ?string $block, array $parameters, string $method): string
    {
        foreach ($parameters as $k => $v) {
            if ($v instanceof FormInterface) {
                $parameters[$k] = $v->createView();
            }
        }

        if (null !== $block) {
            return $this->twig->load($view)->renderBlock($block, $parameters);
        }

        return $this->twig->render($view, $parameters);
    }

    private function doRender(string $view, ?string $block, array $parameters, ?Response $response, string $method): Response
    {
        $content = $this->doRenderView($view, $block, $parameters, $method);
        $response ??= new Response();

        if (200 === $response->getStatusCode()) {
            foreach ($parameters as $v) {
                if ($v instanceof FormInterface && $v->isSubmitted() && !$v->isValid()) {
                    $response->setStatusCode(422);
                    break;
                }
            }
        }

        $response->setContent($content);

        return $response;
    }
}
