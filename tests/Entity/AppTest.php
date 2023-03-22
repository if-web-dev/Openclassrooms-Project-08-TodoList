<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AppTest extends TestCase
{
    public function testTastsAreWorking () {
        $this->assertEquals(2, 1+1);
    }
}