<?php

namespace spec\Acme;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CalculatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Acme\Calculator');
        $this->toHtml("Hi, there")->shouldReturn("<p>Hi, there</p>");
    }
}
