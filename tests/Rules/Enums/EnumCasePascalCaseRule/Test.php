<?php

namespace Aryeo\PHPStan\Tests\Rules\Enums\EnumCasePascalCaseRule;

use Aryeo\PHPStan\Rules\Enums\EnumCasePascalCaseRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

class Test extends RuleTestCase
{
	protected function getRule(): Rule
	{
		return new EnumCasePascalCaseRule();
	}

	public function test_rule(): void
	{
		$this->analyse(
			files: [
				__DIR__ . '/data/test-cases.php'
			],
			expectedErrors: [
				[
					'Enum case name [completed] is not in PascalCase.',
					6, // the line number where the enum case is declared
				],
				[
					'Enum case name [In_Review] is not in PascalCase.',
					7, // the line number where the enum case is declared
				]
			],
		);
	}
}
