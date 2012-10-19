<?php

require_once 'libs/MongoAssist.php';
require_once 'libs/Slim/Slim.php';
require_once 'libs/TwigAutoloader.php';

Twig_Extensions_Autoloader::register();

require_once 'libs/TwigView.php';

require_once 'app/Command.php';

class Application {

    const Title = 'Deveaque.com - inspiration girls';

    /**
     * @var Slim
     */
    private $slim;

    public function __construct() {
        $this->initializeSlim();
        $this->createRoutes();
        $this->slim->run();
    }

    private function createRoutes() {
        $this->createBaseSiteCommands();
        $this->createContentCommands();
        $this->createAdminCommands();
    }

    private function createBaseSiteCommands() {
        $this->addGetCommand('/(page:pageId)', 'MainSitePages', 'showDefault');
        $this->addGetCommand('/post/:title/(page:pageId)', 'MainSitePages', 'showByTitle');
        $this->addGetCommand('/post/:id', 'MainSitePages', 'showPost');
        $this->addGetCommand('/tag/:tag/(page:pageId)', 'MainSitePages', 'showByTag');
        $this->addGetCommand('/login', 'AuthHandler', 'login');
    }

    private function createContentCommands() {
        $this->addGetCommand('/image/full/:year/:month/:day/:image', 'ContentHandler', 'showFullPostImage');
        $this->addGetCommand('/image/small/:year/:month/:day/:image', 'ContentHandler', 'showSmallPostImage');
    }

    private function createAdminCommands() {
        $this->addGetAdminCommand('/upload', 'AdminPage', 'showUpload');
        $this->addPostAdminCommand('/post/add', 'PostHandler', 'addPost');
        $this->addPostAdminCommand('/post/edit/:id', 'PostHandler', 'editPost');
        $this->addGetAdminCommand('/post/delete/:id', 'PostHandler', 'deletePost');
        $this->addPostAdminCommand('/tag/save', 'TagHandler', 'saveTag');
        $this->addGetAdminCommand('/tag/:tag/attach/:post', 'TagHandler', 'attachTagToPost');
        $this->addGetAdminCommand('/tag/:tag/deattach/:post', 'TagHandler', 'deattachTag');
        $this->addGetAdminCommand('/editors/post/:id', 'EditorsHandler', 'getPostEditor');
        $this->addGetAdminCommand('/editors/tag/:id', 'EditorsHandler', 'getTagEditor');
    }

    private function addGetCommand($path, $class, $method) {
        $command = new Command($this->getSlim(), array($class, $method));
        $this->slim->get($path, $command->getCallback());
    }

    private function addPostCommand($path, $class, $method) {
        $command = new Command($this->getSlim(), array($class, $method));
        $this->slim->post($path, $command->getCallback());
    }

    private function addGetAdminCommand($path, $class, $method) {
        if(!self::isAdmin()) return;
        $command = new Command($this->getSlim(), array($class, $method));
        $this->slim->get($path, $command->getCallback());
    }

    private function addPostAdminCommand($path, $class, $method) {
        if(!self::isAdmin()) return;
        $command = new Command($this->getSlim(), array($class, $method));
        $this->slim->post($path, $command->getCallback());
    }

    private function initializeSlim() {
        $this->slim = new Slim(array(
                                    'view'           => new TwigView(),
                                    'debug'          => $_SERVER['DEVELOP'],
                                    'log.enable'     => true,
                                    'log.path'       => '../slim-log',
                                    'log.level'      => 4,
                                    'templates.path' => '../templates'
                               ));
        $this->setupGlobalTemplateData();
    }

    private function setupGlobalTemplateData() {
        $this->getSlim()->view()->appendData(array('siteTitle' => self::Title));
        $this->getSlim()->view()->appendData(array('logoTitle' => self::Title));
        $this->getSlim()->view()->appendData(array('isAdmin' => self::isAdmin()));
        $this->getSlim()->view()->appendData(array('isDevelop' => $_SERVER['DEVELOP']));
    }

    public static function isAdmin() {
        return $_SERVER['DEVELOP'] || in_array($_SERVER['REMOTE_ADDR'],
                                               array('92.62.59.95',
                                                     '89.110.48.143'));
    }

    private function getSlim() {
        return $this->slim;
    }

}
