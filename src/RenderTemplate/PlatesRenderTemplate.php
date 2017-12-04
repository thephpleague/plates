<?php

namespace League\Plates\RenderTemplate;

use League\Plates;
use Throwable;
use Exception;

final class PlatesRenderTemplate implements Plates\RenderTemplate
{
    private $resolve_name;
    private $resolve_data;
    private $include;
    private $create_render_context;
    private $render_context_var_name;

    public function __construct(
        $resolve_name = null,
        $resolve_data = null,
        $include = null,
        $create_render_context = null,
        $render_context_var_name = 'v'
    ) {
        $this->resolve_name = $resolve_name ?: Plates\Template\idResolveName();
        $this->resolve_data = $resolve_data ?: Plates\Util\id();
        $this->include = $include ?: Plates\Template\phpInclude();
        $this->create_render_context = $create_render_context ?: Plates\RenderContext::factory();
        $this->render_context_var_name = $render_context_var_name;
    }

    public function renderTemplate(Plates\Template $template) {
        $resolve_name = $this->resolve_name;

        $path = $template->resolveName($this->resolve_name);
        $data = $template->resolveData($this->resolve_data);

        $template->addContext([
            'path' => $path,
            'current_directory' => dirname($path)
        ]);
        $ctx = call_user_func($this->create_render_context, $this, $template);

        if (array_key_exists($this->render_context_var_name, $data)) {
            throw new Plates\Exception\PlatesException('Cannot set render context because a variable already exists as ' . $this->render_context_var_name);
        }
        $data[$this->render_context_var_name] = $ctx;

        // this is done for BC reasons}
        $include = $this->include->bindTo($ctx);

        try {
            return $include($path, $data);
        } catch (Exception $e) {

        } catch (Throwable $e) {

        }

        throw new Plates\Exception\PlatesException(
            'An exception occurred while rendering Template ' . $template->name . '.',
            0,
            $e
        );
    }
}
