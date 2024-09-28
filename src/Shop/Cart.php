<?php

namespace Planet\InterviewChallenge\Shop;

use Exception;
use Throwable;
use Planet\InterviewChallenge\App;

require_once(__DIR__.'/CartItem.php');

class Cart
{
    private array $items;

    public function __construct()
    {
        $this->items = [];

        $params = json_decode($_GET['items'] ?? '[]');
        foreach ($params as $key=>$item){
            $this->addItem(new CartItem((int)$item->price, $this->valueToMode($item->expires), $this->valueToModifier($item->expires)));
        }
    }

    private function valueToMode($value): ?int
    {
        if ($value === 'never') {
            return CartItem::MODE_NO_LIMIT;
        }else{
            $unit = preg_replace('/\d+/', '', $value);
            switch ($unit){
                case "hour":
                case "hours":
                    return CartItem::MODE_HOUR;
                case "min":
                case "mins":
                    return CartItem::MODE_MINUTE;
                case "second":
                case "seconds":
                    return CartItem::MODE_SECONDS;

            }
        }

        return null;
    }

    private function valueToModifier($value): ?int
    {
        if ($value === 'never') {
            return null;
        }else{
            return intval($value);
        }
    }

    public function addItem(CartItem $cartItem): ?bool
    {
        try {
            $cartItem->isAvailable();
            $this->items[] = $cartItem;
            return true;
        } catch (Throwable|Exception $e) {
            throw $e;
        }
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function clear(): void {
        unset($this->items);
    }

    public function display(): string
    {
        App::smarty()->assign('items', $this->items);
        return App::smarty()->fetch('shop/Cart.tpl');
    }

    public function getState(): string
    {
        $objectStates = '[';

        while ($item = each($this->items)) {
            $objectStates .= $item['value']->getState() . ',';
        }
        $objectStates = substr($objectStates, 0, -1);
        return $objectStates . ']';
    }
}
