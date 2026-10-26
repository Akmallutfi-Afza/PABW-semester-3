<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Upload;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

$rows = Upload::where('id', '>', 1)->get();
foreach ($rows as $row) {
    Storage::disk('public')->delete($row->filepath);
    $row->delete();
}
DB::statement('ALTER TABLE uploads AUTO_INCREMENT = 2');
echo 'uploads left: '.Upload::count().PHP_EOL;
foreach (Storage::disk('public')->files('uploads') as $f) {
    echo $f.PHP_EOL;
}
