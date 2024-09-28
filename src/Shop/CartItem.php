<?php

namespace Planet\InterviewChallenge\Shop;

use Exception;
use Throwable;
use Planet\InterviewChallenge\App;

class CartItem extends Exception
{
		const MODE_NO_LIMIT = 0;

		const MODE_HOUR = 1;

		const MODE_MINUTE = 10;

		const MODE_SECONDS = 1000;

		protected bool $smartySetup = false;

		private int $available_at;

		private int $price;

		public function __construct(int $price, $mode, $modifier = null)
		{
		    switch ($mode) {
                case self::MODE_NO_LIMIT:
                    $this->available_at = -2;
                    break;
                case self::MODE_HOUR:
                    $this->available_at = strtotime('+1 hour');
                    break;
                case self::MODE_MINUTE:
                    $this->available_at = strtotime('+' . $modifier . ' minutes');
                    break;
                case self::MODE_SECONDS:
                    $this->available_at = strtotime('+' . $modifier . ' seconds');
                    break;
		    }
		    $this->price = $price;
		}

		public function isAvailable(): ?bool
		{
		    return $this->available_at <= time();
		}

       /**
        * Returns the state representation of the object.
        *
        * @param int $format Constant from the class CartItem
        * @return string|object State representation of the class.
        */
		public function getState(): string
		{
		    return '{"price":' . $this->price . ',"availableAt":'.$this->available_at.'}';
		}

		public function display(): string
		{
		    App::smarty()->assign('price', $this->price);
		    App::smarty()->assign('availableAt', $this->available_at);

		    return App::smarty()->fetch('shop/CartItem.tpl');
		}
}
