<?php

declare(strict_types=1);

namespace Aryeo\PHPStan\Rules\Enums;

use PhpParser\Node;
use PhpParser\Node\Stmt\EnumCase;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * @implements Rule<EnumCase>
 */
final class EnumCasePascalCaseRule implements Rule
{
	public function getNodeType(): string
	{
		return EnumCase::class;
	}

	/**
	 * @throws ShouldNotHappenException
	 */
	public function processNode(Node $node, Scope $scope): array
	{
		$classReflection = $scope->getClassReflection();

		if ($classReflection === null) {
			return [];
		}

		// Ensure that the scope is an enum.
		if (!$classReflection->isEnum()) {
			return [];
		}

		// Ensure that the enum case name is Pascal Case
		if (preg_match('/^[A-Z][A-Za-z0-9]*$/', $name = $node->name->toString())) {
			return [];
		}

		return [
			RuleErrorBuilder::message("Enum case name [{$name}] is not in PascalCase.")
				->identifier('enums.namePascalCase')
				->build(),
		];
	}
}
