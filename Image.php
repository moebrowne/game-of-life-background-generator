<?php namespace GameOfLife;


class Image {

	private $resource;

    protected $width;
    protected $height;

    const IMAGE_PATH = 'G';

    /**
     * Initiate a blank image
     * @param int $width
     * @param int $height
     */
	 function __construct($width, $height)
	{
        $this->width = $width;
        $this->height = $height;

		$this->create();
	}

	private function create() {

		// Create an image canvas
		$this->resource = imagecreatetruecolor($this->width, $this->height);

		// Ensure the alpha channel is maintained
		imagesavealpha($this->resource, true);

		$this->setBackgroundTransparent();

	}

	public function renderCell(Cell $cell)
	{
        $y = $cell->getPositionX() * (Cell::WIDTH + Cell::SPACING);
        $x = $cell->getPositionY() * (Cell::HEIGHT + Cell::SPACING);

        $colourResource = imagecolorallocatealpha($this->resource, $cell->colour['R'], $cell->colour['G'], $cell->colour['B'], $cell->colour['A']);
		imagefilledrectangle($this->resource, $x, $y, ($x + Cell::WIDTH), ($y + Cell::HEIGHT), $colourResource);
	}

	/**
	 * Set the background of the image
	 *
	 * @param $red
	 * @param $green
	 * @param $blue
	 * @param $alpha
	 */
	private function setBackground($red, $green, $blue, $alpha) {

		// Set the background of the image
		$backgroundColor = imagecolorallocatealpha($this->resource, $red, $green, $blue, $alpha);
		imagefill($this->resource, 0, 0, $backgroundColor);
	}

    /**
     * Set the background of the image with a hex code
     *
     * @param $hex
     * @param int $alpha
     */
	public function setBackgroundHex($hex, $alpha = 0)
	{
		// Split the hex code into RGB
		list($red, $green, $blue) = sscanf($hex, "#%02x%02x%02x");

		// Pass the RGBA data to the background setter
		$this->setBackground($red, $green, $blue, $alpha);

	}

	/**
	 * Set the background of the images as transparent
	 */
	public function setBackgroundTransparent()
	{
		// Pass the RGBA data to the background setter
		$this->setBackground(0, 0, 0, 127);
	}

	/**
	 * Write the image data to a file
	 *
	 * @param $path
	 */
	public function write($path)
	{
		// Write the image data to a file
		imagepng($this->resource, $path.".png");
	}

}