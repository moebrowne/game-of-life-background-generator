<?php


//generate a Game of Life generation

class gol_gen {
	
	private $matrix;
		
	public function generate() {
		
		/*
		for($i=0;$i<50;$i++) {
			$matrix[$i] = array_fill(0,50,false);
			}
		*/
		
		$board_x = 2560;
		$board_y = 1024;
		
		$cell_width = 13;
		$cell_height = 13;
		
		$cell_spacing = 3;
		
		$board_cols = round($board_x/($cell_width+$cell_spacing));
		$board_rows = round($board_y/($cell_height+$cell_spacing)); 
		
		//echo "board: ".$board_cols."x".$board_rows;
		
		
		for($i=0;$i<(($board_x*$board_y)/350);$i++) {
			$matrix[mt_rand(0,$board_cols)][mt_rand(0,$board_rows)] = true;
			}
		
		$matrix_gen = $matrix;
		$matrix_penultiamte = $matrix;
		
		//process the matrix
		for($generation=0;$generation<(int)$_GET['gen'];$generation++) {
	
			//write out this generation
			$gd = imagecreatetruecolor($board_x, $board_y);
			//$colour = imagecolorallocate($gd, 85, 152, 215);
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
					
					$pos_x = ($matrix_x*($cell_width+$cell_spacing));
					$pos_y = ($matrix_y*($cell_height+$cell_spacing));
					
					if($cell_living) {
						imagefilledrectangle($gd, $pos_x, $pos_y, ($pos_x+$cell_width), ($pos_y+$cell_height), $colour);
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
		
		$gd = imagecreatetruecolor($board_x, $board_y);
		imagesavealpha($gd, true);
		//$colour = imagecolorallocate($gd, 85, 152, 215);
		$colour = imagecolorallocatealpha($gd, 85, 152, 215, 100);
		$color = imagecolorallocatealpha($gd, 0, 0, 0, 127);
		imagefill($gd, 0, 0, $color);
		
		for($matrix_x=0;$matrix_x<$board_cols;$matrix_x++) {
			for($matrix_y=0;$matrix_y<$board_rows;$matrix_y++) {
				if($matrix_penultiamte[$matrix_x][$matrix_y]) {
					
					$pos_x = ($matrix_x*($cell_width+$cell_spacing));
					$pos_y = ($matrix_y*($cell_height+$cell_spacing));
					
					imagefilledrectangle($gd, $pos_x, $pos_y, ($pos_x+$cell_width), ($pos_y+$cell_height), $colour);
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

$gol = new gol_gen;

$gol->generate();

?>