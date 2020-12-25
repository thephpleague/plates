<?php

namespace League\Plates\Tests\Template;

use League\Plates\Engine;
use League\Plates\Template\Theme;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

final class ThemeTest extends TestCase
{
    /** @var Engine */
    private $engine;
    /** @var ?string */
    private $result;
    /** @var \Throwable */
    private $exception;

    /** @test */
    public function engine_renders_with_single_themes() {
        $this->given_a_directory_structure_is_setup_like('templates', ['main.php' => '<html></html>']);
        $this->given_an_engine_is_created_with_theme(Theme::new($this->vfsPath('templates')));
        $this->when_the_engine_renders('main');
        $this->then_the_rendered_template_matches('<html></html>');
    }

    /** @test */
    public function engine_renders_with_theme_hierarchy() {
        $this->given_a_directory_structure_is_setup_like('templates', [
            'parent' => [
                'main.php' => '<?php $this->layout("layout") ?>parent',
                'layout.php' => '<html>parent: <?=$this->section("content")?></html>'
            ],
            'child' => [
                'layout.php' => '<html>child: <?=$this->section("content")?></html>'
            ],
        ]);
        $this->given_an_engine_is_created_with_theme(Theme::hierarchy([
            Theme::new($this->vfsPath('templates/parent'), 'Parent'),
            Theme::new($this->vfsPath('templates/child'), 'Child'),
        ]));
        $this->when_the_engine_renders('main');
        $this->then_the_rendered_template_matches('<html>child: parent</html>');
    }

    /** @test */
    public function duplicate_theme_names_in_hierarchies_are_not_allowed() {
        $this->when_a_theme_is_created_like(function() {
            Theme::hierarchy([
                Theme::new('templates/a'),
                Theme::new('templates/b'),
            ]);
        });
        $this->then_an_exception_is_thrown_with_message('Duplicate theme names in hierarchies are not allowed. Received theme names: [Default, Default].');
    }

    /** @test */
    public function nested_hierarchies_are_not_allowed() {
        $this->when_a_theme_is_created_like(function() {
            Theme::hierarchy([
                Theme::hierarchy([Theme::new('templates', 'A'), Theme::new('templates', 'B')])
            ]);
        });
        $this->then_an_exception_is_thrown_with_message('Nested theme hierarchies are not allowed, make sure to use Theme::new when creating themes in your hierarchy. Theme B is already in a hierarchy.');
    }

    /** @test */
    public function empty_hierarchies_are_not_allowed() {
        $this->when_a_theme_is_created_like(function() {
            Theme::hierarchy([]);
        });
        $this->then_an_exception_is_thrown_with_message('Empty theme hierarchies are not allowed.');
    }

    /** @test */
    public function template_not_found_errors_reference_themes_checked() {
        $this->given_a_directory_structure_is_setup_like('templates', []);
        $this->given_an_engine_is_created_with_theme(Theme::hierarchy([
            Theme::new($this->vfsPath('templates/one'), 'One'),
            Theme::new($this->vfsPath('templates/two'), 'Two'),
        ]));
        $this->when_the_engine_renders('main');
        $this->then_an_exception_is_thrown_with_message('The template "main" was not found in the following themes: Two:vfs://templates/two/main.php, One:vfs://templates/one/main.php');
    }

    private function given_a_directory_structure_is_setup_like(string $rootDir, array $directoryStructure) {
        vfsStream::setup($rootDir);
        vfsStream::create($directoryStructure);
    }

    private function given_an_engine_is_created_with_theme(Theme $theme) {
        $this->engine = \League\Plates\Engine::fromTheme($theme);
    }

    private function when_a_theme_is_created_like(callable $fn) {
        try {
            $fn();
        } catch (\Throwable $e) {
            $this->exception = $e;
        }
    }

    private function vfsPath(string $path): string {
        return vfsStream::url($path);
    }

    private function when_the_engine_renders(string $templateName, array $data = []) {
        try {
            $this->result = $this->engine->render($templateName, $data);
        } catch (\Throwable $e) {
            $this->exception = $e;
        }
    }

    private function then_the_rendered_template_matches(string $expected) {
        if ($this->exception) {
            throw $this->exception;
        }

        $this->assertEquals($expected, $this->result);
    }

    private function then_an_exception_is_thrown_with_message(string $expectedMessage) {
        $this->assertNotNull($this->exception, 'Expected an exception to be thrown with message: ' . $expectedMessage);
        $this->assertEquals($expectedMessage, $this->exception->getMessage(), 'Expected an exception to be thrown with message: ' . $expectedMessage);
    }
}
