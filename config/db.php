<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=db;dbname=books_db',
    'username' => 'books_user',
    'password' => 'books_password',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
