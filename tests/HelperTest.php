<?php

namespace ITCAN\LaravelHelpers\Tests;

use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    /**
     * Check if a given string is a valid UUID.
     *
     * @param string $uuid The string to check
     *
     * @return  bool
     */
    protected function isValidUuid($uuid = '')
    {
        if (! is_string($uuid) || (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/',
                    $uuid) !== 1)) {
            return false;
        }

        return true;
    }

    /**
     * Test convertFloat() helper.
     *
     * @return void
     */
    public function testConvertFloat()
    {
        $expected = 1000.55;
        $response = convertFloat('1000,55');
        $this->assertEquals($expected, $response);

        $expected = 1000.55;
        $response = convertFloat('1000.55');
        $this->assertEquals($expected, $response);

        $expected = 1000.55;
        $response = convertFloat(1000.55);
        $this->assertEquals($expected, $response);

        $expected = 1000;
        $response = convertFloat(1000);
        $this->assertEquals($expected, $response);

        $expected = 55.44;
        $response = convertFloat(55.44);
        $this->assertEquals($expected, $response);

        $expected = 55.44;
        $response = convertFloat('55.44');
        $this->assertEquals($expected, $response);

        $expected = 0;
        $response = convertFloat(null);
        $this->assertEquals($expected, $response);
    }

    /**
     * Test calculateTax helper inc tax.
     *
     * @return void
     */
    public function testCalculateTax()
    {
        $ex = false;

        $response = calculateTax(100, 21, $ex);
        $this->assertEquals(17.36, $response);

        $response = calculateTax(100, 6, $ex);
        $this->assertEquals(5.66, $response);

        $response = calculateTax(100, 6.00, $ex);
        $this->assertEquals(5.66, $response);

        $response = calculateTax(100, '6.00', $ex);
        $this->assertEquals(5.66, $response);

        $response = calculateTax(100, '6', $ex);
        $this->assertEquals(5.66, $response);

        $response = calculateTax(100, 0, $ex);
        $this->assertEquals(0, $response);

        $response = calculateTax(100, '0', $ex);
        $this->assertEquals(0, $response);

        $ex = true;

        $response = calculateTax(100, 21, $ex);
        $this->assertEquals(21, $response);

        $response = calculateTax(100, 6, $ex);
        $this->assertEquals(6, $response);

        $response = calculateTax(100, 6.00, $ex);
        $this->assertEquals(6, $response);

        $response = calculateTax(100, '6.00', $ex);
        $this->assertEquals(6, $response);

        $response = calculateTax(100, '6', $ex);
        $this->assertEquals(6, $response);

        $response = calculateTax(100, 0, $ex);
        $this->assertEquals(0, $response);

        $response = calculateTax(100, '0', $ex);
        $this->assertEquals(0, $response);
    }

    /**
     * Test convertPercent helper.
     *
     * @return void
     */
    public function testConvertPercent()
    {
        $response = convertPercent(21);
        $this->assertEquals(0.21, $response);

        $response = convertPercent(6);
        $this->assertEquals(0.06, $response);
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testGetHost()
    {
        $response = getHost('www.google.nl', true);
        $expected = 'www.google.nl';

        $this->assertEquals($expected, $response);

        $response = getHost('www.google.nl', false);
        $expected = 'google.nl';

        $this->assertEquals($expected, $response);

        $response = getHost('www.google.co.uk', true);
        $expected = 'www.google.co.uk';

        $this->assertEquals($expected, $response);

        $response = getHost('www.google.co.uk', false);
        $expected = 'google.co.uk';

        $this->assertEquals($expected, $response);

        $response = getHost('test.google.nl', true);
        $expected = 'test.google.nl';

        $this->assertEquals($expected, $response);

        $response = getHost('test.google.nl', false);
        $expected = 'google.nl';

        $this->assertEquals($expected, $response);

        $response = getHost('test.google.co.uk', true);
        $expected = 'test.google.co.uk';

        $this->assertEquals($expected, $response);

        $response = getHost('test.google.co.uk', false);
        $expected = 'google.co.uk';

        $this->assertEquals($expected, $response);

        $response = getHost('http://www.google.nl', true);
        $expected = 'www.google.nl';

        $this->assertEquals($expected, $response);

        $response = getHost('http://www.google.nl', false);
        $expected = 'google.nl';

        $this->assertEquals($expected, $response);

        $response = getHost('https://www.google.nl', true);
        $expected = 'www.google.nl';

        $this->assertEquals($expected, $response);

        $response = getHost('https://www.google.nl', false);
        $expected = 'google.nl';

        $this->assertEquals($expected, $response);

        $response = getHost('https://tweakers.net/nieuws/120361/google-geeft-alle-toekomstige-chromebooks-ondersteuning-voor-android-apps.html',
            true);
        $expected = 'tweakers.net';

        $this->assertEquals($expected, $response);

        $response = getHost('https://tweakers.net/nieuws/120361/google-geeft-alle-toekomstige-chromebooks-ondersteuning-voor-android-apps.html',
            false);
        $expected = 'tweakers.net';

        $response = getHost('https://www.tweakers.net/nieuws/120361/google-geeft-alle-toekomstige-chromebooks-ondersteuning-voor-android-apps.html',
            true);
        $expected = 'www.tweakers.net';

        $this->assertEquals($expected, $response);

        $response = getHost('https://www.tweakers.net/nieuws/120361/google-geeft-alle-toekomstige-chromebooks-ondersteuning-voor-android-apps.html',
            false);
        $expected = 'tweakers.net';

        $this->assertEquals($expected, $response);

        $response = getHost('https://tweakers.net/categorie/47/moederborden/producten/#filter:q1YqKMpMTvXNzFOyMtBRKi5ITXbLzClJLSpWsqpWMjY3AFFliTlKVtFKFkbGRkqxtbW1AA ',
            true);
        $expected = 'tweakers.net';

        $this->assertEquals($expected, $response);

        $response = getHost('https://tweakers.net/categorie/47/moederborden/producten/#filter:q1YqKMpMTvXNzFOyMtBRKi5ITXbLzClJLSpWsqpWMjY3AFFliTlKVtFKFkbGRkqxtbW1AA ',
            false);
        $expected = 'tweakers.net';

        $this->assertEquals($expected, $response);

        $response = getHost('https://www.tweakers.net/categorie/47/moederborden/producten/#filter:q1YqKMpMTvXNzFOyMtBRKi5ITXbLzClJLSpWsqpWMjY3AFFliTlKVtFKFkbGRkqxtbW1AA ',
            true);
        $expected = 'www.tweakers.net';

        $this->assertEquals($expected, $response);

        $response = getHost('https://www.tweakers.net/categorie/47/moederborden/producten/#filter:q1YqKMpMTvXNzFOyMtBRKi5ITXbLzClJLSpWsqpWMjY3AFFliTlKVtFKFkbGRkqxtbW1AA ',
            false);
        $expected = 'tweakers.net';

        $this->assertEquals($expected, $response);
    }

    /**
     * Test randomCode() helper.
     *
     * @return void
     */
    public function testRandomCode()
    {
        $this->assertTrue($this->isValidUuid(randomCode()));

        $this->assertFalse($this->isValidUuid('123'));

        $this->assertFalse($this->isValidUuid());
    }

    /**
     * Test randomFilename().
     */
    public function testRandomFilename()
    {
        $response = randomFilename('.', 'jpg');
        $this->assertEquals(45, strlen($response));

        $response = randomFilename('.', 'jpg', 'test');
        $this->assertStringContainsString('test_', $response);

        $response = randomFilename('.', 'jpg', 'TEST');
        $this->assertStringContainsString('test_', $response);

        $response = randomFilename('.', 'jpg', 'test test');
        $this->assertStringContainsString('test_test_', $response);

        $response = randomFilename('.', 'xml', 'phpunit');
        $this->assertStringContainsString('phpunit_', $response);

        $response = randomFilename('.', 'xml', 'PHPUNIT');
        $this->assertStringContainsString('phpunit_', $response);
    }

    /**
     * Test ibanMachine().
     */
    public function testIbanMachine()
    {
        $response = ibanMachine('NL68ABNA0484748203');
        $this->assertEquals($response, 'NL68ABNA0484748203');

        $response = ibanMachine('NL68 ABNA 0484 7482 03');
        $this->assertEquals($response, 'NL68ABNA0484748203');

        $response = ibanMachine('123');
        $this->assertEquals($response, '123');
    }

    /**
     * Test ibanMachine().
     */
    public function testIbanHuman()
    {
        $response = ibanHuman('NL68ABNA0484748203');
        $this->assertEquals($response, 'NL68 ABNA 0484 7482 03');

        $response = ibanHuman('NL68 ABNA 0484 7482 03');
        $this->assertEquals($response, 'NL68 ABNA 0484 7482 03');

        $response = ibanHuman('123123123');
        $this->assertEquals($response, '1231 2312 3');
    }

    /**
     * Test now() and carbon().
     */
    public function testNow()
    {
        $response = carbon();
        $this->assertInstanceOf(\Carbon\Carbon::class, $response);
    }

    public function testSelectArray()
    {
        $response = selectArray([1 => 'test']);
        $this->assertEquals($response, [1 => 'test']);

        $response = selectArray([1 => 'test'], 'test');
        $this->assertEquals($response, ['' => 'test', 1 => 'test']);

        $response = selectArray(collect([1 => 'testje', 2 => 'testtest']));
        $this->assertEquals($response, [1 => 'testje', 2 => 'testtest']);

        $response = selectArray(collect([1 => 'testje', 2 => 'testtest']), '-- choose --');
        $this->assertEquals($response, ['' => '-- choose --', 1 => 'testje', 2 => 'testtest']);

        $response = selectArray(collect([1 => 'testje', 2 => 'testtest']), [0 => '-- choose --']);
        $this->assertEquals($response, [0 => '-- choose --', 1 => 'testje', 2 => 'testtest']);
    }

    public function testNullOrValue()
    {
        $this->assertEquals(nullOrValue('test'), 'test');
        $this->assertEquals(nullOrValue(123), 123);
        $this->assertEquals(nullOrValue(0), 0);

        $this->assertEquals(nullOrValue('test', false), 'test');
        $this->assertEquals(nullOrValue(123, false), 123);
        $this->assertEquals(nullOrValue(0, false), 0);

        $this->assertEquals(nullOrValue('0', true), null);
        $this->assertEquals(nullOrValue('0', false), '0');

        $this->assertEquals(nullOrValue('', true), null);
        $this->assertEquals(nullOrValue('', false), null);
    }

    /** @test */
    public function email_it_returns_false_for_null()
    {
        $this->assertFalse(validEmail(null));
    }

    /** @test */
    public function email_it_returns_false_for_boolean_true()
    {
        $this->assertFalse(validEmail(true));
    }

    /** @test */
    public function email_it_returns_false_for_boolean_false()
    {
        $this->assertFalse(validEmail(false));
    }

    /** @test */
    public function email_it_returns_false_for_integer()
    {
        $this->assertFalse(validEmail(123));
    }

    /** @test */
    public function email_it_returns_false_for_float()
    {
        $this->assertFalse(validEmail(123.45));
    }

    /** @test */
    public function email_it_returns_false_for_empty_array()
    {
        $this->assertFalse(validEmail([]));
    }

    /** @test */
    public function email_it_returns_false_for_array()
    {
        $this->assertFalse(validEmail(['user@example.com']));
    }

    /** @test */
    public function email_it_returns_false_for_an_object()
    {
        $this->assertFalse(validEmail(new \stdClass));
    }

    /** @test */
    public function email_it_returns_false_for_invalid_emails()
    {
        $this->assertFalse(validEmail('user'));
        $this->assertFalse(validEmail('user@'));
        $this->assertFalse(validEmail('user@example'));
        $this->assertFalse(validEmail('user@example.'));
    }

    /** @test */
    public function email_it_returns_true_for_valid_emails()
    {
        $this->assertTrue(validEmail('user@example.com'));
        $this->assertTrue(validEmail('user.name@example.com'));
        $this->assertTrue(validEmail('user.name-long@example.com'));
    }

    public function test_sanitizeFilename()
    {
        $response = sanitizeFilename('./<!--foo-->', '');
        $this->assertEquals('foo', $response);

        $response = sanitizeFilename('ja.test.docx');
        $this->assertEquals('ja.test.docx', $response);

        $response = sanitizeFilename('ja\test.docx');
        $this->assertEquals('ja-test.docx', $response);

        $response = sanitizeFilename('bla/bla.pdf');
        $this->assertEquals('bla-bla.pdf', $response);
    }

    protected function getXmlFileLocation($name)
    {
        return __DIR__ . '/XML/' . $name;
    }

    protected function getXmlFileContent($name)
    {
        return file_get_contents($this->getXmlFileLocation($name));
    }

    protected function parseXML($content = '')
    {
        if (! isValidXML($content)) {
            return false;
        }

        return simplexml_load_string($content);
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testIsValidXML()
    {
        $this->assertFalse(isValidXML('NL 82 RABO 0111111111'));

        $this->assertTrue(isValidXML($this->getXmlFileContent('string.xml')));

        $this->assertFalse(isValidXML($this->getXmlFileContent('test.txt')));

        $this->assertFalse(isValidXML('<!DOCTYPE html><html><body></body></html>'));

        $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"><url><loc><![CDATA[ test]]></loc><image:image><image:loc><![CDATA[test]]></image:loc><image:title><![CDATA[test]]></image:title></image:image><lastmod>2018-06-06T10:45:45+00:00</lastmod><changefreq>weekly</changefreq><priority>0.5</priority></url><url><loc><![CDATA[test2]]></loc><image:image><image:loc><![CDATA[test2]]></image:loc><image:title><![CDATA[test2]]></image:title></image:image><lastmod>2018-04-17T09:23:18+00:00</lastmod><changefreq>weekly</changefreq><priority>0.5</priority></url></urlset>';

        $this->assertTrue(isValidXML($xml));

        $xml = '<url><loc><![CDATA[ test]]></loc><image:image><image:loc><![CDATA[test]]></image:loc><image:title><![CDATA[test]]></image:title></image:image><lastmod>2018-06-06T10:45:45+00:00</lastmod><changefreq>weekly</changefreq><priority>0.5</priority></url>';

        $this->assertFalse(isValidXML($xml));

        $xml = '<url><loc><![CDATA[ test]]></loc><lastmod>2018-06-06T10:45:45+00:00</lastmod><changefreq>weekly</changefreq><priority>0.5</priority></url>';

        $this->assertTrue(isValidXML($xml));

        $xml = '<loc><![CDATA[ testï]]></loc>';

        $this->assertTrue(isValidXML($xml));
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCheckParseXML()
    {
        $response = $this->parseXML($this->getXmlFileContent('string.xml'));
        $this->assertInstanceOf('SimpleXMLElement', $response);

        $response = $this->parseXML($this->getXmlFileContent('test.txt'));
        $this->assertFalse($response);
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testDomainName()
    {
        $response = domainName('www.bier.google.nl');
        $expected = 'google.nl';
        $this->assertEquals($expected, $response);

        $response = domainName('www.google.nl');
        $expected = 'google.nl';
        $this->assertEquals($expected, $response);

        $response = domainName('www.google.nl');
        $expected = 'google.nl';
        $this->assertEquals($expected, $response);

        $response = domainName('www.google.co.uk');
        $expected = 'google.co.uk';
        $this->assertEquals($expected, $response);

        $response = domainName('www.google.co.uk');
        $expected = 'google.co.uk';
        $this->assertEquals($expected, $response);

        $response = domainName('test.google.nl');
        $expected = 'google.nl';
        $this->assertEquals($expected, $response);

        $response = domainName('test.google.nl');
        $expected = 'google.nl';
        $this->assertEquals($expected, $response);

        $response = domainName('test.google.co.uk');
        $expected = 'google.co.uk';
        $this->assertEquals($expected, $response);

        $response = domainName('test.google.co.uk');
        $expected = 'google.co.uk';
        $this->assertEquals($expected, $response);

        $response = domainName('http://www.google.nl');
        $expected = 'google.nl';
        $this->assertEquals($expected, $response);

        $response = domainName('http://www.google.nl');
        $expected = 'google.nl';
        $this->assertEquals($expected, $response);

        $response = domainName('https://www.google.nl');
        $expected = 'google.nl';
        $this->assertEquals($expected, $response);

        $response = domainName('https://www.google.nl');
        $expected = 'google.nl';
        $this->assertEquals($expected, $response);

        $response = domainName('https://tweakers.net/nieuws/120361/google-geeft-alle-toekomstige-chromebooks-ondersteuning-voor-android-apps.html');
        $expected = 'tweakers.net';
        $this->assertEquals($expected, $response);

        $response = domainName('https://tweakers.net/nieuws/120361/google-geeft-alle-toekomstige-chromebooks-ondersteuning-voor-android-apps.html');
        $expected = 'tweakers.net';
        $this->assertEquals($expected, $response);

        $response = domainName('https://www.tweakers.net/nieuws/120361/google-geeft-alle-toekomstige-chromebooks-ondersteuning-voor-android-apps.html');
        $expected = 'tweakers.net';
        $this->assertEquals($expected, $response);

        $response = domainName('https://www.tweakers.net/nieuws/120361/google-geeft-alle-toekomstige-chromebooks-ondersteuning-voor-android-apps.html');
        $expected = 'tweakers.net';
        $this->assertEquals($expected, $response);

        $response = domainName('https://tweakers.net/categorie/47/moederborden/producten/#filter:q1YqKMpMTvXNzFOyMtBRKi5ITXbLzClJLSpWsqpWMjY3AFFliTlKVtFKFkbGRkqxtbW1AA ');
        $expected = 'tweakers.net';
        $this->assertEquals($expected, $response);

        $response = domainName('https://tweakers.net/categorie/47/moederborden/producten/#filter:q1YqKMpMTvXNzFOyMtBRKi5ITXbLzClJLSpWsqpWMjY3AFFliTlKVtFKFkbGRkqxtbW1AA ');
        $expected = 'tweakers.net';
        $this->assertEquals($expected, $response);

        $response = domainName('https://www.tweakers.net/categorie/47/moederborden/producten/#filter:q1YqKMpMTvXNzFOyMtBRKi5ITXbLzClJLSpWsqpWMjY3AFFliTlKVtFKFkbGRkqxtbW1AA ');
        $expected = 'tweakers.net';
        $this->assertEquals($expected, $response);

        $response = domainName('https://www.tweakers.net/categorie/47/moederborden/producten/#filter:q1YqKMpMTvXNzFOyMtBRKi5ITXbLzClJLSpWsqpWMjY3AFFliTlKVtFKFkbGRkqxtbW1AA ');
        $expected = 'tweakers.net';
        $this->assertEquals($expected, $response);
    }

    public function testDomainNameWithSubdomain()
    {
        $response = domainName('www.bier.google.nl', true);
        $expected = 'www.bier.google.nl';
        $this->assertEquals($expected, $response);

        $response = domainName('www.google.nl', true);
        $expected = 'www.google.nl';
        $this->assertEquals($expected, $response);

        $response = domainName('www.google.nl', true);
        $expected = 'www.google.nl';
        $this->assertEquals($expected, $response);

        $response = domainName('www.google.co.uk', true);
        $expected = 'www.google.co.uk';
        $this->assertEquals($expected, $response);

        $response = domainName('www.google.co.uk', true);
        $expected = 'www.google.co.uk';
        $this->assertEquals($expected, $response);

        $response = domainName('test.google.nl', true);
        $expected = 'test.google.nl';
        $this->assertEquals($expected, $response);

        $response = domainName('test.google.nl', true);
        $expected = 'test.google.nl';
        $this->assertEquals($expected, $response);

        $response = domainName('test.google.co.uk', true);
        $expected = 'test.google.co.uk';
        $this->assertEquals($expected, $response);

        $response = domainName('test.google.co.uk', true);
        $expected = 'test.google.co.uk';
        $this->assertEquals($expected, $response);

        $response = domainName('http://www.google.nl', true);
        $expected = 'www.google.nl';
        $this->assertEquals($expected, $response);

        $response = domainName('http://www.google.nl', true);
        $expected = 'www.google.nl';
        $this->assertEquals($expected, $response);

        $response = domainName('https://www.google.nl', true);
        $expected = 'www.google.nl';
        $this->assertEquals($expected, $response);

        $response = domainName('https://www.google.nl', true);
        $expected = 'www.google.nl';
        $this->assertEquals($expected, $response);

        $response = domainName('https://tweakers.net/nieuws/120361/google-geeft-alle-toekomstige-chromebooks-ondersteuning-voor-android-apps.html', true);
        $expected = 'tweakers.net';
        $this->assertEquals($expected, $response);

        $response = domainName('https://tweakers.net/nieuws/120361/google-geeft-alle-toekomstige-chromebooks-ondersteuning-voor-android-apps.html', true);
        $expected = 'tweakers.net';
        $this->assertEquals($expected, $response);

        $response = domainName('https://www.tweakers.net/nieuws/120361/google-geeft-alle-toekomstige-chromebooks-ondersteuning-voor-android-apps.html', true);
        $expected = 'www.tweakers.net';
        $this->assertEquals($expected, $response);

        $response = domainName('https://www.tweakers.net/nieuws/120361/google-geeft-alle-toekomstige-chromebooks-ondersteuning-voor-android-apps.html', true);
        $expected = 'www.tweakers.net';
        $this->assertEquals($expected, $response);

        $response = domainName('https://tweakers.net/categorie/47/moederborden/producten/#filter:q1YqKMpMTvXNzFOyMtBRKi5ITXbLzClJLSpWsqpWMjY3AFFliTlKVtFKFkbGRkqxtbW1AA ', true);
        $expected = 'tweakers.net';
        $this->assertEquals($expected, $response);

        $response = domainName('https://tweakers.net/categorie/47/moederborden/producten/#filter:q1YqKMpMTvXNzFOyMtBRKi5ITXbLzClJLSpWsqpWMjY3AFFliTlKVtFKFkbGRkqxtbW1AA ', true);
        $expected = 'tweakers.net';
        $this->assertEquals($expected, $response);

        $response = domainName('https://www.tweakers.net/categorie/47/moederborden/producten/#filter:q1YqKMpMTvXNzFOyMtBRKi5ITXbLzClJLSpWsqpWMjY3AFFliTlKVtFKFkbGRkqxtbW1AA ', true);
        $expected = 'www.tweakers.net';
        $this->assertEquals($expected, $response);

        $response = domainName('https://www.tweakers.net/categorie/47/moederborden/producten/#filter:q1YqKMpMTvXNzFOyMtBRKi5ITXbLzClJLSpWsqpWMjY3AFFliTlKVtFKFkbGRkqxtbW1AA ', true);
        $expected = 'www.tweakers.net';
        $this->assertEquals($expected, $response);
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testMarkdownHelper()
    {
        $markdownText = '# Hello World!';
        $expected = "<h1>Hello World!</h1>\n";
        $this->assertEquals($expected, markdown($markdownText));

        $markdownText = 'This is an example of **emphasis**. Note how the text is *wrapped* with the same character(s) before and after.';
        $expected = "<p>This is an example of <strong>emphasis</strong>. Note how the text is <em>wrapped</em> with the same character(s) before and after.</p>\n";
        $this->assertEquals($expected, markdown($markdownText));
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCustomRangeHelper()
    {
        $this->assertCount(11, custom_range(0, 10, 1));

        $this->assertCount(10, custom_range(1, 10, 1));
    }

    /**
     * Test cleanLicensePlate() helper.
     *
     * @return void
     */
    public function testCleanPlate()
    {
        $plate = '10-KFH-4';
        $expected = '10KFH4';
        $this->assertEquals($expected, cleanLicensePlate($plate));

        $plate = '10KFH-4';
        $this->assertEquals($expected, cleanLicensePlate($plate));

        $plate = '10KFH4';
        $this->assertEquals($expected, cleanLicensePlate($plate));
    }

    /**
     * Test formatPlate() helper.
     *
     * @return void
     */
    public function testFormatPlate()
    {
        /**********/
        // PLATE 1
        /**********/
        $plate = 'SK-506-L';
        $expected = 'SK-506-L';

        $this->assertEquals($expected, formatLicensePlate($plate));

        $plate = 'SK506-L';
        $this->assertEquals($expected, formatLicensePlate($plate));

        $plate = 'SK506L';
        $this->assertEquals($expected, formatLicensePlate($plate));

        /**********/
        // PLATE 2
        /**********/
        $plate = 'RX-NL-63';
        $expected = 'RX-NL-63';

        $this->assertEquals($expected, formatLicensePlate($plate));

        $plate = 'RXNL-63';
        $this->assertEquals($expected, formatLicensePlate($plate));

        $plate = 'RXNL63';
        $this->assertEquals($expected, formatLicensePlate($plate));

        /**********/
        // PLATE 3
        /**********/
        $plate = '06-LHS-9';
        $expected = '06-LHS-9';

        $this->assertEquals($expected, formatLicensePlate($plate));

        $plate = '06LHS-9';
        $this->assertEquals($expected, formatLicensePlate($plate));

        $plate = '06LHS9';
        $this->assertEquals($expected, formatLicensePlate($plate));

        /**********/
        // PLATE 4
        /**********/
        $plate = '22CD25';
        $expected = '22-CD-25';

        $this->assertEquals($expected, formatLicensePlate($plate));

        /**********/
        // PLATE 5
        /**********/
        $plate = 'CDJ355';
        $expected = 'CDJ355';

        $this->assertEquals($expected, formatLicensePlate($plate));
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testValidJsonHelper()
    {
        $this->assertFalse(validJson('dfdsafadsadfsfasdfadsfdsa'));

        $this->assertTrue(validJson('{ "Id": 1, "Name": "Coke" }'));
    }
}
