<?php
use PHPUnit\Framework\TestCase;

class SplitCollectionTest extends TestCase {
    public function testRegularCase() {
        $this->assertEquals(
            [[1,2,3], [4,5,6], [7,8,9], [10]],
            splitCollection([1,2,3,4,5,6,7,8,9,10], 3)
        );
    }

    public function testListWithEvenDivision() {
        $this->assertEquals(
            [[1,2], [3,4], [5,6]],
            splitCollection([1,2,3,4,5,6], 2)
        );
    }

    public function testSingleElementList() {
        $this->assertEquals([[42]], splitCollection([42], 3));
    }

    public function testEmptyListThrowsException() {
        $this->expectException(InvalidArgumentException::class);
        splitCollection([], 3);
    }

    public function testNegativeSizeThrowsException() {
        $this->expectException(InvalidArgumentException::class);
        splitCollection([1,2,3], -2);
    }

    public function testNonNumericValuesThrowException() {
        $this->expectException(InvalidArgumentException::class);
        splitCollection([1, "two", 3], 2);
    }
}
?>