<?php
/**
 * This file is part of PHP_Depend.
 *
 * PHP Version 5
 *
 * Copyright (c) 2008-2010, Manuel Pichler <mapi@pdepend.org>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Manuel Pichler nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   QualityAssurance
 * @package    PHP_Depend
 * @subpackage Metrics
 * @author     Manuel Pichler <mapi@pdepend.org>
 * @copyright  2008-2010 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id$
 * @link       http://pdepend.org/
 */

require_once dirname(__FILE__) . '/../AbstractTest.php';

require_once 'PHP/Depend/Code/Class.php';
require_once 'PHP/Depend/Code/File.php';
require_once 'PHP/Depend/Code/Interface.php';
require_once 'PHP/Depend/Code/NodeIterator.php';
require_once 'PHP/Depend/Code/Package.php';
require_once 'PHP/Depend/Code/Filter/Package.php';
require_once 'PHP/Depend/Code/Filter/Collection.php';
require_once 'PHP/Depend/Metrics/Inheritance/Analyzer.php';

/**
 * Test case for the inheritance analyzer.
 *
 * @category   QualityAssurance
 * @package    PHP_Depend
 * @subpackage Metrics
 * @author     Manuel Pichler <mapi@pdepend.org>
 * @copyright  2008-2010 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://pdepend.org/
 */
class PHP_Depend_Metrics_Inheritance_AnalyzerTest extends PHP_Depend_Metrics_AbstractTest
{
    /**
     * Tests that the analyzer calculates the correct average number of derived
     * classes.
     *
     * @return void
     * @covers PHP_Depend_Metrics_Inheritance_Analyzer
     * @group pdepend
     * @group pdepend::metrics
     * @group pdepend::metrics::inheritance
     * @group unittest
     */
    public function testAnalyzerCalculatesCorrectANDCValue()
    {
        $filter = PHP_Depend_Code_Filter_Collection::getInstance();
        $filter->addFilter(new PHP_Depend_Code_Filter_Package(array('library')));

        $packages = self::parseTestCaseSource(__METHOD__);
        $analyzer = new PHP_Depend_Metrics_Inheritance_Analyzer();
        $analyzer->analyze($packages);

        $project = $analyzer->getProjectMetrics();

        $this->assertArrayHasKey('andc', $project);
        $this->assertEquals(0.7368, $project['andc'], null, 0.0001);
    }

    /**
     * Tests that the analyzer calculates the correct average hierarchy height.
     *
     * @return void
     * @covers PHP_Depend_Metrics_Inheritance_Analyzer
     * @group pdepend
     * @group pdepend::metrics
     * @group pdepend::metrics::inheritance
     * @group unittest
     */
    public function testAnalyzerCalculatesCorrectAHHValue()
    {
        $filter = PHP_Depend_Code_Filter_Collection::getInstance();
        $filter->addFilter(new PHP_Depend_Code_Filter_Package(array('library')));

        $packages = self::parseTestCaseSource(__METHOD__);
        $analyzer = new PHP_Depend_Metrics_Inheritance_Analyzer();
        $analyzer->analyze($packages);

        $project = $analyzer->getProjectMetrics();

        $this->assertArrayHasKey('ahh', $project);
        $this->assertEquals(1, $project['ahh']);
    }

    /**
     * Tests that the analyzer calculates the correct DIT values.
     *
     * @return void
     * @covers PHP_Depend_Metrics_Inheritance_Analyzer
     * @group pdepend
     * @group pdepend::metrics
     * @group pdepend::metrics::inheritance
     * @group unittest
     */
    public function testCalculateDITMetricNoInheritance()
    {
        $packages = self::parseTestCaseSource(__METHOD__);
        $package  = $packages->current();

        $analyzer = new PHP_Depend_Metrics_Inheritance_Analyzer();
        $analyzer->analyze($packages);

        $metrics = $analyzer->getNodeMetrics($package->getClasses()->current());
        $this->assertEquals(0, $metrics['dit']);
    }

    /**
     * Tests that the analyzer calculates the correct DIT values.
     *
     * @return void
     * @covers PHP_Depend_Metrics_Inheritance_Analyzer
     * @group pdepend
     * @group pdepend::metrics
     * @group pdepend::metrics::inheritance
     * @group unittest
     */
    public function testCalculateDITMetricOneLevelInheritance()
    {
        $packages = self::parseTestCaseSource(__METHOD__);
        $package  = $packages->current();

        $analyzer = new PHP_Depend_Metrics_Inheritance_Analyzer();
        $analyzer->analyze($packages);

        $metrics = $analyzer->getNodeMetrics($package->getClasses()->current());
        $this->assertEquals(1, $metrics['dit']);
    }

    /**
     * Tests that the analyzer calculates the correct DIT values.
     *
     * @return void
     * @covers PHP_Depend_Metrics_Inheritance_Analyzer
     * @group pdepend
     * @group pdepend::metrics
     * @group pdepend::metrics::inheritance
     * @group unittest
     */
    public function testCalculateDITMetricTwoLevelNoInheritance()
    {
        $packages = self::parseTestCaseSource(__METHOD__);
        $package  = $packages->current();

        $analyzer = new PHP_Depend_Metrics_Inheritance_Analyzer();
        $analyzer->analyze($packages);

        $metrics = $analyzer->getNodeMetrics($package->getClasses()->current());
        $this->assertEquals(2, $metrics['dit']);
    }

    /**
     * Tests that the analyzer calculates the correct DIT values.
     *
     * @return void
     * @covers PHP_Depend_Metrics_Inheritance_Analyzer
     * @group pdepend
     * @group pdepend::metrics
     * @group pdepend::metrics::inheritance
     * @group unittest
     */
    public function testCalculateDITMetricThreeLevelNoInheritance()
    {
        $packages = self::parseTestCaseSource(__METHOD__);
        $package  = $packages->current();

        $analyzer = new PHP_Depend_Metrics_Inheritance_Analyzer();
        $analyzer->analyze($packages);

        $metrics = $analyzer->getNodeMetrics($package->getClasses()->current());
        $this->assertEquals(3, $metrics['dit']);
    }

    /**
     * Tests that the analyzer calculates the correct DIT values.
     *
     * @return void
     * @covers PHP_Depend_Metrics_Inheritance_Analyzer
     * @group pdepend
     * @group pdepend::metrics
     * @group pdepend::metrics::inheritance
     * @group unittest
     */
    public function testCalculateDITMetricFourLevelNoInheritance()
    {
        $packages = self::parseTestCaseSource(__METHOD__);
        $package  = $packages->current();

        $analyzer = new PHP_Depend_Metrics_Inheritance_Analyzer();
        $analyzer->analyze($packages);

        $metrics = $analyzer->getNodeMetrics($package->getClasses()->current());
        $this->assertEquals(4, $metrics['dit']);
    }
}