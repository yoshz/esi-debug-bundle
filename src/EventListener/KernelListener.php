<?php

namespace Yoshz\EsiDebugBundle\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class KernelListener
{
    private string $fragmentPath;

    public function __construct(string $fragmentPath)
    {
        $this->fragmentPath = $fragmentPath;
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();
        $request  = $event->getRequest();

        // ignore non fragment requests
        if ($this->fragmentPath !== rawurldecode($request->getPathInfo())) {
            return;
        }

        // ignore web_profiler ESI requests
        $controller = $request->attributes->get('_controller');
        if ($controller && str_starts_with($controller, 'web_profiler')) {
             return;
        }

        // ignore non-HTML ESI requests
        $contentType = $response->headers->get('Content-Type');
        if ($contentType && !str_starts_with($contentType, 'text/html')) {
            return;
        }

        // set ESI name
        $name = $controller;
        if ($debugLink = $response->headers->get('x-debug-token-link')) {
            $name = "<a href=\"$debugLink\" target=\"_blank\">$name</a>";
        }

        $response->setContent(sprintf(
            '
                <div class="esi-debug">
                    <div class="esi-debug-details">
                       ESI: %s, Cache-Control: %s
                    </div>
                    %s
                </div>

                <style>
                    .esi-debug {
                        border: 1px solid red;
                        box-sizing: border-box;
                    }

                    .esi-debug-details {
                        background: red;
                        position: absolute;
                        color: white;
                        font-size: 11px;
                        z-index: 9999;
                    }

                    .esi-debug:hover {
                        background: rgba(255, 0, 0, .25);
                        border-color #CC0000;
                        cursor: pointer;
                    }

                    .esi-debug:hover .esi-debug-details {
                        background: #CC0000;
                    }
                </style>
            ',
            $name,
            $response->headers->get('cache-control'),
            $response->getContent()
        ));
    }
}
