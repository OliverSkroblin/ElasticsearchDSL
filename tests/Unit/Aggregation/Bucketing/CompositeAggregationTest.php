<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Tests\Unit\Bucketing\Aggregation;

use ONGR\ElasticsearchDSL\Aggregation\Bucketing\CompositeAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\TermsAggregation;

class CompositeAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test for composite aggregation toArray() method exception.
     */
    public function testToArray()
    {
        $compositeAgg = new CompositeAggregation('composite_test_agg');
        $termsAgg = new TermsAggregation('test_term_agg', 'test_field');
        $compositeAgg->addSource($termsAgg);

        $expectedResult = [
            'composite' => [
                'sources' =>  [
                    [
                        'test_term_agg' => [ 'terms' => ['field' => 'test_field'] ],
                    ]
                ]
            ],
        ];

        $this->assertEquals($expectedResult, $compositeAgg->toArray());
    }

    /**
     * Tests getType method.
     */
    public function testGetType()
    {
        $aggregation = new CompositeAggregation('foo');
        $result = $aggregation->getType();
        $this->assertEquals('composite', $result);
    }

    public function testTermsSourceWithOrderParameter()
    {
        $compositeAgg = new CompositeAggregation('composite_with_order');
        $termsAgg = new TermsAggregation('test_term_agg', 'test_field');
        $termsAgg->addParameter('order', 'asc');
        $compositeAgg->addSource($termsAgg);

        $expectedResult = [
            'composite' => [
                'sources' =>  [
                    [
                        'test_term_agg' => [ 'terms' => ['field' => 'test_field', 'order' => 'asc'] ],
                    ]
                ]
            ],
        ];

        $this->assertEquals($expectedResult, $compositeAgg->toArray());
    }


    public function testTermsSourceWithDescOrderParameter()
    {
        $compositeAgg = new CompositeAggregation('composite_with_order');
        $termsAgg = new TermsAggregation('test_term_agg', 'test_field');
        $termsAgg->addParameter('order', 'desc');
        $compositeAgg->addSource($termsAgg);

        $expectedResult = [
            'composite' => [
                'sources' =>  [
                    [
                        'test_term_agg' => [ 'terms' => ['field' => 'test_field', 'order' => 'desc'] ],
                    ]
                ]
            ],
        ];

        $this->assertEquals($expectedResult, $compositeAgg->toArray());
    }


    public function testMultipleSourcesWithDifferentOrders()
    {
        $compositeAgg = new CompositeAggregation('composite_with_order');

        $termsAgg = new TermsAggregation('test_term_agg_1', 'test_field');
        $termsAgg->addParameter('order', 'desc');
        $compositeAgg->addSource($termsAgg);

        $termsAgg = new TermsAggregation('test_term_agg_2', 'test_field');
        $termsAgg->addParameter('order', 'asc');
        $compositeAgg->addSource($termsAgg);

        $expectedResult = [
            'composite' => [
                'sources' =>  [
                    [
                        'test_term_agg_1' => [ 'terms' => ['field' => 'test_field', 'order' => 'desc'] ],
                    ],
                    [
                        'test_term_agg_2' => [ 'terms' => ['field' => 'test_field', 'order' => 'asc'] ],
                    ]
                ]
            ],
        ];

        $this->assertEquals($expectedResult, $compositeAgg->toArray());
    }


}
