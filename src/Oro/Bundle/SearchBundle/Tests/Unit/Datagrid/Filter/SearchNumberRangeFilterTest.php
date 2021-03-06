<?php

namespace Oro\Bundle\SearchBundle\Tests\Unit\Datagrid\Filter;

use Oro\Bundle\FilterBundle\Datasource\FilterDatasourceAdapterInterface;
use Oro\Bundle\FilterBundle\Filter\FilterUtility;
use Oro\Bundle\FilterBundle\Form\Type\Filter\NumberRangeFilterType;
use Oro\Bundle\SearchBundle\Datagrid\Filter\Adapter\SearchFilterDatasourceAdapter;
use Oro\Bundle\SearchBundle\Datagrid\Filter\SearchNumberRangeFilter;
use Oro\Bundle\SearchBundle\Query\Criteria\Comparison;
use Doctrine\Common\Collections\Expr\Comparison as BaseComparison;
use Symfony\Component\Form\FormFactoryInterface;

class SearchNumberRangeFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SearchNumberRangeFilter
     */
    private $filter;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        /* @var $formFactory FormFactoryInterface|\PHPUnit_Framework_MockObject_MockObject */
        $formFactory = $this->getMock(FormFactoryInterface::class);
        /* @var $filterUtility FilterUtility|\PHPUnit_Framework_MockObject_MockObject */
        $filterUtility = $this->getMock(FilterUtility::class);

        $this->filter = new SearchNumberRangeFilter($formFactory, $filterUtility);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Invalid filter datasource adapter provided
     */
    public function testThrowsExceptionForWrongFilterDatasourceAdapter()
    {
        $ds = $this->getMock(FilterDatasourceAdapterInterface::class);
        $this->filter->apply(
            $ds,
            [
                'type' => NumberRangeFilterType::TYPE_BETWEEN,
                'value' => 123,
                'value_end' => 155,
            ]
        );
    }

    public function testApplyBetween()
    {
        $fieldName = 'field';

        $ds = $this->getMockBuilder(SearchFilterDatasourceAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $ds->expects($this->exactly(2))
            ->method('addRestriction')
            ->withConsecutive(
                [new BaseComparison("decimal.".$fieldName, Comparison::GTE, 123), FilterUtility::CONDITION_AND, false],
                [new BaseComparison("decimal.".$fieldName, Comparison::LTE, 155), FilterUtility::CONDITION_AND, false]
            );

        $this->filter->init('test', [FilterUtility::DATA_NAME_KEY => $fieldName]);
        $this->assertTrue(
            $this->filter->apply(
                $ds,
                [
                    'type' => NumberRangeFilterType::TYPE_BETWEEN,
                    'value' => 123,
                    'value_end' => 155,
                ]
            )
        );
    }

    public function testApplyNotBetween()
    {
        $fieldName = 'field';

        $ds = $this->getMockBuilder(SearchFilterDatasourceAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $ds->expects($this->exactly(2))
            ->method('addRestriction')
            ->withConsecutive(
                [new BaseComparison("decimal.".$fieldName, Comparison::LTE, 123), FilterUtility::CONDITION_AND, false],
                [new BaseComparison("decimal.".$fieldName, Comparison::GTE, 155), FilterUtility::CONDITION_AND, false]
            );

        $this->filter->init('test', [FilterUtility::DATA_NAME_KEY => $fieldName]);
        $this->assertTrue(
            $this->filter->apply(
                $ds,
                [
                    'type' => NumberRangeFilterType::TYPE_NOT_BETWEEN,
                    'value' => 123,
                    'value_end' => 155,
                ]
            )
        );
    }
}
