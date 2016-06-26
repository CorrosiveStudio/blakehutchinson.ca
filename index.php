<?php

require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\Finder\Finder;

$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$sort = function (\SplFileInfo $a, \SplFileInfo $b){
    return (int)$a->getfilename() < (int)$b->getfilename();
};

$app->get('pictures', function() use($app, $sort) {
    $finder = new Finder();
    $finder->files()->sort($sort);
    $finder->in(__DIR__.'/img/')->name('*.jpg');

    return $app['twig']->render('pictures.twig', array('images' => $finder));
});

$app->get('videos', function() use($app, $sort) {
    $finder = new Finder();
    $finder->files()->sort($sort);
    $finder->in(__DIR__.'/video/')->name('*.png');

    return $app['twig']->render('videos.twig', array('videos' => $finder));
});

$app->get('{page}', function($page) use($app) {
    return $app['twig']->render($page.'.twig');
});

$app->error(function (\Exception $e, $code) use($app) {
    return $app['twig']->render('home.twig');
});

$app->run();
