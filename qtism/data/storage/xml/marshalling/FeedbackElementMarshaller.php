<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 * Copyright (c) 2013 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Jérôme Bogaerts, <jerome@taotesting.com>
 * @license GPLv2
 * @package
 */


namespace qtism\data\storage\xml\marshalling;

use qtism\data\content\BlockCollection;
use qtism\data\content\InlineCollection;
use qtism\data\ShowHide;
use qtism\data\QtiComponentCollection;
use qtism\data\QtiComponent;
use \DOMElement;
use \InvalidArgumentException;

/**
 * The Marshaller implementation for FeedbackInline/FeedbackBlock elements of the content model.
 * 
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 *
 */
class FeedbackElementMarshaller extends ContentMarshaller {
    
    protected function unmarshallChildrenKnown(DOMElement $element, QtiComponentCollection $children) {
        
        $fqClass = $this->lookupClass($element);
        
        if (($outcomeIdentifier = self::getDOMElementAttributeAs($element, 'outcomeIdentifier')) !== null) {

            if (($identifier = self::getDOMElementAttributeAs($element, 'identifier')) !== null) {
                
                $component = new $fqClass($outcomeIdentifier, $identifier);
                
                if (($showHide = self::getDOMElementAttributeAs($element, 'showHide')) !== null) {
                    
                    try {
                        $component->setShowHide(ShowHide::getConstantByName($showHide));
                    }
                    catch (InvalidArgumentException $e) {
                        $msg = "'${showHide}' is not a valid value for the 'showHide' attribute of element '" . $element->nodeName . "'.";
                        throw new UnmarshallingException($msg, $element, $e);
                    }
                    
                    try {
                        $content = ($element->nodeName === 'feedbackInline') ? new InlineCollection($children->getArrayCopy()) : new BlockCollection($children->getArrayCopy());
                        $component->setContent($content);
                    }
                    catch (InvalidArgumentException $e) {
                        $mustContain = ($element->nodeName === 'feedbackInline') ? 'inline' : 'block';
                        $msg = "The content of the 'feedback' is invalid. It must only contain '${mustContain}' elements.";
                        throw new UnmarshallingException($msg, $element, $e);
                    }
                    
                    self::fillBodyElement($component, $element);
                    return $component;
                }
            }
            else {
                $msg = "The mandatory 'identifier' attribute is missing from element '" . $element->nodeName . "'.";
                throw new UnmarshallingException($msg, $element);
            }
        }
        else {
            $msg = "The mandatory 'outcomeIdentifier' attribute is missing from element '" . $element->nodeName . "'.";
            throw new UnmarshallingException($msg, $element);
        }
        
        
    }
    
    protected function marshallChildrenKnown(QtiComponent $component, array $elements) {
        
        $element = self::getDOMCradle()->createElement($component->getQtiClassName());
        self::fillElement($element, $component);
        self::setDOMElementAttribute($element, 'outcomeIdentifier', $component->getOutcomeIdentifier());
        self::setDOMElementAttribute($element, 'identifier', $component->getIdentifier());
        self::setDOMElementAttribute($element, 'showHide', ShowHide::getNameByConstant($component->getShowHide()));
        
        foreach ($elements as $e) {
            $element->appendChild($e);
        }
        
        return $element;
    }
    
    protected function setLookupClasses() {
        $this->lookupClasses = array("qtism\\data\\content");
    }
}