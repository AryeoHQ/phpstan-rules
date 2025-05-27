<?php

namespace Tests {
	abstract class TestCase {}
}

namespace {
	abstract class AbstractTest extends PHPUnit\Framework\TestCase
	{
	}

	class BadTest extends TestCase
	{
	}

	class GoodTest extends Tests\TestCase
	{
	}

	class UnrelatedClass extends AbstractTest
	{
	}
}


