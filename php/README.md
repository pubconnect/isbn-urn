# Deeplinks to publications within urn: ISBN
An experiment/draft to add readable deeplinks and fragments using the urn format. The aim is to support both 'digital' and 'physical' usecases of (book) publications. 

The provided basic libraries and documentation allows to test, experience, adjust and extend its usability within various (development) environments and applications.

Please and post issues and pull requests in the [base project](https://github.com/pubconnect/isbn-urn/). Also see the extended [README](https://github.com/pubconnect/isbn-urn/) located there

## Installing

`composer require pubconnect/isbn-urn`

## Usage 
 
### CREATE :: a URN string
```php
   use Pubconnect\IsbnUrn\IsbnUrn; 
   
   $urnParser = new IsbnUrn();
   $urnParser->setNamespaceIdentifier('isbn');
   $urnParser->setNamespace('9795363916662');
   $urnParser->setTocItem('3.3.3');
   $urnParser->setOffset(10, 34);
   $urnParser->setTextFragment('de lelijke vos sprong in de bosjes');
   echo $urnParser->getUrn();
```

### UPDATE :: a existing URN string
```php
   use Pubconnect\IsbnUrn\IsbnUrn; 
   
   $urnString = "urn:isbn:9795363916662";
   $urnParser = new IsbnUrn($urnString);
   $urnParser->setTocItem('4.3.2');
   $urnParser->setOffset(0, 340);
   $urnParser->setTextFragment('de lelijke vos sprong in de bosjes');
   echo $urnParser->getUrn();
   echo PHP_EOL.PHP_EOL;
```  

### PARSE :: URN strings
```php
   use Pubconnect\IsbnUrn\IsbnUrn; 

   $urnStrings[] = "urn:isbn:9795363916662";
   $urnStrings[] = "urn:isbn:9795363916662?segmentnum=5";
   $urnStrings[] = "urn:isbn:9795363916662?tocitem=3.3.3";
   $urnStrings[] = "urn:isbn:9795363916662?tocitem=3.3.3#offset(150)";
   $urnStrings[] = "urn:isbn:9795363916662?tocitem=3.3.3#offset(10,34)";
   $urnStrings[] = "urn:isbn:9795363916662?tocitem=3.3.3#offset(10,34)de+lelijke+vos+sprong+in+de+bosjes";
    
   foreach($urnStrings as $urnString){
     $urnParser = new IsbnUrn($urnString);
     echo "URN: " . $urnParser->getUrn() . PHP_EOL;
     echo "Namespace Identifier: " . $urnParser->getNamespaceIdentifier() . PHP_EOL;
     echo "Namespace: " . $urnParser->getNamespace() . PHP_EOL;
     echo "TOC Item: " . $urnParser->getTocItem() . PHP_EOL;
     echo "Segment Number: " . $urnParser->getSegmentNum() . PHP_EOL;
     echo "Offset: ";
     echo var_export($urnParser->getOffset(), true).PHP_EOL;
     echo "Text Fragment: " . $urnParser->getTextFragment() . PHP_EOL;
     echo PHP_EOL.PHP_EOL;
   }  
     
```
