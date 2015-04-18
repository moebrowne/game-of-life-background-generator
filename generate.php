<?php


//generate a Game of Life generation

class GameOfLifeBackground {
	
	private $matrix = [];
	
	public $cells = [
		'width' => 13,
		'height' => 13,
		'spacing'  => 3,
	];
	
	public $board = [
		'width' => 2560,
		'height' => 1024,
	];
		
	public function generate() {
		
		$this->board['width'] = 2560;
		$this->board['height'] = 1024;
		
		$board_cols = round($this->board['width']/($this->cells['width']+$this->cells['spacing']));
		$board_rows = round($this->board['height']/($this->cells['height']+$this->cells['spacing']));
		
		for($i=0;$i<(($this->board['width']*$this->board['height'])/350);$i++) {
			$matrix[mt_rand(0,$board_cols)][mt_rand(0,$board_rows)] = true;
			}
		
		$matrix_gen = $matrix;
		$matrix_penultiamte = $matrix;
		
		//process the matrix
		for($generation=0;$generation<(int)$_GET['gen'];$generation++) {
	
			//write out this generation
			$gd = imagecreatetruecolor($this->board['width'], $this->board['height']);
			$colour = imagecolorallocatealpha($gd, 85, 152, 215, 100);
			imagesavealpha($gd, true);
			$color = imagecolorallocatealpha($gd, 0, 0, 0, 127);
			imagefill($gd, 0, 0, $color);
			
			for($matrix_x=0;$matrix_x<$board_cols;$matrix_x++) {
				for($matrix_y=0;$matrix_y<$board_rows;$matrix_y++) {
					
					$cell_living = (bool)$matrix[$matrix_x][$matrix_y];
					
					$neighbours = $this->number_of_neighbours($matrix,$matrix_x,$matrix_y);
					
					$matrix_neighbours[$matrix_x][$matrix_y] = $neighbours;
					
					if($cell_living) {
						if($neighbours < 2 || $neighbours > 3) {
							unset($matrix_gen[$matrix_x][$matrix_y]);
							}
						}
						else if($neighbours == 3) {
							$matrix_gen[$matrix_x][$matrix_y] = true;
							}
					
					$pos_x = ($matrix_x*($this->cells['width']+$this->cells['spacing']));
					$pos_y = ($matrix_y*($this->cells['height']+$this->cells['spacing']));
					
					if($cell_living) {
						imagefilledrectangle($gd, $pos_x, $pos_y, ($pos_x+$this->cells['width']), ($pos_y+$this->cells['height']), $colour);
						}
					
					//check there  are still some 'living' cells
					if($matrix_gen === false) {break;}
					
					}
				//check there  are still some 'living' cells
				if($matrix_gen === false) {break;}
				}
			
			//write the XML
			
			if($generation > 2) {
				$xml .= "
				<static>
					<duration>7</duration>
					<file>/home/oliver/WEBSITE/uplyme.com/GOL/G/G_".str_pad(($generation-2),3,"0",STR_PAD_LEFT).".png</file>
				</static>
				";
				}
			
			if($generation > 3) {
				$xml .= "
				<transition>
					<duration>1</duration>
					<from>/home/oliver/WEBSITE/uplyme.com/GOL/G/G_".str_pad(($generation-2),3,"0",STR_PAD_LEFT).".png</from>
					<to>/home/oliver/WEBSITE/uplyme.com/GOL/G/G_".str_pad(($generation-1),3,"0",STR_PAD_LEFT).".png</to>
				</transition>
				";
				}
			
			
			$matrix_penultiamte = $matrix;
			
			$matrix = $matrix_gen;
			
			imagepng($gd,"./G/G_".str_pad($generation,3,"0",STR_PAD_LEFT).".png");
			imagedestroy($gd);
			
			//check there  are still some 'living' cells
			if($matrix_gen === false) {break;}
			}
		
		file_put_contents("text.xml","<background>".$xml."</background>");
		
		$gd = imagecreatetruecolor($this->board['width'], $this->board['height']);
		imagesavealpha($gd, true);
		//$colour = imagecolorallocate($gd, 85, 152, 215);
		$colour = imagecolorallocatealpha($gd, 85, 152, 215, 100);
		$color = imagecolorallocatealpha($gd, 0, 0, 0, 127);
		imagefill($gd, 0, 0, $color);
		
		for($matrix_x=0;$matrix_x<$board_cols;$matrix_x++) {
			for($matrix_y=0;$matrix_y<$board_rows;$matrix_y++) {
				if($matrix_penultiamte[$matrix_x][$matrix_y]) {
					
					$pos_x = ($matrix_x*($this->cells['width']+$this->cells['spacing']));
					$pos_y = ($matrix_y*($this->cells['height']+$this->cells['spacing']));
					
					imagefilledrectangle($gd, $pos_x, $pos_y, ($pos_x+$this->cells['width']), ($pos_y+$this->cells['height']), $colour);
					//imagesetpixel($gd, $matrix_x,$matrix_y, $colour);
					}
				}
			}
		
		//var_dump($matrix_neighbours);
		
		header('Content-Type: image/png');
		imagepng($gd);
		
		}
	
	private function number_of_neighbours($matrix,$x,$y) {
		
		for($dx=-1;$dx<=1;$dx++) {
			for($dy=-1;$dy<=1;$dy++) {
				//skip the cell we are checking
				if($dx == 0 && $dy == 0) {continue;}
				
				if($matrix[($x+$dx)][($y+$dy)]) {
					$neighbours++;
					}
				
				}
			}
		
		return $neighbours;
		
		}
	
	}

$gol = new GameOfLifeBackground;

$gol->generate();

?>