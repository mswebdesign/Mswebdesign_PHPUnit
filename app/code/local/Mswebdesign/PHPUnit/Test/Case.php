<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Mswebdesign
 * @package    Mswebdesign_PHPUnit
 * @copyright  Copyright (c) 2012 münster-webdesign.net (http://www.muenster-webdesign.net)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Christian Grugel <cgrugel@muenster-webdesign.net>
 */
class Mswebdesign_PHPUnit_Test_Case extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Retrieves a accessible mock object for the specified model class alias.
     *
     * @param  string  $classAlias
     * @param  array   $methods
     * @param  array   $constructorArguments
     * @param  string  $mockClassAlias
     * @param  boolean $callOriginalConstructor
     * @param  boolean $callOriginalClone
     * @param  boolean $callAutoload
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function getAccessibleModelMock($classAlias, $methods = array(),
        array $constructorArguments = array(),
        $mockClassAlias = '',  $callOriginalConstructor = true,
        $callOriginalClone = true, $callAutoload = true)
    {
        $className = $this->getGroupedClassName('model', $classAlias);
        return $this->getAccessibleMock($className, $methods, $constructorArguments, $mockClassAlias, $callOriginalConstructor, $callOriginalClone, $callAutoload);
    }

    /**
     * @param $classAlias
     * @return PHPUnit_Framework_MockObject_MockBuilder
     */
    public function getAccessibleModelMockBuilder($classAlias)
    {
        $className = $this->getGroupedClassName('model', $classAlias);

        return $this->getMockBuilder($this->buildAccessibleProxy($className));
    }

    /**
     * @param $className
     * @return PHPUnit_Framework_MockObject_MockBuilder
     */
    public function getAccessibleMockBuilder($className)
    {
        return $this->getMockBuilder($this->buildAccessibleProxy($className));
    }

    /**
     * @param $classAlias
     * @return PHPUnit_Framework_MockObject_MockBuilder
     */
    public function getAccessibleResourceModelMockBuilder($classAlias)
    {
        $className = $this->getGroupedClassName('resource_model', $classAlias);

        return $this->getMockBuilder($this->buildAccessibleProxy($className));
    }

    public function getAccessibleBlockMockBuilder($classAlias)
    {
        $className = $this->getGroupedClassName('block', $classAlias);

        return $this->getMockBuilder($this->buildAccessibleProxy($className));
    }

    /**
     * @param $classAlias
     * @param array $methods
     * @param array $constructorArguments
     * @param string $mockClassAlias
     * @param bool $callOriginalConstructor
     * @param bool $callOriginalClone
     * @param bool $callAutoload
     * @return object
     */
    public function getAccessibleResourceModelMock($classAlias, $methods = array(),
        array $constructorArguments = array(),
        $mockClassAlias = '',  $callOriginalConstructor = true,
        $callOriginalClone = true, $callAutoload = true)
    {
        $className = $this->getGroupedClassName('resource_model', $classAlias);
        return $this->getAccessibleMock($className, $methods, $constructorArguments, $mockClassAlias, $callOriginalConstructor, $callOriginalClone, $callAutoload);
    }

    /********************* accessible mock code *****************/
    /**
     * Returns a mock object which allows for calling protected methods and access
     * of protected properties.
     *
     * @param string $className Full qualified name of the original class
     * @param array $methods
     * @param array $arguments
     * @param string $mockClassName
     * @param boolean $callOriginalConstructor
     * @param boolean $callOriginalClone
     * @param boolean $callAutoload
     * @return object
     * @author Robert Lemke <robert@typo3.org>
     * @api
     */
    protected function getAccessibleMock($originalClassName, $methods = array(), array $arguments = array(), $mockClassName = '', $callOriginalConstructor = TRUE, $callOriginalClone = TRUE, $callAutoload = TRUE) {
        return $this->getMock($this->buildAccessibleProxy($originalClassName), $methods, $arguments, $mockClassName, $callOriginalConstructor, $callOriginalClone, $callAutoload);
    }


    /**
     * Creates a proxy class of the specified class which allows
     * for calling even protected methods and access of protected properties.
     *
     * @param protected $className Full qualified name of the original class
     * @return string Full qualified name of the built class
     */
    protected function buildAccessibleProxy($className) {
        $accessibleClassName = uniqid('AccessibleTestProxy');
        $class = new ReflectionClass($className);
        $abstractModifier = $class->isAbstract() ? 'abstract ' : '';
        eval('
			' . $abstractModifier . 'class ' . $accessibleClassName . ' extends ' . $className . ' {
				public function _call($methodName) {
					$args = func_get_args();
					return call_user_func_array(array($this, $methodName), array_slice($args, 1));
				}
				public function _callRef($methodName, &$arg1 = NULL, &$arg2 = NULL, &$arg3 = NULL, &$arg4 = NULL, &$arg5= NULL, &$arg6 = NULL, &$arg7 = NULL, &$arg8 = NULL, &$arg9 = NULL) {
					switch (func_num_args()) {
						case 0 : return $this->$methodName();
						case 1 : return $this->$methodName($arg1);
						case 2 : return $this->$methodName($arg1, $arg2);
						case 3 : return $this->$methodName($arg1, $arg2, $arg3);
						case 4 : return $this->$methodName($arg1, $arg2, $arg3, $arg4);
						case 5 : return $this->$methodName($arg1, $arg2, $arg3, $arg4, $arg5);
						case 6 : return $this->$methodName($arg1, $arg2, $arg3, $arg4, $arg5, $arg6);
						case 7 : return $this->$methodName($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7);
						case 8 : return $this->$methodName($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8);
						case 9 : return $this->$methodName($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9);
					}
				}
				public function _set($propertyName, $value) {
					$this->$propertyName = $value;
				}
				public function _setRef($propertyName, &$value) {
					$this->$propertyName = $value;
				}
				public function _get($propertyName) {
					return $this->$propertyName;
				}
			}
		');
        return $accessibleClassName;
    }
}