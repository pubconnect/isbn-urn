class IsbnUrn {
    constructor(urn = null) {
        this.urn = urn;
        this.namespaceIdentifier = null;
        this.namespace = null;
        this.offset = {};
        this.tocItem = null;
        this.segmentNum = null;
        this.textFragment = null;

        if (urn) {
            this.setUrn(urn);
        }
    }

    setUrn(urn) {
        this.urn = urn;
        this.parseUrn();
    }

    getUrn() {
        return this.createUrn();
    }

    setNamespaceIdentifier(namespaceIdentifier = 'isbn') {
        this.namespaceIdentifier = namespaceIdentifier;
    }

    getNamespaceIdentifier() {
        return this.namespaceIdentifier;
    }

    setNamespace(namespace) {
        this.namespace = namespace;
    }

    getNamespace() {
        return this.namespace;
    }

    setTocItem(tocItem) {
        this.tocItem = tocItem;
    }

    getTocItem() {
        return this.tocItem;
    }

    setSegmentNum(segmentNum) {
        this.segmentNum = segmentNum;
    }

    getSegmentNum() {
        return this.segmentNum;
    }

    setOffset(start, length = null) {
        this.offset.start = start;
        if (length) {
            this.offset.length = length;
        }
    }

    getOffset() {
        return this.offset;
    }

    setTextFragment(textFragment) {
        this.textFragment = textFragment;
    }

    getTextFragment() {
        return this.textFragment;
    }

    parseUrn() {
        this.parseIdentifier();
        this.parseTocOrSegment();
        this.parseUrnFragment();
    }

    parseIdentifier() {
        const matches = this.urn.match(/urn:([^:]+):([^?#]+)/);
        if (matches) {
            this.namespaceIdentifier = matches[1];
            this.namespace = matches[2];
        }
    }

    parseUrnFragment() {
        const matches = this.urn.match(/#offset\((\d+)(?:,(\d+))?\)([a-zA-Z0-9+]+)?/);
        if (matches) {
            this.offset = {
                start: matches[1],
                length: matches[2] || null
            };
            this.textFragment = matches[3] ? decodeURIComponent(matches[3]) : null;
        }
    }

    parseTocOrSegment() {
        let matches = this.urn.match(/tocitem=([0-9\.]+)/);
        if (matches) {
            this.tocItem = matches[1];
        } else {
            matches = this.urn.match(/segmentnum=([0-9]+)/);
            if (matches) {
                this.segmentNum = matches[1];
            }
        }
    }

    createUrn() {
        const urnParts = ["urn", ":"];
        
        if (this.namespaceIdentifier && this.namespace) {
            urnParts.push(`${this.namespaceIdentifier}:${this.namespace}`);
        }

        const queryParts = [];
        if (this.tocItem) {
            queryParts.push(`tocitem=${this.tocItem}`);
        } else if (this.segmentNum) {
            queryParts.push(`segmentnum=${this.segmentNum}`);
        }

        if (queryParts.length > 0) {
            urnParts.push('?' + queryParts.join('&'));
        }

        if (this.offset.start) {
            let offsetPart = `#offset(${this.offset.start}`;
            if (this.offset.length) {
                offsetPart += `,${this.offset.length}`;
            }
            offsetPart += ")";
            
            if (this.textFragment) {
                offsetPart += encodeURIComponent(this.textFragment);
            }
            
            urnParts.push(offsetPart);
        }

        return urnParts.join('');
    }
}

module.exports = IsbnUrn;
