<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2013-2014 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 * @license GPLv2
 *
 */

namespace qtism\runtime\expressions\operators;

use qtism\common\datatypes\Boolean;
use qtism\data\expressions\operators\IsNull;
use qtism\data\expressions\Expression;
use \InvalidArgumentException;

/**
 * The IsNullProcessor class aims at processing IsNull QTI Data Model Expression objects.
 *
 * From IMS QTI:
 *
 * The isNull operator takes a sub-expression with any base-type and cardinality.
 * The result is a single boolean with a value of true if the sub-expression is NULL
 * and false otherwise. Note that empty containers and empty strings are both
 * treated as NULL.
 *
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 *
 */
class IsNullProcessor extends OperatorProcessor
{
    /**
	 * Process the IsNullExpression object from the QTI Data Model.
	 *
	 * @return boolean Whether the sub-expression is considered to be NULL.
	 * @throws \qtism\runtime\expressions\operators\OperatorProcessingException If something goes wrong.
	 */
    public function process()
    {
        $operands = $this->getOperands();
        $expression = $this->getExpression();

        return new Boolean($operands->containsNull());
    }
    
    /**
     * @see \qtism\runtime\expressions\ExpressionProcessor::getExpressionType()
     */
    protected function getExpressionType()
    {
        return 'qtism\\data\\expressions\\operators\\IsNull';
    }
}
