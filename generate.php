<?php namespace GameOfLife;

error_reporting(E_ALL & ~E_NOTICE);

include "Image.php";
include "Config.php";

//generate a Game of Life generation

class GameOfLifeBackground {

	// The array containing the whole matrix
	private $matrix = [];

	private $xml = "";

	// The number of generations to run
	public $generationCount = 15;

	// An array of generations
	private $generations = [];

	// Initialise!
	function __construct()
	{
		// Calculate the number of columns and rows on the board
		Config::setData('boardRows', round(Config::getData('boardHeight')/(Config::getData('cellHeight')+Config::getData('cellSpacing'))));
		Config::setData('boardCols', round(Config::getData('boardWidth')/(Config::getData('cellWidth')+Config::getData('cellSpacing'))));
		
	}

	/**
	 * Populate the matrix with a random set of cells
	 *
	 * @param int $density
	 */
	public function randomMatrix($density=350)
	{

		for($i=0;$i<((Config::getData('boardWidth')*Config::getData('boardHeight'))/$density);$i++) {
			$this->matrix[mt_rand(0,Config::getData('boardCols'))][mt_rand(0,Config::getData('boardRows'))] = true;
		}
	}

	/**
	 * Generate the game of life
	 */
	public function generate() {

		// Fill the matrix with a random set of cells
		$this->randomMatrix();
		
		$matrixTemp = $this->matrix;

		//process the matrix
		for($generationNum=0;$generationNum<$this->generationCount;$generationNum++) {

			// initiate a new generation
			$generationImage = new Image();
			
			for($matrixX=0;$matrixX<Config::getData('boardCols');$matrixX++) {
				for($matrixY=0;$matrixY<Config::getData('boardRows');$matrixY++) {
					
					$cellLiving = (bool)$this->matrix[$matrixX][$matrixY];
					
					$livingNeighbours = $this->cellNoNeighbours($matrixX,$matrixY);
					
					if($cellLiving) {
						if($livingNeighbours < 2 || $livingNeighbours > 3) {
							unset($matrixTemp[$matrixX][$matrixY]);
							}
						}
						else if($livingNeighbours == 3) {
							$matrixTemp[$matrixX][$matrixY] = true;
							}
					
					$pos_x = ($matrixX*(Config::getData('cellWidth')+Config::getData('cellSpacing')));
					$pos_y = ($matrixY*(Config::getData('cellHeight')+Config::getData('cellSpacing')));
					
					if($cellLiving) {
						$generationImage->renderCell($pos_x, $pos_y);
						}
					
					//check there  are still some 'living' cells
					if($matrixTemp === false) {break;}
					
					}
				//check there  are still some 'living' cells
				if($matrixTemp === false) {break;}
				}
			
			//write the XML
			
			if($generationNum > 2) {
				$this->xml .= "
				<static>
					<duration>7</duration>
					<file>/home/oliver/WEBSITE/uplyme.com/GOL/G/G_".str_pad(($generationNum-2),3,"0",STR_PAD_LEFT).".png</file>
				</static>
				";
				}
			
			if($generationNum > 3) {
				$this->xml .= "
				<transition>
					<duration>1</duration>
					<from>/home/oliver/WEBSITE/uplyme.com/GOL/G/G_".str_pad(($generationNum-2),3,"0",STR_PAD_LEFT).".png</from>
					<to>/home/oliver/WEBSITE/uplyme.com/GOL/G/G_".str_pad(($generationNum-1),3,"0",STR_PAD_LEFT).".png</to>
				</transition>
				";
				}

			$this->matrix = $matrixTemp;

			$generationImage->write(Config::getData('ImagesPath')."/G_".str_pad($generationNum,3,"0",STR_PAD_LEFT));
			$generationImage->destroy();
			
			//check there  are still some 'living' cells
			if($matrixTemp === false) {break;}
			}
		
		file_put_contents(Config::getData('XMLPath'), "<background>".$this->xml."</background>");
		
		}

	/**
	 * Look at all the cells surrounding this cell and
	 * count how many are still alive
	 *
	 * @param $x
	 * @param $y
	 * @return int
	 * @internal param $matrix
	 */
	private function cellNoNeighbours($x,$y) {

		// init
		$neighbours = 0;

		// Loop through each of the surrounding cells
		for($dx=-1;$dx<=1;$dx++) {
			for($dy=-1;$dy<=1;$dy++) {
				//skip the cell we are checking
				if($dx == 0 && $dy == 0) {continue;}
				
				if($this->matrix[($x+$dx)][($y+$dy)]) {
					$neighbours++;
					}
				
				}
			}

		// Return the number of living neighbours
		return (int)$neighbours;
		
		}
	
	}


// Configure!
Config::setData('boardWidth', 2560);
Config::setData('boardHeight', 1024);

Config::setData('background', [
	'R' => 0,
	'G' => 0,
	'B' => 0,
	'A' => 127,
]);

Config::setData('cellWidth', 13);
Config::setData('cellHeight', 13);
Config::setData('cellSpacing', 3);

Config::setData('XMLPath', './background.xml');

Config::setData('ImagesPath', './G');


$gol = new GameOfLifeBackground;

$gol->generate();

?>