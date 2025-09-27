<?php

use Sudoku\Box\Number;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class NumberTest extends TestCase {

    #[Test]
    public function candidate_count():void {
        $number = new Number(0, 0, 0);
        $int = $number->candidateCount();
        $this->assertSame(9, $int);
    }

    #[Test]
    public function get_candidates_zero():void {
        $number = new Number(0, 0, 0);
        $this->assertSame(511, $number->getCandidates());
    }

    #[Test]
    public function get_candidates_num():void {
        $number = new Number(3, 0, 0);
        $this->assertSame(1<<(3-1), $number->getCandidates());
    }

    #[Test]
    public function eliminate():void {
        $number = new Number(0, 0, 0);
        $number->eliminate(509);
        $this->assertSame(2, $number->getCandidates());
    }

    #[Test]
    public function valid():void {
        $number = new Number(0, 0, 0);
        $number->eliminate(511);
        $this->assertSame(false, $number->valid());
    }

    #[Test]
    public function get_RCB():void {
        $number = new Number(0, 8, 8);
        $this->assertSame([8, 8, 8], $number->getRCB());
    }

    #[Test]
    public function get_digit_one():void {
        $number = new Number(0, 0, 0);
        $number->eliminate(510);
        $this->assertSame(1, $number->getDigit());
    }

    #[Test]
    public function get_digit_two():void {
        $number = new Number(2, 0, 0);
        $this->assertSame(2, $number->getDigit());
    }

    #[Test]
    public function get_digit_zero():void {
        $number = new Number(0, 0, 0);
        $this->assertSame(0, $number->getDigit());
    }

}

