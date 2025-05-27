<?php

namespace Tests {
	abstract class TestCase
	{
	}
}

namespace {
	class ExampleTest extends Tests\TestCase
	{
		public function test_method_that_is_valid(): void
		{
		}

		public function method_that_is_invalid(): void
		{
		}

		protected function ignored_protected_method(): void
		{
		}

		private function ignored_private_method(): void
		{
		}
	}

	class IgnoredClass
	{
		public function not_a_test(): void
		{
		}
	}

	abstract class IgnoredAbstractClass extends Tests\TestCase
	{
		public function not_a_test(): void
		{
		}
	}
}
