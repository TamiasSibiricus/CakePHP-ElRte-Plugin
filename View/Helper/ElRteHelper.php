<?php

class ElRteHelper extends Helper
{
	/**
	 * Helpers for ElRTEHElper
	 *
	 * @var array
	 */
    public $helpers = array(
	    'Html',
	    'Form'
    );

	/**
	 * Configuration
	 *
	 * @var array
	 */
	public $configs = array(
		'cssClass' => 'el-rte',
		'lang' => 'en',
		//'height' => 280,
		//'absoluteURLs' => 'false',
		'toolbar' => 'complete'
	);
	public $fields_count = 0;

	public function __construct(View $View, $options = array()) {
		parent::__construct($View, $options);

		$options = Configure::read('ElRTE.config');
		if (!empty($configs) && is_array($configs)) {
			$this->configs = array_merge($configs, $options);
		}
		$this->configs['sitebaseURL'] = 'http://'.$_SERVER['SERVER_NAME'].Router::url('/');
	}

	public function afterRender(){
		if (!empty($this->fields_count)){
		//load editor
		$this->Html->css("ElRte.elrte.min", null, array('inline' => false));
		$this->Html->script("ElRte.elrte.full", array('inline' => false));
		$this->Html->script("ElRte.i18n/elrte.".$this->configs['lang'], array('inline' => false));

		//load finder
		$this->Html->css("ElRte.elfinder.min", null, array('inline' => false));
	        $this->Html->script("ElRte.elfinder.min", array('inline' => false));
		//$this->Html->script("ElRte.i18n/elfinder.".$this->configs['lang'], array('inline' => false));

		$js = "
		    //var dialog;
	            elRTE.prototype.options.panels.simple = [
	                'bold', 'italic', 'underline', 'justifyleft', 'justifyright',
	                'justifycenter', 'justifyfull', 'formatblock', 'insertorderedlist', 'insertunorderedlist',
		    ];
	            elRTE.prototype.options.toolbars.simple = ['simple'];
	        ";
		$this->Html->scriptBlock($js, array("inline"=> false));

		}
	}

    public function render($fieldName, $options = array()){

	    //$this->Form->setEntity($fieldName);
	    //$id = ucfirst($this->Form->model()).ucfirst($this->Form->field());
	    $id = $this->domId($fieldName);
	    if (!strpos($fieldName, ".")){
		    $id = ucfirst($this->Form->model()).ucfirst($id);
	    }

		$options = array_merge($this->configs, $options);

		$lines = '';
		foreach ($options as $option => $value) {
		    $lines .= $option . " : '" . $value . "',\n";
		}

		//modern way
		//https://github.com/Studio-42/elFinder/wiki/Integration-with-elRTE-1.x
		$lines .= "
			absoluteURLs : false,
	        cssfiles : ['".Router::url('/', true)."el_rte/css/elrte-inner.css'],
			fmOpen: function(callback) {
				if (!dialog) {
					// create new elFinder
					dialog = $('<div />').dialogelfinder({
	                    url : '".$this->Html->url(array( "plugin" => "el_rte", "controller"=>"el_finder", "action"=>"finder"))."',
	                    lang : 'en',
	                    dialog : { width : 800, modal : true, title : 'Select an Image' }, // open in dialog window
						commandsOptions: {
							getfile: {
							oncomplete : 'close' // close/hide elFinder
						}
					},
					getFileCallback: callback // pass callback to file manager
						//getFileCallback: function(file) { callback(file.path); } // pass callback to file manager
					});
				} else {
					// reopen elFinder
					dialog.dialogelfinder('open')
				}
			}\n
		";
	$opts = "{ ".$lines." }";
        $js = "
            $(document).ready(function(){
				$('#".$id."').elrte(".$opts.");
            });
        ";
		$this->fields_count = $this->fields_count + 1;
	    $this->Html->scriptBlock($js, array("inline"=> false));
    }  
}