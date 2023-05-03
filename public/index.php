<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

// Libs
require __DIR__ .'/../src/config/error_handling.php';
require __DIR__ . '/../src/config/database.php';
require __DIR__ . '/../src/libs/xssClean.php';
require __DIR__ . '/../src/libs/hashString.php';

// MiddleWares
require __DIR__ . '/api/v0/middlewares/json_bodyparser.php';

// Controllers
require __DIR__ . '/api/v0/controllers/chat_group_member.php';
require __DIR__ . '/api/v0/controllers/chat_group.php';
require __DIR__ . '/api/v0/controllers/message.php';
require __DIR__ . '/api/v0/controllers/user.php';

// Models
require __DIR__ . '/api/v0/models/chat_group_member.php';
require __DIR__ . '/api/v0/models/chat_group.php';
require __DIR__ . '/api/v0/models/message.php';
require __DIR__ . '/api/v0/models/user.php';

// Routes
require __DIR__ . '/../src/routes/user.php';
require __DIR__ . '/../src/routes/message.php';
require __DIR__ . '/../src/routes/chat_group.php';


$app->addErrorMiddleware(false, false, false);

$app->run();