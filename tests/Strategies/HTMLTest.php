<?php

namespace SunAsterisk\DomainVerifier\Tests\Strategies;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use SunAsterisk\DomainVerifier\Contracts\Models\DomainVerifiableInterface;
use SunAsterisk\DomainVerifier\Strategies\HTML;

class HTMLTest extends TestCase
{
    protected $verification;
    protected $url;
    protected $metaTags;

    protected function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->verification = new \stdClass();
        $this->verification->token = '123';

        $this->url = 'https://domain.local';

        $this->metaTags = [
            'author'=> 'sun*',
            'domain-verification' => '123',
        ];
    }

    public function test_it_can_verify_html_tag()
    {
        /** Mock DomainVerifiable*/
        $domainVerifiable = m::mock(DomainVerifiableInterface::class);
        $domainVerifiable->shouldReceive('getKey')->andReturns(123);

        /** Mock facade */
        m::mock('alias:\SunAsterisk\DomainVerifier\DomainVerificationFacade')
            ->shouldReceive('getTokenFor')
            ->andReturn($this->verification);

        /** @var HTML|m\MockInterface $strategy */
        $strategy = m::mock(HTML::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $strategy->shouldReceive('getMetaTags')->with($this->url)->andReturn($this->metaTags);
        $strategy->shouldReceive('getToken')->with($this->metaTags)->andReturn('123');
        $result = $strategy->verify($this->url, $domainVerifiable);

        $this->assertTrue($result);
    }
}
