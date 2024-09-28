<?php

namespace Planet\InterviewChallenge\Shop;

use Exception;
use Throwable;
use Planet\InterviewChallenge\App;

class CartItem extends Exception
{
		const MODE_NO_LIMIT = -2;

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
                    $this->available_at = self::MODE_NO_LIMIT;
                    break;
                case self::MODE_HOUR:
                    $this->available_at = strtotime('+' . ($modifier ?: self::MODE_HOUR) . ' hour');
                    break;
                case self::MODE_MINUTE:
                    $this->available_at = strtotime('+' . ($modifier ?: self::MODE_MINUTE) . ' minutes');
                    break;
                case self::MODE_SECONDS:
                    $this->available_at = strtotime('+' . ($modifier ?: self::MODE_SECONDS) . ' seconds');
                    break;
		    }
		    $this->price = $price;
		}


        /**
         * Check if the item is available
         *
         * @return bool|null
         */
        public function isAvailable(): ?bool
		{
		    return $this->available_at <= time();
		}

       /**
        * Returns the state representation of the object.
        *
        * @return string|object State representation of the class.
        */
		public function getState(): string
		{
		    return '{"price":' . $this->price . ',"availableAt":'.$this->available_at.'}';
		}

        /**
         * @return string
         * @throws \Smarty\Exception
         */
        public function display(): string
		{
		    App::smarty()->assign('price', $this->price);
		    App::smarty()->assign('available_at', $this->available_at);

		    return App::smarty()->fetch('shop/CartItem.tpl');
		}
}
