<?php

namespace Hepa19\MyTextFilter;

use PHPUnit\Framework\TestCase;

/**
 * Example test class.
 */
class MyTextFilterTest extends TestCase
{
    private $filter;

    /**
     * Setup the controller, before each testcase, just like the router
     * would set it up.
     */
    protected function setUp(): void
    {
        $this->filter = new MyTextFilter();
    }
    /**
     * Construct object and verify that the object has the expected
     * properties. Use no arguments.
     */
    public function testCreateObjectNoArguments()
    {
        $this->assertInstanceOf("\Hepa19\MyTextFilter\MyTextFilter", $this->filter);
    }



    /**
     * Test BBCode filter output
     *
     */
    public function testBBCode()
    {
        $text = '[b]Bold text[/b] [i]Italic text[/i]';

        $res = $this->filter->parse($text, ["bbcode"]);

        $exp = '<strong>Bold text</strong> <em>Italic text</em>';

        $this->assertEquals($res, $exp);
        $this->assertNotEquals($text, $res);
    }



    /**
     * Test Clickable filter output
     *
     */
    public function testClickable()
    {
        $text = "<p>Clickable link: http://dbwebb.se/clickable#id.</p>";

        $res = $this->filter->parse($text, ["link"]);

        $exp = "<p>Clickable link: <a href='http://dbwebb.se/clickable#id\'>http://dbwebb.se/clickable#id</a>.</p>";

        $this->assertEquals($res, $exp);
    }



    /**
     * Test Markdown filter output
     *
     */
    public function testMarkdown()
    {
        $text = 'Some **bold** and *italic* text and a [link](http://dbwebb.se)';
        $res = $this->filter->parse($text, ["markdown"]);

        $exp = '<p>Some <strong>bold</strong> and <em>italic</em> text and a <a href="http://dbwebb.se">link</a></p>
';

        $this->assertEquals($res, $exp);
    }



    /**
     * Test nl2br filter output
     *
     */
    public function testNl2brAndBBCode()
    {
        $text = 'Text and
        ';
        $res = $this->filter->parse($text, ["nl2br"]);

        $exp = 'Text and<br />
        ';

        $this->assertEquals($res, $exp);
    }
}
