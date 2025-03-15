<?php

declare(strict_types=1);

namespace Apiera\Sdk\Enum;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 2.1.0
 */
enum IntegrationProtocol: string
{
    case Webhook = 'webhook';
    case RabbitMQ = 'rabbitmq';
    case None = 'none';
}
