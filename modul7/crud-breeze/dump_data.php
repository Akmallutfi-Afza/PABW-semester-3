<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$users = App\Models\User::all();
$uploads = App\Models\Upload::all();
echo "USERS: ".$users->count().PHP_EOL;
foreach ($users as $u) {
    echo $u->id.' | '.$u->name.' | '.$u->email.' | '.$u->created_at.PHP_EOL;
}
echo "UPLOADS: ".$uploads->count().PHP_EOL;
foreach ($uploads as $f) {
    echo $f->id.' | '.$f->judul.' | '.$f->filename.' | '.$f->filepath.' | user '.$f->user_id.' | '.$f->created_at.PHP_EOL;
}
