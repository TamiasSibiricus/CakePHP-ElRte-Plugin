CakePHP-ElRte-Plugin
====================

elRTE plugin for CakePHP 2.x

Installation
============

Your need jQuery, jQuery enabled app. Plugin works fine with jQuery 1.7.2 and jQuery UI 1.8.21

You have two ways to install plugin in your app dir
standalone plugin - git clone git://github.com/TamiasSibiricus/CakePHP-ElRte-Plugin.git Plugin/ElRte
as submodule      - git submodule add git://github.com/TamiasSibiricus/CakePHP-ElRte-Plugin.git Plugin/ElRte

Usage
=====

Bootstrap
---------
<?php
//...some code
CakePlugin::load('ElRte');

Controller
----------
<?php

class MyController extends AppController
{
    public $helpers = array(
		'Editor' => array(
			'className' => 'ElRte.ElRte'
		),
	);
}
?>

View
----
<?php
    echo $this->Form->input('fieldName'),
         $this->Editor->render('fieldName');
;

Known bugs
==========

1. Editor don't understand relative urls for images. Temporairely plugin use hacked fersion of elrte.full.js

TODO
====

1. Add dynamic detection previous loaded jQuery and jQuery UI and load own libraries when jQuery was not detected.


