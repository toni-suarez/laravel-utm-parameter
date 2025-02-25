<?php

namespace Suarez\UtmParameter\Tests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase;
use Suarez\UtmParameter\Facades\UtmParameter;

class UtmParameterTest extends TestCase
{
    protected $sessionKey;

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('utm-parameter.override_utm_parameters', false);
        Config::set('utm-parameter.session_key', 'custom_utm_key');
        $this->sessionKey = Config::get('utm-parameter.session_key');

        $parameters = [
            'utm_source' => 'google',
            'utm_medium' => 'cpc',
            'utm_campaign' => '{campaignid}',
            'utm_content' => '{adgroupid}',
            'utm_term' => '{targetid}',
        ];

        $request = Request::create('/test', 'GET', $parameters);

        UtmParameter::boot($request);
    }

    protected function tearDown(): void
    {
        session()->forget($this->sessionKey);

        Config::set('utm-parameter.override_utm_parameters', null);
        Config::set('utm-parameter.session_key', null);

        parent::tearDown();
    }

    public function test_it_should_be_bound_in_the_app()
    {
        $utm = app(UtmParameter::class);
        $this->assertInstanceOf(UtmParameter::class, $utm);
    }

    public function test_it_should_work_without_parameters()
    {
        session()->forget($this->sessionKey);

        $request = Request::create('/test', 'GET', []);
        UtmParameter::boot($request);

        $parameters = UtmParameter::all();
        $this->assertIsArray($parameters);
        $this->assertEmpty($parameters);
    }

    public function test_it_should_have_a_session_key()
    {
        $this->assertIsString($this->sessionKey);
    }

    public function test_it_should_have_a_session()
    {
        $sessionContent = session($this->sessionKey);
        $this->assertIsArray($sessionContent);
        $this->assertArrayHasKey('utm_source', $sessionContent);
        $this->assertIsNotString(session($this->sessionKey));
    }

    public function test_it_should_also_clear_a_session()
    {
        $sessionContent = session($this->sessionKey);
        $this->assertIsArray($sessionContent);

        $sessionEmptyContent = session()->forget($this->sessionKey);
        $this->assertIsNotArray($sessionEmptyContent);
        $this->assertNull($sessionEmptyContent);
    }

    public function test_it_should_have_an_utm_attribute_bag()
    {
        $utm = UtmParameter::all();
        $this->assertIsArray($utm);
        $this->assertNotEmpty($utm);
        $this->assertArrayHasKey('utm_source', $utm);
    }

    public function test_it_should_have_a_source_parameter()
    {
        $source = UtmParameter::get('source');
        $this->assertNotEmpty($source);
        $this->assertIsString($source);
        $this->assertEquals('google', $source);
    }

    public function test_it_should_have_work_with_utm_inside_key()
    {
        $source = UtmParameter::get('utm_source');
        $this->assertNotEmpty($source);
        $this->assertIsString($source);
        $this->assertEquals('google', $source);
    }

    public function test_it_should_have_a_medium_parameter()
    {
        $medium = UtmParameter::get('medium');
        $this->assertNotEmpty($medium);
        $this->assertIsString($medium);
        $this->assertEquals('cpc', $medium);
    }

    public function test_it_should_have_a_campaign_parameter()
    {
        $campaign = UtmParameter::get('campaign');
        $this->assertNotEmpty($campaign);
        $this->assertIsString($campaign);
        $this->assertEquals('{campaignid}', $campaign);
    }

    public function test_it_should_have_a_content_parameter()
    {
        $content = UtmParameter::get('content');
        $this->assertNotEmpty($content);
        $this->assertIsString($content);
        $this->assertEquals('{adgroupid}', $content);
    }

    public function test_it_should_have_a_term_parameter()
    {
        $term = UtmParameter::get('term');
        $this->assertNotEmpty($term);
        $this->assertIsString($term);
        $this->assertEquals('{targetid}', $term);
    }

    public function test_it_should_get_a_utm_parameter_via_helper()
    {
        $source = get_utm('source');
        $this->assertNotEmpty($source);
        $this->assertIsString($source);
        $this->assertEquals('google', $source);
    }

    public function test_it_should_determine_if_utm_has_key()
    {
        $hasSource = UtmParameter::has('source');
        $this->assertIsBool($hasSource);
        $this->assertNotEmpty($hasSource);
        $this->assertTrue($hasSource);
    }

    public function test_it_should_determine_if_utm_has_not_key()
    {
        $hasRandomKey = UtmParameter::has('random-key');
        $this->assertIsBool($hasRandomKey);
        $this->assertEmpty($hasRandomKey);
        $this->assertFalse($hasRandomKey);
    }

    public function test_it_should_determine_if_utm_has_key_and_value()
    {
        $hasGoogleSource = UtmParameter::has('utm_source', 'google');
        $this->assertIsBool($hasGoogleSource);
        $this->assertNotEmpty($hasGoogleSource);
        $this->assertTrue($hasGoogleSource);
    }

    public function test_it_should_determine_if_utm_has_not_key_and_value()
    {
        $hasRandomSource = UtmParameter::has('random-source', 'random-value');
        $this->assertIsBool($hasRandomSource);
        $this->assertEmpty($hasRandomSource);
        $this->assertFalse($hasRandomSource);
    }

    public function test_it_should_determine_if_a_key_exists_for_utm_parameters()
    {
        $hasSource = has_utm('source');
        $this->assertIsBool($hasSource);
        $this->assertNotEmpty($hasSource);
        $this->assertTrue($hasSource);
    }

    public function test_it_should_determine_if_a_utm_parameter_equals_a_value()
    {
        $isGoogle = has_utm('source', 'google');
        $this->assertIsBool($isGoogle);
        $this->assertNotEmpty($isGoogle);
        $this->assertTrue($isGoogle);
    }

    public function test_it_should_determine_if_a_key_does_not_exists_for_utm_parameters()
    {
        $hasRandomKey = has_not_utm('random-key');
        $this->assertIsBool($hasRandomKey);
        $this->assertNotEmpty($hasRandomKey);
        $this->assertTrue($hasRandomKey);
    }

    public function test_it_should_determine_if_a_utm_parameter_not_equals_a_value()
    {
        $isRandomSource = has_not_utm('source', 'random');
        $this->assertIsBool($isRandomSource);
        $this->assertNotEmpty($isRandomSource);
        $this->assertTrue($isRandomSource);
    }

    public function test_it_should_determine_if_an_utm_contains_a_value()
    {
        $campaign = UtmParameter::contains('utm_campaign', 'campaign');
        $this->assertIsBool($campaign);
        $this->assertNotEmpty($campaign);
        $this->assertTrue($campaign);
    }

    public function test_it_should_determine_if_an_utm_contains_not_a_value()
    {
        $hasRandomCampaign = UtmParameter::contains('utm_campaign', 'some-thing');
        $this->assertIsBool($hasRandomCampaign);
        $this->assertEmpty($hasRandomCampaign);
        $this->assertFalse($hasRandomCampaign);
    }

    public function test_it_should_determine_if_an_utm_contains_a_non_string_value()
    {
        $campaign = UtmParameter::contains('utm_campaign', 'null');
        $this->assertIsBool($campaign);
        $this->assertFalse($campaign);

        $term = UtmParameter::contains('utm_term', 'false');
        $this->assertIsBool($term);
        $this->assertFalse($term);

        $content = UtmParameter::contains('utm_content', '[]');
        $this->assertIsBool($content);
        $this->assertFalse($content);

        $medium = UtmParameter::contains('utm_medium', '1');
        $this->assertIsBool($medium);
        $this->assertFalse($medium);
    }

    public function test_it_should_determine_if_a_utm_parameter_contains_a_value()
    {
        $isGoogle = contains_utm('source', 'goog');
        $this->assertIsBool($isGoogle);
        $this->assertTrue($isGoogle);
    }

    public function test_it_should_determine_if_a_utm_parameter_not_contains_a_value()
    {
        $isRandomSource = has_not_utm('source', 'random');
        $this->assertIsBool($isRandomSource);
        $this->assertTrue($isRandomSource);
    }

    public function test_it_should_clear_and_remove_the_utm_parameter_again()
    {
        $source = UtmParameter::get('source');
        $this->assertEquals('google', $source);
        $this->assertArrayHasKey('utm_source', session($this->sessionKey));

        UtmParameter::clear();
        $emptySource = UtmParameter::get('source');
        $this->assertNull(session($this->sessionKey));
        $this->assertNull($emptySource);
    }

    public function test_it_should_overwrite_new_utm_parameter()
    {
        Config::set('utm-parameter.override_utm_parameters', true);

        $source = UtmParameter::get('source');
        $this->assertEquals('google', $source);

        $parameters = [
            'utm_source' => 'newsletter',
            'utm_medium' => 'email',
        ];

        $request = Request::create('/', 'GET', $parameters);
        UtmParameter::boot($request);

        $source = UtmParameter::get('source');
        $this->assertEquals('newsletter', $source);

        $medium = UtmParameter::get('utm_medium');
        $this->assertEquals('email', $medium);

        $campaign = UtmParameter::get('campaign');
        $this->assertEquals('{campaignid}', $campaign);
    }

    public function test_it_should_keep_existing_parameters()
    {
        Config::set('utm-parameter.override_utm_parameters', false);

        $source = UtmParameter::get('source');
        $this->assertEquals('google', $source);

        $parameters = [
            'id' => '0123456789',
            'sorting' => 'relevance',
        ];

        $request = Request::create('/test', 'GET', $parameters);
        UtmParameter::boot($request);

        $id = UtmParameter::get('id');
        $this->assertEmpty($id);
        $this->assertNull($id);

        $sorting = UtmParameter::get('sorting');
        $this->assertEmpty($sorting);
        $this->assertNull($sorting);

        $source = UtmParameter::get('source');
        $this->assertEquals('google', $source);

        $medium = UtmParameter::get('utm_medium');
        $this->assertEquals('cpc', $medium);

        $campaign = UtmParameter::get('campaign');
        $this->assertEquals('{campaignid}', $campaign);
    }

    public function test_it_should_keep_existing_parameters_while_browsing()
    {
        $source = UtmParameter::get('source');
        $this->assertEquals('google', $source);

        $parameters = ['id' => '0123456789', 'sorting' => 'relevance'];
        $request = Request::create('/new-page', 'GET', $parameters);
        UtmParameter::boot($request);

        $id = UtmParameter::get('id');
        $this->assertEmpty($id);
        $this->assertNull($id);

        $sorting = UtmParameter::get('sorting');
        $this->assertEmpty($sorting);
        $this->assertNull($sorting);

        $source = UtmParameter::get('source');
        $this->assertEquals('google', $source);

        $parameters = [];
        $request = Request::create('/second-page', 'GET', $parameters);
        UtmParameter::boot($request);

        $id = UtmParameter::get('id');
        $this->assertEmpty($id);
        $this->assertNull($id);

        $sorting = UtmParameter::get('sorting');
        $this->assertEmpty($sorting);
        $this->assertNull($sorting);

        $source = UtmParameter::get('source');
        $this->assertEquals('google', $source);
    }

    public function test_it_should_only_use_utm_parameters_in_the_allowed_list()
    {
        session()->forget($this->sessionKey);
        Config::set('utm-parameter.override_utm_parameters', true);
        Config::set('utm-parameter.allowed_utm_parameters', ['utm_source', 'utm_medium']);

        $parameters = [
            'utm_source' => 'newsletter',
            'utm_medium' => 'email',
            'utm_campaign' => 'not-allowed',
        ];

        $request = Request::create('/test', 'GET', $parameters);
        UtmParameter::boot($request);

        $source = UtmParameter::get('source');
        $this->assertEquals('newsletter', $source);
        $this->assertIsNotInt($source);
        $this->assertNotEmpty($source);

        $medium = UtmParameter::get('medium');
        $this->assertEquals('email', $medium);
        $this->assertIsNotInt($medium);
        $this->assertNotEmpty($medium);

        $campaign = UtmParameter::get('campaign');
        $this->assertNull($campaign);
        $this->assertEmpty($campaign);

        $term = UtmParameter::get('term');
        $this->assertNull($term);
        $this->assertEmpty($term);

        $utm_campaign_id = UtmParameter::get('utm_campaign_id');
        $this->assertNull($utm_campaign_id);
        $this->assertEmpty($utm_campaign_id);
    }

    public function test_it_should_sanitize_utm_parameter()
    {
        Config::set('utm-parameter.override_utm_parameters', true);

        $parameters = [
            'utm_source' => '<span onclick="alert(\'alert\')">google</span>',
            'utm_medium' => 'cpc<script>alert(1)</script>',
            'utm_campaign' => '<script href="x" onload="alert(1)">',
            'utm_content' => '<img src="x" onerror="alert(1)">',
            'utm_term' => '%3Cscript%3Ealert(1)%3C%2Fscript%3E',
            'utm_sql_injection' => '" OR 1=1; --',
            'utm_html_tag' => '<b>bold</b>',
            'utm_<html onclick="alert(\'alert\')">_tag' => '<b>bold</b>',
            'utm_" OR 1=1; --' => '<b>bold</b>',
        ];

        $request = Request::create('/test', 'GET', $parameters);
        UtmParameter::boot($request);

        $source = UtmParameter::get('source');
        $this->assertEquals('&lt;span onclick=&quot;alert(&#039;alert&#039;)&quot;&gt;google&lt;/span&gt;', $source);

        $medium = UtmParameter::get('medium');
        $this->assertEquals('cpc&lt;script&gt;alert(1)&lt;/script&gt;', $medium);

        $campaign = UtmParameter::get('campaign');
        $this->assertEquals('&lt;script href=&quot;x&quot; onload=&quot;alert(1)&quot;&gt;', $campaign);

        $content = UtmParameter::get('content');
        $this->assertEquals('&lt;img src=&quot;x&quot; onerror=&quot;alert(1)&quot;&gt;', $content);

        $term = UtmParameter::get('term');
        $this->assertEquals('%3Cscript%3Ealert(1)%3C%2Fscript%3E', $term);

        $sql = UtmParameter::get('sql_injection');
        $this->assertNull($sql);

        $html = UtmParameter::get('html_tag');
        $this->assertNull($html);

        $randomKey = UtmParameter::get('utm_&lt;html onclick=&quot;alert(&#039;alert&#039;)&quot;&gt;_tag');
        $this->assertNull($randomKey);

        $randomSqlKey = UtmParameter::get('utm_&quot; OR 1=1; --');
        $this->assertNull($randomSqlKey);
    }
}
