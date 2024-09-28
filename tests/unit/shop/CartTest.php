<?php

use Planet\InterviewChallenge\Shop\Cart;
use Planet\InterviewChallenge\Shop\CartItem;

class CartTest extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        $this->object = new Cart();
    }

    public function testGetState()
    {
        $this->object->addItem(new CartItem(12300, CartItem::MODE_NO_LIMIT));
        $state = $this->object->getState();

        $expected = [
            (object)[
                'price'   => 12300,
                'availableAt' => -2,
            ]
        ];
        $this->assertEquals(1, count(json_decode($state)));
        $this->assertEquals($expected, json_decode($state));
    }
}
