<?php declare(strict_types = 1);

namespace App\Core\Latte;

use Latte\CompileException;
use Latte\Compiler;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\MacroTokens;
use Latte\PhpWriter;


class TypeMacros extends MacroSet
{

	public static function install(Compiler $compiler)
	{
		$me = new self($compiler);
		$me->addMacro('type', [$me, 'macroType']);

		return $me;
	}


	public function macroType(MacroNode $node, PhpWriter $writer)
	{
		$tokens = new MacroTokens($node->args . $node->modifiers);
		$node->modifiers = '';

		$type = trim($tokens->joinUntil(MacroTokens::T_VARIABLE));
		$tokens->nextToken();
		if (!$tokens->isCurrent(MacroTokens::T_VARIABLE)) {
			throw new CompileException("Unexpected {$tokens->currentValue()}, variable expected");
		}
		$variable = $tokens->currentValue();
		$types = TypeParser::parse($type, null);
		$assertFunction = TypeHelper::createAssertFunction($types);
		if ($assertFunction === null) {
			return '';
		}
		$variableName = \substr($variable, 1);
		$escapedVariableName = \var_export($variableName, true);

		return
			"/* {type {$node->args}{$node->modifiers}} */\n"
		  . "/** @var $type $variable */\n"
		  . "$variable = func_num_args() === 1 ? func_get_arg(0)[$escapedVariableName] : \$this->params[$escapedVariableName];\n"
		  . "($assertFunction)(" . $variable . ");\n";

	}
}
