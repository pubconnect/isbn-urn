<?php

use PHPUnit\Framework\TestCase;
use Pubconnect\IsbnUrn\IsbnUrn;

require_once 'src/IsbnUrn.class.php';

class ParseUrnTest extends TestCase
{
    private $urnParser;

    protected function setUp(): void
    {
        $this->urnParser = new IsbnUrn();
    }

    public function testNamespaceIdentifier()
    {
        $this->urnParser->setNamespaceIdentifier('isbn');
        $this->assertEquals('isbn', $this->urnParser->getNamespaceIdentifier());
    }

    public function testNamespace()
    {
        $this->urnParser->setNamespace('9795363916662');
        $this->assertEquals('9795363916662', $this->urnParser->getNamespace());
    }

    public function testTocItem()
    {
        $this->urnParser->setTocItem('3.3.3');
        $this->assertEquals('3.3.3', $this->urnParser->getTocItem());
    }

    public function testOffset()
    {
        $this->urnParser->setOffset(10, 34);
        $this->assertEquals(['start' => 10, 'length' => 34], $this->urnParser->getOffset());
    }

    public function testTextFragment()
    {
        $this->urnParser->setTextFragment('de lelijke vos sprong in de bosjes');
        $this->assertEquals('de lelijke vos sprong in de bosjes', $this->urnParser->getTextFragment());
    }

    public function testParseUrn()
    {
        $urnParser = new IsbnUrn("urn:isbn:9795363916662?tocitem=3.3.3#offset(10,34)de+lelijke+vos+sprong+in+de+bosjes");
        $this->assertEquals("urn:isbn:9795363916662?tocitem=3.3.3#offset(10,34)de+lelijke+vos+sprong+in+de+bosjes", $urnParser->getUrn());
        $this->assertEquals('isbn', $urnParser->getNamespaceIdentifier());
        $this->assertEquals('9795363916662', $urnParser->getNamespace());
        $this->assertEquals('3.3.3', $urnParser->getTocItem());
        $this->assertEquals(['start' => '10', 'length' => '34'], $urnParser->getOffset());
        $this->assertEquals('de lelijke vos sprong in de bosjes', $urnParser->getTextFragment());
    }
}
