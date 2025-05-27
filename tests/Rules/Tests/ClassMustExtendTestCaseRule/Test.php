<?php

namespace Aryeo\PHPStan\Tests\Rules\Tests\ClassMustExtendTestCaseRule;

use Aryeo\PHPStan\Rules\Tests\ClassMustExtendTestCaseRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

class Test extends RuleTestCase
{
	protected function getRule(): Rule
	{
		return new ClassMustExtendTestCaseRule();
	}

	public function test_rule(): void
	{
		$this->analyse(
			files: [
				__DIR__ . '/data/test-cases.php'
			],
			expectedErrors: [
				[
					'Test class [BadTest] must extend `Tests\TestCase`.',
					12, // the line number where the class is declared
				]
			],
		);
	}
}
