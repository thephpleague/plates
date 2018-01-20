<?php

class DocsConfig
{
    public $defaultVersion;
    // holds versionSlug => [branch, versionName]
    public $versionsMap;
    public $defaultLayoutPath;
    public $docPath;

    public function __construct(string $defaultVersion, array $versionsMap, string $defaultLayoutPath, string $docPath) {
        $this->defaultVersion = $defaultVersion;
        $this->versionsMap = $versionsMap;
        $this->defaultLayoutPath = $defaultLayoutPath;
        $this->docPath = $docPath;
    }

    public static function createFromArray(array $data) {
        return new self(
            $data['defaultVersion'],
            $data['versionsMap'],
            $data['defaultLayoutPath'],
            $data['docPath']
        );
    }

    public function getDefaultVersionTuple() {
        return $this->versionsMap[$this->defaultVersion];
    }

    public static function createFromConfigFile($path) {
        return self::createFromArray(json_decode(file_get_contents($path), true));
    }
}

function gitCheckout($branch) {
    return `git checkout $branch`;
}

function gitResetBranch() {
    return `git checkout -- .`;
}

function gitCurrentBranch() {
    return `git rev-parse --abbrev-ref HEAD`;
}

function loadDefaultLayout(DocsConfig $config) {
    return file_get_contents($config->defaultLayoutPath);
}

function reduce($func, $items, $acc = null) {
    foreach ($items as $key => $val) {
        $acc = $func($acc, $val, $key);
    }
    return $acc;
}

function buildVersionsYaml(DocsConfig $config, $currentVersion) {
    $fmt = <<<YAML
current_version: %s
generated: true
versions:
%s
YAML;

    return sprintf($fmt,
        $currentVersion,
        reduce(function($acc, $val, $version) {
            list($branch, $name) = $val;
            return $acc . sprintf("  %s: %s\n", $version, $name);
        }, $config->versionsMap, '')
    );
}

function buildDocs(DocsConfig $config, $branch, $version, $defaultLayout, $versionsYaml, $dst) {
    gitCheckout($branch);
    file_put_contents($config->defaultLayoutPath, $defaultLayout);
    file_put_contents($config->docPath . '/_data/versions.yml', $versionsYaml);
    $cmd = sprintf('cd %s; bundle exec jekyll build -b %s -d %s',
        $config->docPath,
        '/' . $version,
        $dst . '/' . $version
    );
    shell_exec($cmd);

    if ($version == $config->defaultVersion) {
        `cp $dst/$version/index.html $dst/index.html`;
        `cp $dst/$version/CNAME $dst/CNAME`;
    }

    gitResetBranch();
}

function copyDocs($src) {
    `mkdir -p .generated`;
    return `cp -r $src/* .generated`;
}

function createBuildDir() {
    $dir = sys_get_temp_dir() . '/plates-docs-' . md5(mt_rand());
    mkdir($dir, 0777, true);
    return $dir;
}

function rmRf($dir) {
    return `rm -rf $dir`;
}

function main($argv) {
    if (count($argv) < 2) {
        printf("usage %s <config-path>\n", $argv[0]);
        exit;
    }

    $buildDir = createBuildDir();
    printf("build dir: %s\n", $buildDir);

    $startingBranch = gitCurrentBranch();
    chdir(__DIR__ . '/..');

    $config = DocsConfig::createFromConfigFile($argv[1]);
    $defaultLayout = loadDefaultLayout($config);

    foreach ($config->versionsMap as $versionSlug => list($branch, $versionName)) {
        $versionsYaml = buildVersionsYaml($config, $versionSlug);
        buildDocs($config, $branch, $versionSlug, $defaultLayout, $versionsYaml, $buildDir);
    }

    copyDocs($buildDir);

    rmrf($buildDir);

    gitCheckout($startingBranch);
}

main($argv);
