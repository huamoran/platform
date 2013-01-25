<?php
namespace Oro\Bundle\FlexibleEntityBundle\Model;

/**
 * Abstract attribute type
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
abstract class AbstractAttributeType
{

    /**
     * Available backend storage
     * @var string
     */
    const BACKEND_STORAGE_ATTRIBUTE_VALUE = 'values';
    const BACKEND_STORAGE_FLAT_VALUE      = 'flatValues';

    /**
     * Available backend types
     * @var string
     */
    const BACKEND_TYPE_DATE     = 'date';
    const BACKEND_TYPE_DATETIME = 'datetime';
    const BACKEND_TYPE_DECIMAL  = 'decimal';
    const BACKEND_TYPE_INTEGER  = 'integer';
    const BACKEND_TYPE_OPTION   = 'options';
    const BACKEND_TYPE_TEXT     = 'text';
    const BACKEND_TYPE_VARCHAR  = 'varchar';

    /**
     * Available frontend types
     * @var string
     */
    const FRONTEND_TYPE_TEXTFIELD = 'text';
    const FRONTEND_TYPE_TEXTAREA  = 'textarea';
    const FRONTEND_TYPE_PRICE     = 'money';
    const FRONTEND_TYPE_DATE      = 'date';
    const FRONTEND_TYPE_DATETIME  = 'datetime';
    const FRONTEND_TYPE_LIST      = 'choice';

}
