<?php

use flight\Engine;

class PaymentController {
    public $app;

    public function __construct(Engine $app) {
        $this->app = $app;
    }
}