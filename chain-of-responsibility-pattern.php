<?php

/**
 * The Handler interface declares a method for building the chain of handlers.
 * It also declares a method for executing a request.
 */
interface Handler
{
    public function setNext(Handler $handler): Handler;
    public function handle(string $request): ?string;
}

/**
 * The default chaining behavior can be implemented inside a base handler class.
 */
abstract class AbstractHandler implements Handler
{
    /**
     * @var Handler
     */
    private $nextHandler;

    public function setNext(Handler $handler): Handler
    {
        $this->nextHandler = $handler;
        // Returning a handler from here will let us link handlers in a
        // convenient way like this:
        // $stu->setNext($cheryl)->setNext($millie)->setNext($harry);
        return $handler;
    }

    public function handle(string $request): ?string
    {
        if ($this->nextHandler) {
            return $this->nextHandler->handle($request);
        }

        return null;
    }
}

/**
 * All Concrete Handlers either handle a request or pass it to the next handler
 * in the chain.
 */
class StuHandler extends AbstractHandler
{
    public function handle(string $request): ?string
    {
        if ($request === "Mixed Grill") {
            return "Stu: I'll eat the " . $request . ".\n";
        } else {
            return parent::handle($request);
        }
    }
}

class CherylHandler extends AbstractHandler
{
    public function handle(string $request): ?string
    {
        if ($request === "Prawns") {
            return "Cheryl: I'll eat the " . $request . ".\n";
        } else {
            return parent::handle($request);
        }
    }
}

class MillieHandler extends AbstractHandler
{
    public function handle(string $request): ?string
    {
        if ($request === "Nandos") {
            return "Millie: I'll eat the " . $request . ".\n";
        } else {
            return parent::handle($request);
        }
    }
}

class HarryHandler extends AbstractHandler
{
    public function handle(string $request): ?string
    {
        if ($request === "McDonalds") {
            return "Harry: I'll eat the " . $request . ".\n";
        } else {
            return parent::handle($request);
        }
    }
}

/**
 * The client code is usually suited to work with a single handler. In most
 * cases, it is not even aware that the handler is part of a chain.
 */
function clientCode(Handler $handler)
{
    foreach (["Nandos", "Mixed Grill", "Cup of coffee", "Burger King", "McDonalds", "Prawns"] as $food) {
        echo "Client: Who wants a " . $food . "?\n";
        $result = $handler->handle($food);
        if ($result) {
            echo "  " . $result;
        } else {
            echo "  " . $food . " was left alone.\n";
        }
    }
}

/**
 * The other part of the client code constructs the actual chain.
 */
$stu = new StuHandler();
$cheryl = new CherylHandler();
$millie = new MillieHandler();
$harry = new HarryHandler();

$stu->setNext($cheryl)->setNext($millie)->setNext($harry);

/**
 * The client should be able to send a request to any handler, not just the
 * first one in the chain.
 */
echo "Chain: Stu > Cheryl > Millie > Harry\n\n";
clientCode($stu);
echo "\n";

echo "Subchain: Millie > Harry\n\n";
clientCode($millie);