<?php

require_once 'Slim/Slim.php';
require_once 'Views/TwigView.php';
require_once 'Lib/Validate.php';

TwigView::$twigDirectory = __DIR__.'/Twig/lib/Twig';

$s = new Slim(array(
    'view' => new TwigView
));

$s->get('/',function() use ($s){
    $s->render('index.html');
});

$s->post('/',function() use ($s){
    //var_dump($_POST);
    $v = new Lib\Validate();
    $fieldStatus    = array();
    $fieldMessages  = array();

    $v->addRule('task-email','email');

    if ($v->validate($_POST) === false) {
        $fieldStatus    = $v->getStatus();
        $fieldMessages  = $v->getMessages();
    }

    $viewData = array('status'=>$fieldStatus,'messages'=>$fieldMessages);
//var_dump($viewData);

    $s->render('index.html',$viewData);
});

// execute the page!
$s->run();

?>
