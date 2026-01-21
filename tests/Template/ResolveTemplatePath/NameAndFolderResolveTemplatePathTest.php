<?php

namespace League\Plates\Tests\Template\ResolveTemplatePath;

use League\Plates\Engine;
use League\Plates\Template\Theme;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class NameAndFolderResolveTemplatePathTest extends TestCase
{
    /** @var Engine */
    private $engine;

    /** @var ?string */
    private $result;

    /** @var \Throwable */
    private $exception;

    /** @test */
    public function engine_renders_with_path_hierarchy()
    {
        $this->given_a_directory_structure_is_setup_like('templates', [
            'parent' => [
                'main.php' => '<?php $this->layout("layout") ?>parent',
                'layout.php' => '<html>parent: <?=$this->section("content")?></html>',
            ],
            'child' => [
                'layout.php' => '<html>child: <?=$this->section("content")?></html>',
            ],
        ]);

        $this->engine->getResolveTemplatePath()->addPath($this->vfsPath('templates/parent'));
        $this->engine->getResolveTemplatePath()->prependPath($this->vfsPath('templates/child'));

        $this->when_the_engine_renders('main');
        $this->then_the_rendered_template_matches('<html>child: parent</html>');
    }

    protected function setUp(): void
    {
        vfsStream::setup('templates');

        $this->engine = new Engine(vfsStream::url('templates'));
    }

    private function given_a_directory_structure_is_setup_like(string $rootDir, array $directoryStructure)
    {
        vfsStream::setup($rootDir);
        vfsStream::create($directoryStructure);
    }

    private function vfsPath(string $path): string {
        return vfsStream::url($path);
    }

    private function when_the_engine_renders(string $templateName, array $data = [])
    {
        try {
            $this->result = $this->engine->render($templateName, $data);
        } catch (\Throwable $e) {
            $this->exception = $e;
        }
    }

    private function then_the_rendered_template_matches(string $expected)
    {
        if ($this->exception) {
            throw $this->exception;
        }

        $this->assertEquals($expected, $this->result);
    }
}
