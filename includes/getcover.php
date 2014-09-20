<?php
 
class GetCover
{
 
	var $image;
	var $image_tmp;
	var $image_type;
	var $image_x;
	var $image_y;
 
	function load($filename)
	{
		$image_info = getimagesize($filename);
		$this->image_type = $image_info[2];
		if( $this->image_type == IMAGETYPE_JPEG ) {
 
			$this->image = imagecreatefromjpeg($filename);
		} elseif( $this->image_type == IMAGETYPE_GIF ) {
 
			$this->image = imagecreatefromgif($filename);
		} elseif( $this->image_type == IMAGETYPE_PNG ) {
 
			$this->image = imagecreatefrompng($filename);
		}
		else
			return false;
		
		$this->image_x = imagesx($this->image);
		$this->image_y = imagesy($this->image);
		$this->auto_crop();
		
		return true;
	}
	
	function is_white($color)
	{
		$r = ($color >> 16) & 0xFF;
		$g = ($color >> 8) & 0xFF;
		$b = $color & 0xFF;
		
		if (($r + $g + $b) / 3 >= 244)
			return true;
		else
			return false;
	}
	
	function auto_crop()
	{
		// si ratio trop fort, il y a surement des bandes a découper, on les cherche
		if (($this->image_x / $this->image_y) >= 0.9)
		{
			////////////////////
			// limite gauche
			
			// on regarde chaque pixel de la première ligne en partant de la gauche
			$y = $this->image_y;
			$left = 0;
			while($y == $this->image_y && $left < $this->image_x && $this->is_white(imagecolorat($this->image, $left, 0)))
			{
				$left++;
				// on vérifie si tout la bande verticale est blanche
				$y = 0;
				while($y < $this->image_y && $this->is_white(imagecolorat($this->image, $left, $y)))
					$y++;
			}
			
			////////////////////
			// limite droite
			
			// on regarde chaque pixel de la première ligne en partant de la droite
			$y = $this->image_y;
			$right = $this->image_x - 1;
			while($y == $this->image_y && $right < $this->image_x && $this->is_white(imagecolorat($this->image, $right, 0)))
			{
				$right--;
				// on vérifie si tout la bande verticale est blanche
				$y = 0;
				while($y < $this->image_y && $this->is_white(imagecolorat($this->image, $right, $y)))
					$y++;
			}
			
			////////////////////////////////
			// on découpe si nécéssaire
			if ($left != 0 || $right != $this->image_x)
			{
				$this->image_tmp = imagecreatetruecolor($right - $left + 1, $this->image_y);
				imagecopy($this->image_tmp, $this->image, 0, 0, $left, 0, ($right - $left + 1), $this->image_y);
				$this->image = $this->image_tmp;
				$this->image_x = $right - $left + 1;
			}
		}
	}
	
	function resize($height)
	{
		$ratio = $height / $this->image_y;
		$width = $this->image_x * $ratio;
		
		$this->image_tmp = imagecreatetruecolor($width, $height);
		imagecopyresampled($this->image_tmp, $this->image, 0, 0, 0, 0, $width, $height, $this->image_x, $this->image_y);
	}
	
	function save($filename)
	{
		imagejpeg($this->image_tmp, 'images/'.$filename, 90);
	}
}
