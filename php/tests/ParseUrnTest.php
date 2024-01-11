<?php

use PHPUnit\Framework\TestCase;
use Pubconnect\IsbnUrn\IsbnUrn;

require_once 'src/IsbnUrn.php';

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

    public function testParseUrns(){  
          $urnStrings[] = "urn:isbn:9795363916662";
          $urnStrings[] = "urn:isbn:9795363916662?segmentnum=5";
          $urnStrings[] = "urn:isbn:9795363916662?tocitem=3.3.3";
          $urnStrings[] = "urn:isbn:9795363916662?tocitem=3.3.3#offset(150)";
          $urnStrings[] = "urn:isbn:9795363916662?tocitem=3.3.3#offset(10,34)";
          $urnStrings[] = "urn:isbn:9795363916662?tocitem=3.3.3#offset(10,34)de+lelijke+vos+sprong+in+de+bosjes";
           
          foreach($urnStrings as $urnString){
            $urnParser = new IsbnUrn($urnString);

             // Implement tests for each URN string
            $this->assertEquals($urnString, $urnParser->getUrn());
            $this->assertEquals('isbn', $urnParser->getNamespaceIdentifier());
            $this->assertEquals('9795363916662', $urnParser->getNamespace());

            if (strpos($urnString, '?') !== false) {
                $this->assertStringContainsString("?", $urnParser->getUrn());
            }  
            
            if (strpos($urnString, 'tocitem') !== false) {
                $this->assertStringContainsString("tocitem", $urnParser->getUrn());
                // Check if toc item is set and valid
                $this->assertEquals('3.3.3', $urnParser->getTocItem());
            }

            if (strpos($urnString, 'segmentnum') !== false) {
                $this->assertStringContainsString("segmentnum", $urnParser->getUrn());
                // Check if segment number is set and valid
                $this->assertEquals('5', $urnParser->getSegmentNum());
            }

            if (strpos($urnString, 'offset') !== false) {
                $this->assertStringContainsString("offset", $urnParser->getUrn());
                // Check if offset is set and valid
                if (strpos($urnString, '10,34') !== false) {
                    $this->assertEquals(['start' => '10', 'length' => '34'], $urnParser->getOffset());
                }
                if (strpos($urnString, '150') !== false) {
                    $this->assertEquals(['start' => '150'], $urnParser->getOffset());
                }
            }

            if (strpos($urnString, 'de+lelijke+vos+sprong+in+de+bosjes') !== false) {
                $this->assertStringContainsString("de+lelijke+vos+sprong+in+de+bosjes", $urnParser->getUrn());
                // Check if text fragment is set and valid
                $this->assertEquals('de lelijke vos sprong in de bosjes', $urnParser->getTextFragment());
            }
        }
    }
}
