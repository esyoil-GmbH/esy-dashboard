<?php
/**
 * Created by PhpStorm.
 * User: bennet
 * Date: 18.04.18
 * Time: 20:56
 */

namespace Angle\Engine\Template;

use org\bovigo\vfs\vfsStream;

class Engine {

    protected $tokens;
    private $stream;

    public function __construct($viewsFolder = "templates") {
        $this->tokens = new Syntax($viewsFolder);
    }

    public function render($view, $params = []) {
        $params['engine'] = $this;
        if (!empty($params)) extract($params);
        $viewArray = explode('/', $view);
        $viewPath = implode('/', $viewArray);

        vfsStream::setup($viewPath);

        $file = vfsStream::url($view . '.php');
        $this->localCompile(file_get_contents($view));

        file_put_contents($file, $this->getStream());

        ob_start();
        include $file;
        ob_end_flush();
    }

    private function localCompile($stream) {
        $this->setStream($stream);
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader, [
            //'cache' => 'template_cache',
            'cache' => false
        ]);

        $this->stream = $twig->render('test.html', ['name' => 'Fabien']);
    }

    public function getStream() {
        return $this->stream;
    }

    public function setStream($stream) {
        $this->stream = $stream;
    }

    public function compile($view, $params = []) {
        $params["app_url"] = APP_URL;
        $params['engine'] = $this;
        if (!empty($params)) extract($params);
        $viewArray = explode('/', $view);
        $viewPath = implode('/', $viewArray);

        vfsStream::setup($viewPath);

        $file = vfsStream::url($view . '.php');
        $this->localCompile(file_get_contents($view));

        file_put_contents($file, $this->getStream());

        ob_start();
        include $file;
        $cont = ob_get_contents();
        ob_end_clean();
        return $cont;

    }

    public function setViewsFolder($new) {
        $this->tokens->setViewsFolder($new);
    }

    public function getViewsFolder() {
        return $this->tokens->getViewsFolder();
    }
}