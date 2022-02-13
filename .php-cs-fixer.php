 <?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/apps/RPGIdleGame/backend/tests')
    ->in(__DIR__.'/apps/RPGIdleGame/backend/src')
    ->in(__DIR__.'/apps/Backoffice/tests')
    ->in(__DIR__.'/apps/Backoffice/src')
    ->in(__DIR__.'/tests')
    ->in(__DIR__.'/src')
;

$config = new PhpCsFixer\Config();
$config
    ->setRiskyAllowed(true)
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache')
    ->setRules([
        '@PHP71Migration' => true,
        '@PHP71Migration:risky' => true,
        '@PHPUnit75Migration:risky' => true,
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'binary_operator_spaces' => [
            'default' => 'align_single_space_minimal',
        ],
        'concat_space' => [
            'spacing' => 'one',
        ],
        'constant_case' => true,
        'lowercase_keywords' => true,
        'magic_constant_casing' => true,
        'magic_method_casing' => true,
        'native_function_casing' => true,
        'native_function_invocation' => false,
        'modernize_strpos' => true, // needs PHP 8+ or polyfill
        'no_trailing_comma_in_singleline_array' => true,
        'no_whitespace_before_comma_in_array' => true,
        'normalize_index_brace' => true,
        'php_unit_test_case_static_method_calls' => [
            'call_type' => 'self',
        ],
        'single_line_comment_style' => [
            'comment_types' => ['asterisk', 'hash'],
        ],
        'trailing_comma_in_multiline' => true,
        'trim_array_spaces' => true,
        'whitespace_after_comma_in_array' => true,
    ])
    ->setFinder($finder)
;

return $config;
