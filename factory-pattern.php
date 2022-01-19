<?php

/**
 * Interface for Drivers
 */
interface DriverInterface
{
    /**
     * Set amount
     *
     * @param  $amount
     */
    public function setAmount($amount);

    /**
     * Get amount
     *
     * @return  float
     */
    public function getAmount();

    /**
     * Take payment
     *
     */
    public function takePayment();
}

/**
 * Abstract Driver
 */
abstract class AbstractDriver implements DriverInterface
{
    /**
     * Transaction amount
     *
     * @var  float
     */
    private $amount;

    /**
     * Set amount
     *
     * @param  $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return  float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Take payment
     */
    abstract public function takePayment();
}

/**
 * Handles Klarna payments
 */
class KlarnaDriver extends AbstractDriver
{
    /**
     * Simple example but you'd do anything klarna specific in here.
     *
     * @return  string
     */
    public function takePayment()
    {
        return "Took payment using Klarna for " . $this->getAmount();
    }
}

/**
 * Handles Paypal payments
 */
class PaypalDriver extends AbstractDriver
{
    /**
     * Simple example but you'd do anything klarna specific in here.
     *
     * @return  string
     */
    public function takePayment()
    {
        return "Took payment using Paypal for " . $this->getAmount();
    }
}

/**
 * Payment Factory
 */
class Payment
{
    public static function factory(string $type)
    {
        switch ($type) {
            case 'Klarna':
                return new KlarnaDriver;
                break;
            case 'Paypal':
                return new PaypalDriver;
                break;
        }
    }
}

// Take £20 payment with Klarna.
echo Payment::factory('Klarna')->setAmount(20)->takePayment();

// Take 5,000 payment with Paypal.
echo Payment::factory('Paypal')->setAmount(5000)->takePayment();