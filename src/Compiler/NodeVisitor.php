<?php

namespace League\Plates\Compiler;

class NodeVisitor extends \PhpParser\NodeVisitorAbstract
{
    private $functions;

    private $rawFunctions;

    public function __construct(array $functions = array(), array $rawFunctions = array())
    {
        $this->functions = $functions;
        $this->rawFunctions = $rawFunctions;
    }

    public function enterNode(\PhpParser\Node $node)
    {
        if ($node instanceof \PhpParser\Node\Stmt\Echo_) {

            // Is the raw() function
            $isTheRawFunction = (
                $node->exprs[0] instanceof \PhpParser\Node\Expr\FuncCall and
                $node->exprs[0]->name == 'raw'
            );

            // Is the raw() method
            $isTheRawMethod = (
                $node->exprs[0] instanceof \PhpParser\Node\Expr\MethodCall and
                $node->exprs[0]->var->name == 'this' and
                $node->exprs[0]->name === 'raw'
            );

            // Is a "raw function"
            $isARawFunction = (
                $node->exprs[0] instanceof \PhpParser\Node\Expr\FuncCall and
                in_array($node->exprs[0]->name, $this->rawFunctions)
            );

            // Is a "raw method"
            $isARawMethod = (
                $node->exprs[0] instanceof \PhpParser\Node\Expr\MethodCall and
                $node->exprs[0]->var->name == 'this' and
                in_array($node->exprs[0]->name, $this->rawFunctions)
            );

            if ($isTheRawFunction or $isTheRawMethod) {

                // Remove raw() function/method
                $node->exprs = $node->exprs[0]->args;

            } elseif (!$isARawFunction and !$isARawMethod) {

                // Insert escape method
                $node->exprs = array(
                    new \PhpParser\Node\Expr\MethodCall(
                        new \PhpParser\Node\Expr\Variable('this'),
                        new \PhpParser\Node\Name('escape'),
                        $node->exprs
                    )
                );
            }
        }
    }

    public function leaveNode(\PhpParser\Node $node)
    {
        if ($node instanceof \PhpParser\Node\Expr\FuncCall and in_array($node->name, $this->functions)) {

            // Convert template functions to methods
            return new \PhpParser\Node\Expr\MethodCall(
                new \PhpParser\Node\Expr\Variable('this'),
                new \PhpParser\Node\Name($node->name),
                $node->args
            );
        }
    }
}
