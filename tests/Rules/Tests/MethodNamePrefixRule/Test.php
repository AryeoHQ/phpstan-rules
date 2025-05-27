<?php

namespace Aryeo\PHPStan\Tests\Rules\Tests\MethodNamePrefixRule;

use Aryeo\PHPStan\Rules\Tests\MethodNamePrefixRule;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

class Test extends RuleTestCase
{
	protected function getRule(): Rule
	{
		return new MethodNamePrefixRule(
			reflectionProvider: $this->getContainer()
				->getByType(ReflectionProvider::class)
		);
	}

	public function test_rule(): void
	{
		$this->analyse(
			files: [
				__DIR__ . '/data/test-cases.php'
			],
			expectedErrors: [
				[
					'Test method [method_that_is_invalid] must start with `test`.',
					16, // the line number where the class is declared
				]
			],
		);
	}
}
