<?php

namespace Aryeo\PHPStan\Tests\Rules\Tests\MethodNameSnakeCaseRule;

use Aryeo\PHPStan\Rules\Tests\MethodNameSnakeCaseRule;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

class Test extends RuleTestCase
{
	protected function getRule(): Rule
	{
		return new MethodNameSnakeCaseRule(
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
					'Test method [testNameThatIsInvalid] must be snake cased.',
					16, // the line number where the class is declared
				]
			],
		);
	}
}
