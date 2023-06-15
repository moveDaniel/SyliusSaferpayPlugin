<?php

declare(strict_types=1);

namespace spec\CommerceWeavers\SyliusSaferpayPlugin\Provider;

use CommerceWeavers\SyliusSaferpayPlugin\Exception\OrderAlreadyCompletedException;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\OrderCheckoutStates;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class OrderProviderSpec extends ObjectBehavior
{
    function let(OrderRepositoryInterface $orderRepository): void
    {
        $this->beConstructedWith($orderRepository);
    }

    function it_throws_an_exception_when_order_with_given_token_does_not_exist_for_assert(
        OrderRepositoryInterface $orderRepository,
    ): void {
        $orderRepository->findOneByTokenValue('TOKEN')->willReturn(null);
        $orderRepository->findOneBy(['tokenValue' => 'TOKEN'])->willReturn(null);

        $this
            ->shouldThrow(new NotFoundHttpException('Order with token "TOKEN" does not exist.'))
            ->during('provideForAssert', ['TOKEN'])
        ;
    }

    function it_throws_an_exception_when_order_for_assert_is_already_completed(
        OrderRepositoryInterface $orderRepository,
        OrderInterface $order,
    ): void {
        $orderRepository->findOneByTokenValue('TOKEN')->willReturn(null);
        $orderRepository->findOneBy(['tokenValue' => 'TOKEN'])->willReturn($order);
        $order->getCheckoutState()->willReturn(OrderCheckoutStates::STATE_COMPLETED);

        $this
            ->shouldThrow(OrderAlreadyCompletedException::class)
            ->during('provideForAssert', ['TOKEN'])
        ;
    }

    function it_throws_an_exception_when_order_with_given_token_does_not_exist_for_capture(
        OrderRepositoryInterface $orderRepository,
    ): void {
        $orderRepository->findOneByTokenValue('TOKEN')->willReturn(null);
        $orderRepository->findOneBy(['tokenValue' => 'TOKEN'])->willReturn(null);

        $this
            ->shouldThrow(new NotFoundHttpException('Order with token "TOKEN" does not exist.'))
            ->during('provideForCapture', ['TOKEN'])
        ;
    }

    function it_throws_an_exception_when_order_with_given_token_for_capture_is_already_completed(
        OrderRepositoryInterface $orderRepository,
        OrderInterface $order,
    ): void {
        $orderRepository->findOneByTokenValue('TOKEN')->willReturn(null);
        $orderRepository->findOneBy(['tokenValue' => 'TOKEN'])->willReturn($order);
        $order->getCheckoutState()->willReturn(OrderCheckoutStates::STATE_COMPLETED);

        $this
            ->shouldThrow(OrderAlreadyCompletedException::class)
            ->during('provideForCapture', ['TOKEN'])
        ;
    }

    function it_provides_order_for_assert(
        OrderRepositoryInterface $orderRepository,
        OrderInterface $order,
    ): void {
        $orderRepository->findOneByTokenValue('TOKEN')->willReturn($order);

        $this->provideForAssert('TOKEN')->shouldReturn($order);
    }

    function it_provides_order_for_capture(
        OrderRepositoryInterface $orderRepository,
        OrderInterface $order,
    ): void {
        $orderRepository->findOneByTokenValue('TOKEN')->willReturn($order);

        $this->provideForCapture('TOKEN')->shouldReturn($order);
    }
}
