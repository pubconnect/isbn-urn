<?php
namespace Pubconnect\IsbnUrn;

/**
 * Class IsbnUrn
 *
 * This class provides a uniform interface for URNs (both to parse and create).
 * 
 * 
 * @example CREATE :: a URN string
 *   $urnParser = new IsbnUrn();
 *   $urnParser->setNamespaceIdentifier('isbn');
 *   $urnParser->setNamespace('9795363916662');
 *   $urnParser->setTocItem('3.3.3');
 *   $urnParser->setOffset(10, 34);
 *   $urnParser->setTextFragment('de lelijke vos sprong in de bosjes');
 *   echo $urnParser->getUrn();
 * 
 * @example UPDATE :: a existing URN string
 *   $urnString = "urn:isbn:9795363916662";
 *   $urnParser = new IsbnUrn($urnString);
 *   $urnParser->setTocItem('4.3.2');
 *   $urnParser->setOffset(0, 340);
 *   $urnParser->setTextFragment('de lelijke vos sprong in de bosjes');
 *   echo $urnParser->getUrn();
 *   echo PHP_EOL.PHP_EOL;
 *  
 * 
 * @example PARSE :: URN strings
 *   $urnStrings[] = "urn:isbn:9795363916662";
 *   $urnStrings[] = "urn:isbn:9795363916662?segmentnum=5";
 *   $urnStrings[] = "urn:isbn:9795363916662?tocitem=3.3.3";
 *   $urnStrings[] = "urn:isbn:9795363916662?tocitem=3.3.3#offset(150)";
 *   $urnStrings[] = "urn:isbn:9795363916662?tocitem=3.3.3#offset(10,34)";
 *   $urnStrings[] = "urn:isbn:9795363916662?tocitem=3.3.3#offset(10,34)de+lelijke+vos+sprong+in+de+bosjes";
 *    
 *   foreach($urnStrings as $urnString){
 *     $urnParser = new IsbnUrn($urnString);
 *     echo "URN: " . $urnParser->getUrn() . PHP_EOL;
 *     echo "Namespace Identifier: " . $urnParser->getNamespaceIdentifier() . PHP_EOL;
 *     echo "Namespace: " . $urnParser->getNamespace() . PHP_EOL;
 *     echo "TOC Item: " . $urnParser->getTocItem() . PHP_EOL;
 *     echo "Segment Number: " . $urnParser->getSegmentNum() . PHP_EOL;
 *     echo "Offset: ";
 *     echo var_export($urnParser->getOffset(), true).PHP_EOL;
 *     echo "Text Fragment: " . $urnParser->getTextFragment() . PHP_EOL;
 *     echo PHP_EOL.PHP_EOL;
 *   }  
 *     
 */

class IsbnUrn
{   
    
    /**
     * @var string $urn The URN string
     * @var string $namespaceIdentifier The namespace identifier of the URN
     * @var string $namespace The namespace of the URN
     * @var array $offset The offset of the URN
     * @var string $tocItem The TOC item of the URN
     * @var int $segmentNum The segment number of the URN
     * @var string $textFragment The text fragment of the URN
     * 
     */


    private $urn;
    private $namespaceIdentifier;
    private $namespace;
    private $offset = [];
    private $tocItem;
    private $segmentNum;
    private $textFragment;


    /**
     * IsbnUrn constructor.
     *
     * @param string|false $urn The URN string
     */
    public function __construct($urn = false) {
        if($urn) {
            $this->setUrn($urn);
        }
    }


    /**
     * Parses the URN string.
     */
    protected function parseUrn() {
        // Get the namespace identifier and namespace
        $this->parseIdentifier();
    
        // Get the tocitem or segmentnum
        $this->parseTocOrSegment();

        // Get the offset and text fragment
        $this->parseUrnFragment();
    }


    /**
     * Sets the URN string.
     *
     * @param string $urn The URN string
     */

    public function setUrn($urn) {
        $this->urn = $urn;
        $this->parseUrn();
    }


    /**
     * Gets the URN string.
     *
     * @return string The URN string
     */
    public function getUrn() {
        return $this->createUrn();
        //return !empty($this->urn) ? $this->urn : $this->createUrn();
    }
    

    /**
     * Sets the namespace identifier.
     *
     * @param string $namespaceIdentifier The namespace identifier
     */
    public function setNamespaceIdentifier($namespaceIdentifier = 'isbn') {
        $this->namespaceIdentifier = $namespaceIdentifier;
    }


    /**
     * Gets the namespace identifier.
     *
     * @return string|false The namespace identifier or false if not set
     */
    public function getNamespaceIdentifier() {
        return $this->namespaceIdentifier ?? false;
    }


    /**
     * Sets the namespace.
     *
     * @param string $namespace The namespace
     */
    public function setNamespace($namespace) {
        $this->namespace = $namespace;
    }


    /**
     * Gets the namespace.
     *
     * @return string|false The namespace or false if not set
     */
    public function getNamespace() {
        return $this->namespace ?? false;
    }


    /**
     * Sets the TOC item.
     *
     * @param string $tocItem The TOC item
     */
    public function setTocItem($tocItem) {
        $this->tocItem = $tocItem;
    }

    
    /**
     * Gets the TOC item.
     *
     * @return string|false The TOC item or false if not set
     */
    public function getTocItem() {
        return $this->tocItem ?? false;
    }


    /**
     * Sets the segment number.
     *
     * @param int $segmentNum The segment number
     */
    public function setSegmentNum($segmentNum) {
        $this->segmentNum = $segmentNum;
    }


    /**
     * Gets the segment number.
     *
     * @return int|false The segment number or false if not set
     */
    public function getSegmentNum() {
        return $this->segmentNum ?? false;
    }


    /**
     * Sets the offset.
     *
     * @param int $start The start of the offset
     * @param int|null $length The length of the offset
     */
    public function setOffset($start, $length = null) {
        $this->offset['start'] = $start;
        if($length) {
            $this->offset['length'] = $length;
        }
    }


    /**
     * Gets the offset.
     *
     * @return array|false The offset or false if not set
     */
    public function getOffset() {
        return $this->offset ?? false;
    }


    /**
     * Sets the text fragment.
     *
     * @param string $textFragment The text fragment
     */
    public function setTextFragment($textFragment) {
        $this->textFragment = $textFragment;
    }


    /**
     * Gets the text fragment.
     *
     * @return string|false The text fragment or false if not set
     */
    public function getTextFragment() {
        return $this->textFragment ?? false;
    }


    /**
     * Parses the identifier from the URN string.
     */
    protected function parseIdentifier() {
        if (preg_match('/urn:([^:]+):([^?#]+)/', $this->urn, $matches)) {
            $this->namespaceIdentifier = $matches[1];
            $this->namespace = $matches[2];
        }
    }


    /**
     * Parses the URN fragment from the URN string.
     */
    protected function parseUrnFragment() {
        if (preg_match('/#offset\((\d+)(?:,(\d+))?\)([a-zA-Z0-9+]+)?/', $this->urn, $matches)) {
            $this->offset = !(empty($matches[2])) ? [
                'start' => $matches[1],
                'length' => $matches[2] ?? null // Length is optional
            ] : [
                'start' => $matches[1]
            ];
            $this->textFragment = isset($matches[3]) ? urldecode($matches[3]) : false;
        }
    }


    /**
     * Gets the TOC item or segment number from the URN string.
     */
    protected function parseTocOrSegment() {
        // Get the tocitem or segmentnum
        if (preg_match('/tocitem=([0-9\.]+)/', $this->urn, $matches)) {
            $this->tocItem = $matches[1];
        } elseif (preg_match('/segmentnum=([0-9]+)/', $this->urn, $matches)) {
            $this->segmentNum = $matches[1];
        }
    }


    /**
     * Creates a URN string.
     *
     * @return string The created URN string
     */
    protected function createUrn() {
        $urnParts = ["urn", ":"];
        
        if ($this->namespaceIdentifier && $this->namespace) {
            $urnParts[] = "{$this->namespaceIdentifier}:{$this->namespace}";
        }

        $queryParts = [];
        if ($this->tocItem) {
            $queryParts[] = "tocitem={$this->tocItem}";
        } elseif ($this->segmentNum) {
            $queryParts[] = "segmentnum={$this->segmentNum}";
        }

        if (!empty($queryParts)) {
            $urnParts[] = '?' . implode('&', $queryParts);
        }

        if ($this->offset) {
            $offsetPart = "#offset({$this->offset['start']}";
            if (isset($this->offset['length'])) {
                $offsetPart .= ",{$this->offset['length']}";
            }
            $offsetPart .= ")";
            
            if ($this->textFragment) {
                $offsetPart .= urlencode($this->textFragment);
            }
            
            $urnParts[] = $offsetPart;
        }

        return implode('', $urnParts);
    }
}
