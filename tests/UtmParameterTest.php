<?php

namespace Suarez\UtmParameter\Tests;

use PHPUnit\Framework\TestCase;
use Suarez\UtmParameter\UtmParameter;

class UtmParameterTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $parameters = [
            'utm_source'   => 'google',
            'utm_medium'   => 'cpc',
            'utm_campaign' => '{campaignid}',
            'utm_content'  => '{adgroupid}',
            'utm_term'     => '{targetid}',
        ];

        app()->bind(UtmParameter::class, function () use ($parameters) {
            return new UtmParameter($parameters);
        });
    }

    /** @test */
    public function it_should_have_an_utm_attribute_bag()
    {
        $utm = UtmParameter::all();
        $this->assertIsArray($utm);
        $this->assertNotEmpty($utm);
        $this->assertArrayHasKey('utm_source', $utm);
    }

    /** @test */
    public function it_should_have_a_source_parameter()
    {
        $source = UtmParameter::get('source');
        $this->assertNotEmpty($source);
        $this->assertEquals('google', $source);
    }

    /** @test */
    public function it_should_have_work_with_utm_inside_key()
    {
        $source = UtmParameter::get('utm_source');
        $this->assertNotEmpty($source);
        $this->assertEquals('google', $source);
    }

    /** @test */
    public function it_should_have_a_medium_parameter()
    {
        $medium = UtmParameter::get('medium');
        $this->assertNotEmpty($medium);
        $this->assertEquals('cpc', $medium);
    }

    /** @test */
    public function it_should_have_a_campaign_parameter()
    {
        $campaign = UtmParameter::get('campaign');
        $this->assertNotEmpty($campaign);
        $this->assertEquals('{campaignid}', $campaign);
    }

    /** @test */
    public function it_should_have_a_content_parameter()
    {
        $content = UtmParameter::get('content');
        $this->assertNotEmpty($content);
        $this->assertEquals('{adgroupid}', $content);
    }

    /** @test */
    public function it_should_have_a_term_parameter()
    {
        $term = UtmParameter::get('term');
        $this->assertNotEmpty($term);
        $this->assertEquals('{targetid}', $term);
    }

    /** @test */
    public function it_should_get_a_utm_parameter_via_helper()
    {
        $source = get_utm('source');
        $this->assertNotEmpty($source);
        $this->assertEquals('google', $source);
    }

    /** @test */
    public function it_should_determine_if_utm_has_key()
    {
        $hasSource = UtmParameter::has('source');
        $this->assertIsBool($hasSource);
        $this->assertTrue($hasSource);
    }

    /** @test */
    public function it_should_determine_if_utm_has_not_key()
    {
        $hasRandomKey = UtmParameter::has('random-key');
        $this->assertIsBool($hasRandomKey);
        $this->assertFalse($hasRandomKey);
    }

    /** @test */
    public function it_should_determine_if_utm_has_key_and_value()
    {
        $hasGoogleSource = UtmParameter::has('utm_source', 'google');
        $this->assertIsBool($hasGoogleSource);
        $this->assertTrue($hasGoogleSource);
    }

    /** @test */
    public function it_should_determine_if_utm_has_not_key_and_value()
    {
        $hasRandomSource = UtmParameter::has('random-source', 'random-value');
        $this->assertIsBool($hasRandomSource);
        $this->assertFalse($hasRandomSource);
    }

    /** @test */
    public function it_should_determine_if_a_key_exists_for_utm_parameters()
    {
        $hasSource = has_utm('source');
        $this->assertIsBool($hasSource);
        $this->assertTrue($hasSource);
    }

    /** @test */
    public function it_should_determine_if_a_utm_parameter_equals_a_value()
    {
        $isGoogle = has_utm('source', 'google');
        $this->assertIsBool($isGoogle);
        $this->assertTrue($isGoogle);
    }

    /** @test */
    public function it_should_determine_if_a_key_does_not_exists_for_utm_parameters()
    {
        $hasRandomKey = has_not_utm('random-key');
        $this->assertIsBool($hasRandomKey);
        $this->assertTrue($hasRandomKey);
    }

    /** @test */
    public function it_should_determine_if_a_utm_parameter_not_equals_a_value()
    {
        $isRandomSource = has_not_utm('source', 'random');
        $this->assertIsBool($isRandomSource);
        $this->assertTrue($isRandomSource);
    }
}
