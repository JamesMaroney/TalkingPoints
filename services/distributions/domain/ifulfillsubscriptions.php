<?php

interface IFulfillSubscriptions {
    public function handlesType();
    public function handle($subscription, $points);
}