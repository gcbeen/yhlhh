<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Config');
    }

    function it_gets_and_sets_a_configuration_option()
    {
        $this->get('foo')->shouldReturn(null);
     
        $this->set('foo', 'bar');
     
        $this->get('foo')->shouldReturn('bar');
    }

    function it_gets_a_default_value_when_option_is_not_set()
    {
        $this->get('foo', 'bar')->shouldReturn('bar');
     
        $this->set('foo', 'baz');
     
        $this->get('foo', 'bar')->shouldReturn('baz');
    }

}
