<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusSaferpayPlugin\TransactionLog\Event;

class PaymentCaptureSucceeded
{
    public function __construct(
        private string $requestUrl,
        private array $requestBody,
        private array $responseData,
    ) {
    }

    public function getRequestUrl(): string
    {
        return $this->requestUrl;
    }

    /**
     * @return array{
     *     TransactionReference: array{TransactionId: string}
     * }
     */
    public function getRequestBody(): array
    {
        return $this->requestBody;
    }

    /**
     * @return array{
     *     StatusCode: int,
     *     ResponseHeader: array{SpecVersion: string, RequestId: string},
     *     CaptureId: string,
     *     Status: string,
     *     Date: string,
     * }
     */
    public function getResponseData(): array
    {
        return $this->responseData;
    }
}
