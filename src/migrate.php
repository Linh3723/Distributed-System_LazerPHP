<?php
require __DIR__ . '/../vendor/autoload.php';
define('LAZER_DATA_PATH', __DIR__ . '/../database/');
use Lazer\Classes\Helpers\Validate;
use Lazer\Classes\Database as Lazer;

try {
    if (!Validate::table('expenses')->exists()) {
        Lazer::create('expenses', [
            'id' => 'integer',
            'description' => 'string',
            'amount' => 'float',
            'date' => 'string'
        ]);
    }

    if (!Validate::table('income')->exists()) {
        Lazer::create('income', [
            'id' => 'integer',
            'source' => 'string',
            'amount' => 'float',
            'date' => 'string'
        ]);
    }

    echo "Database tables created successfully!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
