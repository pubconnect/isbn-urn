<?php

use PHPUnit\Framework\TestCase;
use Pubconnect\IsbnUrn\IsbnUrn;

require_once 'src/IsbnUrn.class.php';

/*
 * @example UPDATE :: a existing URN string
 *   $urnString = "urn:isbn:9795363916662";
 *   $urnParser = new IsbnUrn($urnString);
 *   $urnParser->setTocItem('4.3.2');
 *   $urnParser->setOffset(0, 340);
 *   $urnParser->setTextFragment('de lelijke vos sprong in de bosjes');
 *   echo $urnParser->getUrn();
 *   echo PHP_EOL.PHP_EOL;
 * 
*/
class UpdateUrnTest extends TestCase
{
    private $urnParser;

    protected function setUp(): void
    {
        $this->urnParser = new IsbnUrn("urn:isbn:9795363916662?tocitem=3.3.3#offset(10,34)de+lelijke+vos+sprong+in+de+bosjes");
    }

    public function testUpdateNamespaceIdentifier()
    {
        $this->urnParser->setNamespaceIdentifier('ieee');
        $this->assertEquals('ieee', $this->urnParser->getNamespaceIdentifier());
        $this->assertStringContainsString("urn:ieee:", $this->urnParser->getUrn());
    }

    public function testUpdateNamespace()
    {
        $this->urnParser->setNamespace('2666193635979');
        $this->assertEquals('2666193635979', $this->urnParser->getNamespace());
        $this->assertStringContainsString(":2666193635979", $this->urnParser->getUrn());
    }

    public function testUpdateTocItem()
    {
        $this->urnParser->setTocItem('1.1.1');
        $this->assertEquals('1.1.1', $this->urnParser->getTocItem());
        $this->assertStringContainsString("?tocitem=1.1.1", $this->urnParser->getUrn());
    }

    public function testUpdateOffset()
    {
        $this->urnParser->setOffset(20, 400);
        $this->assertEquals(['start' => 20, 'length' => 400], $this->urnParser->getOffset());
        $this->assertStringContainsString("#offset(20,400)", $this->urnParser->getUrn());
    }

    public function testUpdateTextFragment()
    {
        $this->urnParser->setTextFragment('de vos sprong in de bosjes');
        $this->assertEquals('de vos sprong in de bosjes', $this->urnParser->getTextFragment());
        $this->assertStringContainsString("de+vos+sprong+in+de+bosjes", $this->urnParser->getUrn());
    }
}
