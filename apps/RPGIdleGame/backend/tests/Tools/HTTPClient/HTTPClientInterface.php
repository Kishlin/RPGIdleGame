<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\HTTPClient;

interface HTTPClientInterface
{
    public function get(Request $request): Response;

    public function post(Request $request): Response;

    public function put(Request $request): Response;

    public function delete(Request $request): Response;
}
