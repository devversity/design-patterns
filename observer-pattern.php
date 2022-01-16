<?php

/**
 * Basic Runnable Interface
 */
interface Runnable  {

    /**
     * Run some code
     *
     * @param  mixed $data
     * @param  object $baseClass
     */
    public function run(&$baseClass, $data = null);
}

/**
 * Observer Trait
 */
trait Observable  {

    /**
     * An array of observer objects
     *
     * @var  array
     */
    protected $observers = [];

    /**
     * Get the array of observers
     *
     * @return  array
     */
    public function get()
    {
        return $this->observers;
    }

    /**
     * Attach an observer
     *
     * @param  Runnable $observer
     * @return  $this
     */
    public function attach(Runnable $observer)
    {
        $this->observers[] = $observer;

        return $this;
    }

    /**
     * Detach an observer
     *
     * @param  int $index
     * @return  $this
     */
    public function detach($index)
    {
        unset($this->observers[$index]);

        return $this;
    }

    /**
     * Notify and run the observers
     *
     * @param  mixed $data
     *
     * @return  $this
     */
    public function notify($data = null)
    {
        foreach ($this->observers as $observer) {
            $observer->run($this, $data);
        }

        return $this;
    }

}

/**
 * Person
 */
class Person {

    use Observable;

    public function get()
    {
        return $this->person;
    }
}

/**
 * PersonsName
 */
class PersonsName implements Runnable
{
    public function run(&$baseClass, $data = null)
    {
        $baseClass->person['id'] = 1;
        $baseClass->person['name'] = 'Stuart Todd';
    }
}

/**
 * PersonsDob
 */
class PersonsDob implements Runnable
{
    public function run(&$baseClass, $data = null)
    {
        if ($_ENV['pull_persons_dob'] === true) {
            $baseClass->person['dob'] = '11/06/1983';
        }
    }
}

/**
 * PersonsJob
 */
class PersonsJob implements Runnable
{
    public function run(&$baseClass, $data = null)
    {
        if ($_ENV['pull_persons_job'] === true) {
            $baseClass->person['job'] = 'Developer';
        }
    }
}

/**
 * PersonsAddress
 */
class PersonsAddress implements Runnable
{
    public function run(&$baseClass, $data = null)
    {
        if ($_ENV['pull_persons_address'] === true) {
            $baseClass->person['address'] = 'Somewhere in the UK';
        }
    }
}

/**
 * PersonsLike
 */
class PersonsLikes implements Runnable
{
    public function run(&$baseClass, $data = null)
    {
        if ($_ENV['pull_persons_likes'] === true) {
            $baseClass->person['likes'] = [
                'Icecream',
                'Not going to the gym'
            ];
        }
    }
}

/**
 * EmailNotifier
 */
class EmailNotifier implements Runnable
{
    public function run(&$baseClass, $data = null)
    {
        mail($data['email_address'], $data['subject'], print_r($baseClass, true));
    }
}

/**
 * Data class
 */
class data {
    /**
     * Get Person
     * @return mixed
     */
    public static function getPerson()
    {
        $personModel = (New Person)
            ->attach(New PersonsName)
            ->attach(New PersonsDob)
            ->attach(New PersonsJob)
            ->attach(New PersonsAddress)
            ->attach(New PersonsLikes)
            ->attach(New EmailNotifier)
            ->notify([
                'email_address' => 'stuarttodd444@gmail.com',
                'subject' => 'New Person - ' . date('d-m-Y G:i:s')
            ]);

        // Note - the notify method doesnt need any input
        // In this example i've passed over some data (each observer has access this)
        // Notify's job is to loop through the attached observer classes and
        // run the 'run' method for each one.
        return $personModel->get();
    }
}

$_ENV['pull_persons_dob'] = true;
$_ENV['pull_persons_job'] = true;
$_ENV['pull_persons_address'] = true;
$_ENV['pull_persons_likes'] = true;

print_r(data::getPerson());