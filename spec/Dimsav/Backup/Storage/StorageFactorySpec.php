<?php namespace spec\Dimsav\Backup\Storage;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StorageFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Dimsav\Backup\Storage\StorageFactory');
    }

}
