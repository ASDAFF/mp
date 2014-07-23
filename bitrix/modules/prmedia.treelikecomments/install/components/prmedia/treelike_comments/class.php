<?

class CPrmediaTreelikeComments extends CBitrixComponent 
{
	var $MODULE_ID = "prmedia.treelikecomments";
	
	public function ParseText($text = "", $arParams = array())
	{
		while (preg_match("#\[quote\](.*?)\[/quote\]#si", $text)) 
			$text = preg_replace("#\[quote\](.*?)\[/quote\]#si", '<div class="quote">\1</div>', $text);
			
		$text = preg_replace("#\[code\](.*?)\[/code\]#si", '<div class="code">\1</div>', $text);
		preg_match_all('#<div class="code">(.*?)</div>#si', $text, $code);
		
		$items = $code[0];
	
		$values = array();
		foreach($items as $key => $val)
			$values[] = "#$".$key."#";
		
		$text = str_replace($items, $values, $text);
		
		// Parse BB
	
		$search[] = "#\[b\](.*?)\[/b\]#si";
		$search[] = "#\[i\](.*?)\[/i\]#si";
		$search[] = "#\[s\](.*?)\[/s\]#si";
		$search[] = "#\[u\](.*?)\[/u\]#si";
		$search[] = "#\[IMG\](.*?)\[/IMG\]#si";      
	
		$replace[] = '<strong>\1</strong>';
		$replace[] = '<i>\1</i>';
		$replace[] = '<strike>\1</strike>';
		$replace[] = '<u>\1</u>';
		$replace[] = '<div><img style="max-width:275px; max-height: 275px; padding: 5px 0 5px 0; clear: both;" src="\1"></div>';

		$text = preg_replace($search, $replace, $text);
	
		$text = preg_replace('#\[url=(https?|ftp)://(\S+[^\s.,>!?])\](.*?)\[\/url\]#si', '<a '.$arParams["NO_FOLLOW"].' href="http://$2">$3</a>', $text);           
	
		// set link if there's no editor
		
		if($arParams["SHOW_FILEMAN"] == 0)
			$text = preg_replace("#(https?|ftp)://\S+[^\s.,>)\];'\"!?]#",'<a '.$arParams["NO_FOLLOW"].' href="\\0">\\0</a>',$text);
	
		$text = str_replace($values, $items, $text);  
	
		return $text;  	
	}
	
	function SetSmiles($text = "", $iconArr = array(), $arParams = array())
	{
		
		// Text must be filled 
		
		if(strlen($text) == 0)
			return false;
			
		// Replace icons with pathnames
		
		foreach($iconArr as $icon => $path):
			$text = str_replace($icon, '<img src="'.$arParams["FOLDER"].'/images/icons/smiles/'.$path.'" />', $text);
		endforeach;
		
		return $text;
	}
	
	public function StopWordExists($text)
	{
		$existence = false;
		$wordString = COption::GetOptionString($this->MODULE_ID, 'stop_words');
		
		if($wordString != "")
		{
			$wordString = str_replace(" ", "", $wordString);
			$wordArr = explode(",", $wordString);
			$regex = "/".implode("|", $wordArr)."/i";
	
			if(preg_match($regex, $text))
				$existence = true;		
		}
		
		return $existence;
	}
	
	public function GetIP()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
			$ip = $_SERVER['HTTP_CLIENT_IP'];
			
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			
		else
			$ip = $_SERVER['REMOTE_ADDR'];

		return $ip;		
	}
	
	function GenerateString($arParams = array()) 
	{ 
		$chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
	
		$count_chars = strlen ($chars);
		
		for ($i = 0; $i < $arParams["LENGTH"]; $i++) 
		{  
			$rand = rand (1,$count_chars); // generating random figure from 1 to length of char string 
			$string .= substr ($chars, $rand, 1); // returning string of 1 char
		}   
		
		return $string; 
	}
}

?>