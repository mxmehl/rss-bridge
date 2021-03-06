<?php
class FSBridge extends BridgeAbstract{

	public function loadMetadatas() {

		$this->maintainer = "qwertygc";
		$this->name = "Futurasciences";
		$this->uri = "http://www.futura-sciences.com";
		$this->description = "Returns the 5 newest posts from FS (full text)";
		$this->update = "03/11/2015";

	}

    public function collectData(array $param){

    function FS_StripCDATA($string) {
    	$string = str_replace('<![CDATA[', '', $string);
    	$string = str_replace(']]>', '', $string);
    	return $string;
    }
    function FS_ExtractContent($url) {
	$html2 = file_get_html($url);
	$text = $html2->find('div.fiche-actualite', 0)->innertext;
	$text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);
	return $text;
    }
        $html = file_get_html('http://www.futura-sciences.com/rss/actualites.xml') or $this->returnError('Could not request Futura Sciences.', 404);
	$limit = 0;

	foreach($html->find('item') as $element) {
	 if($limit < 5) {
	 $item = new \Item();
	 $item->title = FS_StripCDATA($element->find('title', 0)->innertext);
	 $item->uri = FS_StripCDATA($element->find('guid', 0)->plaintext);
	 $item->timestamp = strtotime($element->find('pubDate', 0)->plaintext);
	 $item->content = FS_ExtractContent($item->uri);
	 $this->items[] = $item;
	 $limit++;
	 }
	}
    
    }

    public function getName(){
        return 'Futura Sciences';
    }

    public function getURI(){
        return 'http://www.futura-sciences.com/';
    }

    public function getCacheDuration(){
        return 3600; // 1 hour
        // return 0; // 1 hour
    }
}
