<?php namespace GameOfLife;


//generate a Game of Life generation

class GameOfLifeBackground {

	// The array containing the whole matrix
	private $matrix = [];

	// The number of generations to run
	public $generationCount = 15;

	// An array of generations
	private $generations = [];

	// Properties of each cell
	public $cells = [
		'width' => 13,
		'height' => 13,
		'spacing'  => 3,
	];

	// The size of the board we can draw on
	public $board = [
		'width' => 2560,
		'height' => 1024,
		'rows' => 0,
		'cols' => 0,
	];

	private $image = [
		'background' => [
			'red' => 0,
			'green' => 0,
			'blue' => 0,
			'alpha' => 0,
		]
	];

	// Initialise!
	function __construct()
	{
		// Calculate the number of columns and rows on the board
		$this->board['rows'] = round($this->board['height']/($this->cells['height']+$this->cells['spacing']));
		$this->board['cols'] = round($this->board['width']/($this->cells['width']+$this->cells['spacing']));
		
	}

	/**
	 * Populate the matrix with a random set of cells
	 *
	 * @param int $density
	 */
	public function randomMatrix($density=350)
	{

		for($i=0;$i<(($this->board['width']*$this->board['height'])/$density);$i++) {
			$matrix[mt_rand(0,$this->board['cols'])][mt_rand(0,$this->board['rows'])] = true;
		}
	}

	private function generationInitiate($generationID)
	{
		$this->generations[$generationID] = [
			'ID' => $generationID,
			'image' => $this->imageInitiate(),
			'matrix' => []
		];

		return $this->generations[$generationID];
	}

	/**
	 * Generate the game of life
	 */
	public function generate() {

		// Fill the matrix with a random set of cells
		$this->randomMatrix();
		
		$matrix_gen = $matrix;
		
		//process the matrix
		for($generation=0;$generation<$this->generationCount;$generation++) {

			// initiate a new generation
			$generationData = $this->generationInitiate($generation);
			
			for($matrix_x=0;$matrix_x<$this->board['cols'];$matrix_x++) {
				for($matrix_y=0;$matrix_y<$this->board['rows'];$matrix_y++) {
					
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
			
			$matrix = $matrix_gen;
			
			imagepng($gd,"./G/G_".str_pad($generation,3,"0",STR_PAD_LEFT).".png");
			imagedestroy($gd);
			
			//check there  are still some 'living' cells
			if($matrix_gen === false) {break;}
			}
		
		file_put_contents("text.xml","<background>".$xml."</background>");
		
		}

	/**
	 * Determine how many neighbours a cell has
	 *
	 * @param $matrix
	 * @param $x
	 * @param $y
	 * @return mixed
	 */
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