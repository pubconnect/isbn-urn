# Deeplinks to publications within urn: ISBN
An experiment/draft to add readable deeplinks and fragments using the urn format. The aim is to support both 'digital' and 'physical' usecases of (book) publications. 

The provided basic libraries and documentation allows to test, experience, adjust and extend its usability within various (development) environments and applications.

We attempt to maintain a readable format in snippets and internal references that can be translated to both online and offline books based on URN (Uniform Resource Name) formatting ([RFC 8141](https://en.wikipedia.org/wiki/Uniform_Resource_Name)).
The namespace `isbn` is already [registered](https://www.iana.org/assignments/urn-namespaces/urn-namespaces.xhtml) at [INA](https://www.iana.org/assignments/urn-namespaces/urn-namespaces.xhtml).

![URN_syntax_diagram_-_namestring.png](https://upload.wikimedia.org/wikipedia/commons/c/ce/URN_syntax_diagram_-_namestring.png)*Image source: Wikipedia (CC BY-SA 4.0) URN syntax diagram - namestring.png*

_The structure of the described namespace for_ `isbn` _is:_ `urn:isbn:[ ISBN-10 | ISBN-13 ]`  
_E.g._ `urn:isbn:9795363916662` `[urn]:[nid]:[nss]`

According to the [isbn namespace spec](https://www.iana.org/assignments/urn-formal/isbn), the namespace for isbn allows different writing styles of the ISBN number. Both ISBN-10 and ISBN-13 are [permitted](https://www.iana.org/assignments/urn-formal/isbn) either with or without `'-'`. That means `urn:isbn:9795363916662` and `urn:isbn:979-5-363916-66-2` are both valid.

## Proposal Brief

Below is our proposal/implementation of how URN formatting will be based on our interpretation using (?=)[q-component] and (#)[fragment] within the spec.

**_Reference to publication with ISBN number_**

`urn:isbn:9795363916662`

**_Reference to chapter_**

_Numerical toc item (if present)_  
`urn:isbn:9795363916662?tocitem=3.3.3`

_Chapter number (if no numerical toc present)_  
`urn:isbn:9795363916662?segmentnum=5`

**_Reference to text section_**

`urn:isbn:9795363916662?tocitem=3.3.3#offset(10)`  
`urn:isbn:9795363916662?tocitem=3.3.3#offset(10,34)`

**_Reference to readable fragment_**  
`urn:isbn:9795363916662?tocitem=3.3.3#offset(10,34)the+ugly+fox+jumped+into+the+bushes...`

### Structure

#### Query and Fragment

The formatting of the urn according to [RFC 8141](https://en.wikipedia.org/wiki/Uniform_Resource_Name) allows for ?query and #fragment parameters to be included. At the time of writing, we have not found any existing formatting/query for urn, specifically urn:isbn, that enables referencing specific sections or parts within an isbn resource.

#### Structure of Query ([q-component])

##### Chapter Reference

> :warning: _Sadly, it cannot be stated unequivocally that chapters have unique names, thus a query based on chapter name is not feasible._  
> E.g. `urn:isbn:9795363916662?chapter=bushes+and+foxes` is unfortunately not (well) possible.

##### Chapter based on TOC (numerical)

_To refer to a chapter, we add a ?query= parameter that refers to the numerical level/value within the Table of Contents (TOC)._  
_Assuming the chapter to be referred to is called '3.3.3 Bushes and Foxes,' the numerical value 3.3.3 results in the urn._  
`urn:isbn:9795363916662?tocitem=3.3.3`  
`[urn]:[nid]:[nss](q-component[?=])`

##### Chapter based on Title

_If the chapters do not have a numerical value, a sequential number will be used (regardless of any jumps/levels)._  
For selecting the chapter 'Bushes and Foxes,' the resulting urn would be `urn:isbn:9795363916662?segmentnum=5` as this is the 5th chapter.

* Introduction
  * Proposal Round
* Habitat
  * Sheep in the Meadow
  * Bushes and Foxes

#### Structure of Fragment ([fragment])

_For referencing a text fragment, we also use the 'fragment' described in the spec. Except that 'fragment' is preceded by a '#', there is no further definition/suggestion for naming in the found documentation._

`[urn]:[nid]:[nss](q-component[?=])(fragment[#])`

##### Basis

_We define the fragment with an offset from the selected toc= item in the number of characters._  
`urn:isbn:9795363916662?tocitem=3.3.3#offset([start position], [length])`

E.g. `urn:isbn:9795363916662?tocitem=3.3.3#offset(10,150)` indicating that this urn refers to chapter `3.3.3` from character `10` up to `150` characters from the starting point (`10`)

##### Basis + Snippet

For readability (fragment identifier component), it is advisable to place a piece of text behind the `offset()` e.g.  
`urn:isbn:9795363916662?tocitem=3.3.3#offset(10,34)the ugly fox jumped into the bushes`

To indicate that the fragment is longer than shown in the urn, `...` will be placed behind the text e.g.  
`urn:isbn:9795363916662?tocitem=3.3.3#offset(10,34)the ugly fox jumped into d...`

> :memo: **Note:**  
> The use of '+' instead of '%20' to indicate a space might not be striclty valid.  
> At the moment, we are setting that aside because the use of a '+' supports readability and is also URL encoding compatible/exchangeable with a space. Note that a minus '-' sign is compatible.  
> Moreover, the spec allows deviation from URI encoding if it increases readability see ([RFC 3986 Section 4](https://datatracker.ietf.org/doc/html/rfc3986#section-4))
