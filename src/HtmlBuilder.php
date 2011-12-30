<?php

class HtmlBuilder {
	
	var $options = array(
			'charset' => 'UTF-8'
		);
	
	var $head = array();
	var $tail = array();
	
	public function __construct($options=array()){
		$this->options = array_merge($this->options, $options);
	}
	
	public function push($tag, $attrs=array()){
		$tag = $this->parseTag($tag, $attrs);
		$this->head[] = $this->htmlTagOpen($tag, $attrs);
		$this->tail[] = $this->htmlTagClose($tag);
		return $this;
	}
	
	public function pop($n=1){
		$this->head[] = array_pop($this->tail);
		if ($n>1) $this->pop($n-1);
		return $this;
	}
	
	public function insert($tag, $text=null, $attrs=array()){
		$tag = $this->parseTag($tag, $attrs);
		
		if ($text!==null) {
			$this->head[] = $this->htmlTagOpen($tag, $attrs);
			$this->text($text);
			$this->head[] = $this->htmlTagClose($tag);
		} else {
			$this->head[] = $this->htmlTagOpenClose($tag, $attrs);
		}
		
		return $this;
	}
	
	public function text($text){
		$this->head[] = $this->htmlEscape($text);
		return $this;
	}
	
	public function html($html){
		$this->head[] = strVal($html);
		return $this;
	}
	
	public function asHtml(){
		return implode("", $this->head).implode("", array_reverse($this->tail));
	}
	
	public function __toString(){
		return $this->asHtml();
	}
	
	protected function parseTag($tag, &$attrs){
		$parts = preg_split('/(\.|#)/', $tag, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
		$tag = array_shift($parts);
		
		if ($parts)
		{
			while(count($parts)>=2)
			{
				$op = array_shift($parts);
				$value = array_shift($parts);
				
				if ($op=='#') $attrs['id'] = $value;
				if ($op=='.') {
					if ($attrs['class']) {
						$attrs['class'] .= ' '.$value;
					} else {
						$attrs['class'] = $value;
					}
				}
			}
		}
		
		return $tag;
	}
	
	
	/**
	 *	Utility methods for generating HTML
	 */
	protected function htmlTagOpen($tag, $attrs=array()){
		$attrs = $attrs ? ' '.html_attrs($attrs) : '';
		return '<'.$tag.$attrs.'>';
	}
	
	protected function htmlTagClose($tag){
		return '</'.$tag.'>';
	}
	
	protected function htmlTagOpenClose($tag, $attrs=array()){
		$attrs = $attrs ? ' '.html_attrs($attrs) : '';
		return '<'.$tag.$attrs.' />';
	}
	
	protected function htmlAttrs($attrs){
		$html = array();
		foreach($attrs as $k=>$v) {
			if ($v!==NULL) $html[] = $k.'="'.$this->htmlEscapeAttribute($v).'"';
		}
		return implode(" ", $html);
	}
	
	protected function htmlEscape($value){
		return htmlspecialchars($value, ENT_COMPAT, $this->options['charset']);
	}
	
	protected function htmlEscapeAttribute($value){
		return $this->htmlEscape($value);
	}
}