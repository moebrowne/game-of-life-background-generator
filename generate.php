<?php namespace GameOfLife;

include "Image.php";
include "Cell.php";

//generate a Game of Life generation

class GameOfLifeBackground {

	// The array containing the whole matrix
	private $matrix = [];

	private $xml = "";

    const XML_PATH = './background.xml';

    protected $rows;
    protected $columns;

    protected $boardWidth;
    protected $boardHeight;

	// The number of generations to run
    protected $generations;

	// Initialise!
	function __construct($generations = 10, $boardWidth = 2560, $boardHeight = 1024)
	{
        $this->generations = $generations;
        $this->boardWidth = $boardWidth;
        $this->boardHeight = $boardHeight;

		// Empty the image directory
		array_map('unlink', glob(Image::IMAGE_PATH . "/*.*"));

		// Calculate the number of columns and rows on the board
        $this->rows = ($this->boardWidth/(Cell::WIDTH + Cell::SPACING));
        $this->columns = ($this->boardHeight/(Cell::HEIGHT + Cell::SPACING));

        // Populate the board with dead cells
        for ($column = 0; $column < $this->columns; $column++) {
            for ($row = 0; $row < $this->rows; $row++) {
                $this->matrix[$column][$row] = new Cell($column, $row);
            }
        }
	}

	/**
	 * Populate the matrix with a random set of cells
	 *
	 * @param int $density
	 */
	public function randomMatrix($density = 600)
	{
		for($i=0;$i<(($this->boardWidth*$this->boardHeight)/$density);$i++) {
            $this->matrix[mt_rand(0, ($this->columns-1))][mt_rand(0, ($this->rows-1))]->birth();
		}
	}

    private function matrixClone()
    {
        $matrixClone = [];
        for($matrixX = 0; $matrixX < $this->columns; $matrixX++) {
            for ($matrixY = 0; $matrixY < $this->rows; $matrixY++) {
                $matrixClone[$matrixX][$matrixY] = clone $this->matrix[$matrixX][$matrixY];
            }
        }

        return $matrixClone;
    }

	/**
	 * Generate the game of life
	 */
	public function generate() {

		// Fill the matrix with a random set of cells
		$this->randomMatrix();

		//process the matrix
		for($generationNum = 0; $generationNum < $this->generations; $generationNum++) {

            $matrixTemp = $this->matrixClone();

			// initiate a new generation
			$generationImage = new Image($this->boardWidth, $this->boardHeight);
			
			for($matrixX = 0; $matrixX < $this->columns; $matrixX++) {
				for($matrixY = 0; $matrixY < $this->rows; $matrixY++) {

                    $cell = $matrixTemp[$matrixX][$matrixY];

                    $livingNeighbours = $this->livingCellNeighbourCount($cell);

                    if ($cell->isAlive()) {
                        if ($livingNeighbours < 2 || $livingNeighbours > 3) {
                            $cell->kill();
                        }
                    } else {
                        if ($livingNeighbours == 3) {
                            $cell->birth();
                        }
                    }
					
					if($cell->isAlive()) {
						$generationImage->renderCell($cell);
						}

					}
				}

            //check there are still some 'living' cells
            if($this->extinctionHasOccured()) {
                break;
            }
			
			//write the XML
			
			if($generationNum > 2) {
				$this->xml .= "
				<static>
					<duration>7</duration>
					<file>".__DIR__.'/'.Image::IMAGE_PATH."/G_".str_pad(($generationNum-2),3,"0",STR_PAD_LEFT).".png</file>
				</static>
				";
				}
			
			if($generationNum > 3) {
				$this->xml .= "
				<transition>
					<duration>1</duration>
					<from>".__DIR__.'/'.Image::IMAGE_PATH."/G_".str_pad(($generationNum-2),3,"0",STR_PAD_LEFT).".png</from>
					<to>".__DIR__.'/'.Image::IMAGE_PATH."/G_".str_pad(($generationNum-1),3,"0",STR_PAD_LEFT).".png</to>
				</transition>
				";
				}

			$this->matrix = $matrixTemp;

			$generationImage->write(Image::IMAGE_PATH . "/G_".str_pad($generationNum,3,"0",STR_PAD_LEFT));
			}

        file_put_contents(self::XML_PATH, "<background>". $this->xml ."</background>");
		
		}

    /**
     * Look at all the cells surrounding this cell and
     * count how many are still alive
     *
     * @param Cell $cell
     * @return int
     */
	private function livingCellNeighbourCount(Cell $cell) {

		// init
		$neighbours = 0;

		// Loop through each of the surrounding cells
		for($dx = -1; $dx <= 1; $dx++) {
			for($dy=-1; $dy <= 1; $dy++) {
				//skip the cell we are checking
				if($dx == 0 && $dy == 0) {
                    continue;
                    }

                if(!isset($this->matrix[($cell->getPositionX()+$dx)][($cell->getPositionY()+$dy)])) {
                    continue;
                }
				
				if($this->matrix[($cell->getPositionX()+$dx)][($cell->getPositionY()+$dy)]->isAlive()) {
					$neighbours++;
					}
				
				}
			}

		// Return the number of living neighbours
		return (int)$neighbours;
		
		}

    /**
     * Check if there are any cells alive
     *
     * @return bool
     */
    private function extinctionHasOccured()
    {
        for ($row = 0; $row < $this->rows; $row++) {
            for ($column = 0; $column < $this->columns; $column++) {
                if ($this->matrix[$column][$row]->isAlive()) {
                    return false;
                }
            }
        }

        return true;
    }
	
	}


$gol = new GameOfLifeBackground(15);

$gol->generate();

?>