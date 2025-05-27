<?php

namespace Tests {
	abstract class TestCase
	{
	}
}

namespace {
	class ExampleTest extends Tests\TestCase
	{
		public function test_name_that_is_valid(): void
		{
		}

		public function testNameThatIsInvalid(): void
		{
		}

		public function test_anonymous_class_is_ignored(): void
		{
			new class {
				public function ignoredAnonymousMethod(): void
				{
				}
			};
		}

		protected function ignoredProtectedMethod(): void
		{
		}

		private function ignoredPrivateMethod(): void
		{
		}
	}

	class IgnoredClass
	{
		public function notATest(): void
		{
		}
	}

	abstract class IgnoredAbstractClass extends Tests\TestCase
	{
		public function ignoredAbstractMethod(): void
		{
		}
	}
}
