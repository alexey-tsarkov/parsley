<?php

declare(strict_types=1);

$finder = new PhpCsFixer\Finder()
    ->in(__DIR__)
    ->ignoreDotFiles(false)
;

return new PhpCsFixer\Config()
    ->setRules([
        '@PER-CS' => true,
        '@autoPHPMigration' => true,
    ])
    ->setFinder($finder)
;
